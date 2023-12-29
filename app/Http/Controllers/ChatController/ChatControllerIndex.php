<?php

namespace App\Http\Controllers\ChatController;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ChatControllerIndex
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = auth()->id();

        $conversations = Conversation::where('user_id', $userId)->orderByDesc('updated_at')->get();

        return response()->json([
            'code' => 0,
            'data' => $conversations,
        ]);
    }
}
