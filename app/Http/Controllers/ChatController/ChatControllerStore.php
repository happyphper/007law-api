<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Packages\GPT\ChatGPT;
use App\Packages\GPT\Res;
use Illuminate\Http\Request;

class ChatControllerStore extends Controller
{
    public function __construct()
    {
        // 这行代码用于关闭输出缓冲。关闭后，脚本的输出将立即发送到浏览器，而不是等待缓冲区填满或脚本执行完毕。
        ini_set('output_buffering', 'off');

        // 这行代码禁用了 zlib 压缩。通常情况下，启用 zlib 压缩可以减小发送到浏览器的数据量，但对于服务器发送事件来说，实时性更重要，因此需要禁用压缩。
        ini_set('zlib.output_compression', false);

        // 这行代码使用循环来清空所有当前激活的输出缓冲区。ob_end_flush() 函数会刷新并关闭最内层的输出缓冲区，@ 符号用于抑制可能出现的错误或警告。
        while (@ob_end_flush()) {
        }

        // 这行代码设置 HTTP 响应的 Content-Type 为 text/event-stream，这是服务器发送事件（SSE）的 MIME 类型。
        header('Content-Type: text/event-stream');

        // 这行代码设置 HTTP 响应的 Cache-Control 为 no-cache，告诉浏览器不要缓存此响应。
        header('Cache-Control: no-cache');

        // 这行代码设置 HTTP 响应的 Connection 为 keep-alive，保持长连接，以便服务器可以持续发送事件到客户端。
        header('Connection: keep-alive');

        // 这行代码设置 HTTP 响应的自定义头部 X-Accel-Buffering 为 no，用于禁用某些代理或 Web 服务器（如 Nginx）的缓冲。
        // 这有助于确保服务器发送事件在传输过程中不会受到缓冲影响。
        header('X-Accel-Buffering: no');
    }

    public function __invoke(Request $request)
    {
        $question = str_ireplace('{[$add$]}', '+', $request->query('q', ''));
        if (empty($question)) {
            Res::end('问题不能为空');
            exit();
        }

        $user = auth()->user();
        /* @var User $user */
        if ($user->has_chat && now()->gt($user->chat_expired_at)) {
            Res::end('订阅已过期');
            exit();
        }
        if (!$user->has_chat && $user->chat_count <= 0) {
            Res::end('免费额度已用完');
            exit();
        }

        $userId = auth()->id();

        $title = mb_substr($question, 0, 20);

        $conversation = Conversation::query()->firstOrCreate(['user_id' => $userId, 'id' => $request->query('cid')], ['title' => $title]);

        $messages = $this->handleMessages($conversation, $question);

        Res::start($title, $conversation->id);

        // 此处需要填入 openai 的 api key
        $chat = new ChatGPT(['api_key' => config('gpt.gpt3_key'), 'conversation' => $conversation]);

        // 如果把下面三行注释掉，则不会启用敏感词检测
        // 特别注意，这里特意用乱码字符串文件名是为了防止他人下载敏感词文件，请你部署后也自己改一个别的乱码文件名
//        $dfa = new DFA([
//            'words_file' => './sensitive_words_sdfdsfvdfs5v56v5dfvdf.txt',
//        ]);
//        $chat->set_dfa($dfa);

        // 开始提问
        $chat->qa(['messages' => $messages]);

        return $this->success();
    }

    /**
     * @param Conversation $conversation
     * @param string $content
     * @return array[]
     */
    public function handleMessages(Conversation $conversation, string $content): array
    {
        $newMessage = ['role' => 'user', 'content' => $content];

        $messages = Message::query()->where('conversation_id', $conversation->id)->get(['role', 'content'])->toArray();
        if (!$messages) {
            // 不存在历史消息
            $presetMessage = ['role' => 'system', 'content' => config('gpt.preset_content')];
            $conversation->messages()->create($presetMessage);
            $messages = [$presetMessage];
        }

        $conversation->messages()->create($newMessage);
        $messages[] = $newMessage;

        $conversation->updated_at = now();
        $conversation->save();

        $user = auth()->user();

        /* User $user */
        $user->decrement('chat_count', 1);

        return $messages;
    }
}
