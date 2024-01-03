<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\PayService;
use Illuminate\Http\Request;

class Place extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $type = $request->get('type');

        $service = Service::where('type', $type)->firstOrFail();

        /* @var User $user */
        $user = auth()->user();

        // 是否存在已提交的订单号
        $order = Order::where('service_id', $service->id)
            ->where('user_id', $user->id)
            ->where('status', Order::STATUS_WAIT)
            ->first();
        if (!$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'service_id' => $service->id,
                'status' => Order::STATUS_WAIT,
                'out_trade_no' => date('YmdHis') . mt_rand(100000, 999999),
                'amount' => $service->price,
                'payer' => $user->openid,
                'description' => $service->title,
            ]);
        }

        $mini = new PayService();

        // 是否存在 prepay_id
        $prepayId = $order->prepay_id;
        if (!$prepayId) {
            $pay = [
                'appid' => config('wechat.app_id'),
                'mchid' => config('wechat.pay.mch_id'),
                'description' => $order->description,
                'out_trade_no' => $order->out_trade_no,
                'notify_url' => 'https://2bb6-117-129-2-196.ngrok-free.app/api/',
                'amount' => [
                    'total' => intval($order->amount * 100),
                ],
                'payer' => [
                    'openid' => $user->openid,
                ],
            ];
            $prepayId = $mini->prepay($pay);
            $order->prepay_id = $prepayId;
            $order->save();
        }

        $data = $mini->prepayParameters($prepayId);

        return $this->success($data);
    }
}
