<?php

namespace App\Http\Controllers\ChatController;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\GPTService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class ChatControllerStore
{
    /**
     * @throws GuzzleException
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $content = trim($request->get('content'));
        if (!$content) {
            return response()->json(['code' => -1, 'msg' => '消息不能为空']);
        }

        $userId = 1;

        $title = mb_substr($content, 0, 20);

        $conversation = Conversation::query()->firstOrCreate(['user_id' => $userId, 'id' => $request->get('id')], ['title' => $title]);

        $messages = $this->handleMessages($conversation, $content);

        $content = $this->handleApi($conversation, $messages);

        $data = $conversation->messages()->orderByDesc('id')->first();

        return response()->json(['code' => 0, 'msg' => '成功', 'data' => $data]);
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
            $service = new GPTService();
            $presetMessage = ['role' => 'system', 'content' => $service->presetContent];
            $conversation->messages()->create($presetMessage);
            $messages = [$presetMessage];
        }

        $conversation->messages()->create($newMessage);
        $messages[] = $newMessage;
        return $messages;
    }

    /**
     * @throws GuzzleException
     */
    public function handleApi(Conversation $conversation, array $messages)
    {
        $service = new GPTService();

        $res = $service->request($messages);
        if (empty($res['choices'][0]['message']['content'])) {
            $conversation->messages()->where('status', Message::STATUS_WAIT)->delete();
            return '';
        }

        $conversation->messages()->where('status', Message::STATUS_WAIT)->update(['status' => Message::STATUS_SENT]);

        $content = $res['choices'][0]['message']['content'];

        $conversation->messages()->create(['role' => 'assistant', 'content' => $content, 'status' => Message::STATUS_SENT]);

        return $content;
    }
}
