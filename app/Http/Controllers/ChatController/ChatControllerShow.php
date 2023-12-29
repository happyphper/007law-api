<?php

namespace App\Http\Controllers\ChatController;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ChatControllerShow
{
    public function __invoke($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = auth()->id();

        $conversation = Conversation::where('user_id', $userId)->where('id', $id)->firstOrFail();

        return response()->json([
            'code' => 0,
            'data' => $conversation->messages,
        ]);
    }
}
