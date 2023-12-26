<?php

namespace App\Http\Controllers\ChatController;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ChatControllerIndex
{

    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = 1;

        $conversations = Conversation::where('user_id', $userId)->get();

        return response()->json([
            'code' => 0,
            'data' => $conversations,
        ]);
    }
}
