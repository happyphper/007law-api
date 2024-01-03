<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\PayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Search extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = auth()->id();

        $order = Order::where('user_id', $userId)
            ->where('service_id', $request->get('id'))
            ->orderByDesc('id')
            ->first();

        if ($order->status === Order::STATUS_PAID) {
            $user = User::find($userId);

            return $this->success($user->toArray());
        }

        // 查询
        $service = new PayService();
        $outTradeNo = $order->out_trade_no;
        $data = $service->search($order->out_trade_no);

        if ($data['trade_state'] !== 'SUCCESS') {
            $user = User::find($userId);
            return $this->success($user->toArray());
        }

        \DB::transaction(function () use ($data, $outTradeNo) {
            $order = Order::where('out_trade_no', $outTradeNo)->lockForUpdate()->first();
            if ($order->status !== Order::STATUS_WAIT) {
                return;
            }

            $order->paid_at = Carbon::parse($data['success_time']);
            $order->paid_amount = intval($data['amount']) / 100;
            $order->status = Order::STATUS_PAID;
            $order->response = json_encode($data, JSON_UNESCAPED_UNICODE);
            $order->save();

            $user = User::where('id', $order->user_id)->first();
            if ($order->service_id === Service::TYPE_CHAT) {
                if ($user->has_chat) {
                    $user->chat_expired_at = Carbon::parse($user->chat_expired_at)->addYear();
                } else {
                    $user->has_chat = true;
                    $user->chat_started_at = now();
                    $user->chat_expired_at = now()->addYear()->addDay();
                }
            } else {
                if ($user->has_ip) {
                    $user->ip_expired_at = Carbon::parse($user->ip_expired_at)->addYear();
                } else {
                    $user->has_ip = true;
                    $user->ip_started_at = now();
                    $user->ip_expired_at = now()->addYear()->addDay();
                }
            }
            $user->save();
        });

        $user = User::find($userId);
        return $this->success($user->toArray());
    }
}
