<?php

namespace App\Packages\GPT;

use App\Models\Conversation;

class ChatGPT
{

//    private $api_url = 'https://api.openai.com/v1/chat/completions';

    private string $apiUrl = 'https://key.wenwen-ai.com/v1/chat/completions';
    private string $apiKey = '';
    private StreamHandler $streamHandler;
    private ?DFA $dfa = NULL;
    private bool $checkSensitive = false;

    private Conversation $conversation;

    public function __construct($params)
    {
        $this->apiKey = $params['api_key'] ?? '';

        $this->conversation = $params['conversation'];
    }

    public function set_dfa(&$dfa): void
    {
        $this->dfa = $dfa;
        if (!empty($this->dfa) && $this->dfa->is_available()) {
            $this->checkSensitive = TRUE;
        }
    }

    public function qa($params): void
    {
        $messages = $params['messages'];

        $this->streamHandler = new StreamHandler(['conversation' => $this->conversation]);

        if ($this->checkSensitive) {
            $this->streamHandler->setDFA($this->dfa);
        }

        if (empty($this->apiKey)) {
            Res::end('OpenAI 的 api key 还没填', $this->conversation->id);
            return;
        }

        // 开启检测且提问包含敏感词
//        if ($this->checkSensitive && $this->dfa->containsSensitiveWords($question)) {
//            Res::end('您的问题不合适，AI暂时无法回答');
//            return;
//        }

        $json = json_encode([
            'model' => 'gpt-4',
            'messages' => $messages,
            'temperature' => 0.6,
            'stream' => true,
        ]);

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey,
        );

        $this->openai($json, $headers);
    }

    private function openai($json, $headers): void
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_WRITEFUNCTION, [$this->streamHandler, 'callback']);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            file_put_contents(storage_path('logs/openai.log'), curl_error($ch) . PHP_EOL . PHP_EOL, FILE_APPEND);
        }

        curl_close($ch);
    }

}
