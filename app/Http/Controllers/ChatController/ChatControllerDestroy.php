<?php

namespace App\Http\Controllers\ChatController;

use App\Models\Conversation;
use DB;
use Illuminate\Http\Request;

class ChatControllerDestroy
{
    /**
     * @throws \Throwable
     */
    public function __invoke($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = 1;

        DB::transaction(function () use ($userId, $id) {
            $conversation = Conversation::where('user_id', $userId)->where('id', $id)->lockForUpdate()->firstOrFail();

            $conversation->messages()->delete();

            $conversation->delete();
        });

        return response()->json([
            'code' => 0,
            'data' => null,
        ]);
    }
}
