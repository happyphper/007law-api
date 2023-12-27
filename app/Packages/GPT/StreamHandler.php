<?php

namespace App\Packages\GPT;

use App\Models\Conversation;

class StreamHandler
{
    private string $dataBuffer = '';//缓存，有可能一条data被切分成两部分了，无法解析json，所以需要把上一半缓存起来
    private int $counter = 0;//数据接收计数器
    private int $qmd5;//问题md5
    private array $chars = [];//字符数组，开启敏感词检测时用于缓存待检测字符
    private array $punctuation = [];//停顿符号
    private ?DFA $dfa;
    private bool $checkSensitive = false;

    private Conversation $conversation;

    private string $fulltext = '';


    public function __destruct()
    {
        file_put_contents(storage_path('logs/gpt.' . $this->qmd5 . '.log'), $this->counter . '完整内容==' . $this->fulltext . PHP_EOL . '===============' . PHP_EOL, FILE_APPEND);

        $this->conversation->messages()->create([
            'role' => 'assistant',
            'content' => $this->fulltext,
        ]);

        Res::end('Connection Closed', $this->conversation->id);
    }

    public function __construct($params)
    {
        $this->counter = 0;
        $this->qmd5 = $params['qmd5'] ?? time();
        $this->chars = [];
        $this->punctuation = ['，', '。', '；', '？', '！', '……'];
        $this->conversation = $params['conversation'];
    }

    public function setDFA(&$dfa): void
    {
        $this->dfa = $dfa;
        if (!empty($this->dfa) && $this->dfa->is_available()) {
            $this->checkSensitive = true;
        }
    }

    public function callback($ch, $data): int
    {
        $this->counter += 1;

        file_put_contents(storage_path('logs/gpt.' . $this->qmd5 . '.log'), $this->counter . '==' . $data . PHP_EOL . '--------------------' . PHP_EOL, FILE_APPEND);

        $result = json_decode($data, true);
        if (is_array($result)) {
            Res::end('openai 请求错误：' . json_encode($result));
            return strlen($data);
        }

        /*
            此处步骤仅针对 openai 接口而言
            每次触发回调函数时，里边会有多条data数据，需要分割
            如某次收到 $data 如下所示：
            data: {"id":"chatcmpl-6wimHHBt4hKFHEpFnNT2ryUeuRRJC","object":"chat.completion.chunk","created":1679453169,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"role":"assistant"},"index":0,"finish_reason":null}]}\n\ndata: {"id":"chatcmpl-6wimHHBt4hKFHEpFnNT2ryUeuRRJC","object":"chat.completion.chunk","created":1679453169,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"以下"},"index":0,"finish_reason":null}]}\n\ndata: {"id":"chatcmpl-6wimHHBt4hKFHEpFnNT2ryUeuRRJC","object":"chat.completion.chunk","created":1679453169,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"是"},"index":0,"finish_reason":null}]}\n\ndata: {"id":"chatcmpl-6wimHHBt4hKFHEpFnNT2ryUeuRRJC","object":"chat.completion.chunk","created":1679453169,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"使用"},"index":0,"finish_reason":null}]}

            最后两条一般是这样的：
            data: {"id":"chatcmpl-6wimHHBt4hKFHEpFnNT2ryUeuRRJC","object":"chat.completion.chunk","created":1679453169,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{},"index":0,"finish_reason":"stop"}]}\n\ndata: [DONE]

            根据以上 openai 的数据格式，分割步骤如下：
        */

        // 0、把上次缓冲区内数据拼接上本次的data
        $buffer = $this->dataBuffer . $data;

        //拼接完之后，要把缓冲字符串清空
        $this->dataBuffer = '';

        // 1、把所有的 'data: {' 替换为 '{' ，'data: [' 换成 '['
        $buffer = str_replace('data: {', '{', $buffer);
        $buffer = str_replace('data: [', '[', $buffer);

        // 2、把所有的 '}\n\n{' 替换维 '}[br]{' ， '}\n\n[' 替换为 '}[br]['
        $buffer = str_replace("}\n\n{", '}[br]{', $buffer);
        $buffer = str_replace("}\n\n[", '}[br][', $buffer);

        // 3、用 '[br]' 分割成多行数组
        $lines = explode('[br]', $buffer);

        // 4、循环处理每一行，对于最后一行需要判断是否是完整的json
        $line_c = count($lines);
        foreach ($lines as $li => $line) {
            if (trim($line) == '[DONE]') {
                //数据传输结束
                $this->dataBuffer = '';
                $this->counter = 0;
                $this->sensitive_check();
                Res::end('', $this->conversation->id);
                break;
            }
            $line_data = json_decode(trim($line), true);
            if (!is_array($line_data) || !isset($line_data['choices']) || !isset($line_data['choices'][0])) {
                if ($li == ($line_c - 1)) {
                    //如果是最后一行
                    $this->dataBuffer = $line;
                    break;
                }
                //如果是中间行无法json解析，则写入错误日志中
                file_put_contents('./log/error.' . $this->qmd5 . '.log', json_encode(['i' => $this->counter, 'line' => $line, 'li' => $li], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL . PHP_EOL, FILE_APPEND);
                continue;
            }

            if (isset($line_data['choices'][0]['delta']['content'])) {
                $this->sensitive_check($line_data['choices'][0]['delta']['content']);
            }
        }

        return strlen($data);
    }

    private function sensitive_check($content = null): void
    {
        // 如果不检测敏感词，则直接返回给前端
        if (!$this->checkSensitive) {
            $this->fulltext .= $content;
            Res::send($content, $this->conversation->id);
            return;
        }
        //每个 content 都检测是否包含换行或者停顿符号，如有，则成为一个新行
        if (!$this->has_pause($content)) {
            $this->chars[] = $content;
            return;
        }
        $this->chars[] = $content;
        $content = implode('', $this->chars);
        if ($this->dfa->containsSensitiveWords($content)) {
            $content = $this->dfa->replaceWords($content);
            Res::send($content, $this->conversation->id);
        } else {
            foreach ($this->chars as $char) {
                Res::send($char, $this->conversation->id);
            }
        }
        $this->chars = [];
    }

    private function has_pause($content): bool
    {
        if ($content == null) {
            return true;
        }

        $has_p = false;
        if (is_numeric(strripos(json_encode($content), '\n'))) {
            $has_p = true;
        } else {
            foreach ($this->punctuation as $p) {
                if (is_numeric(strripos($content, $p))) {
                    $has_p = true;
                    break;
                }
            }
        }

        return $has_p;
    }
}

