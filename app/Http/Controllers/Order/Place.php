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
        $type = $request->query('type');

        $service = Service::where('type', $type)->firstOrFail();

        /* @var User $user */
        $user = auth()->user();


        $order = Order::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'status' => Order::STATUS_WAIT,
            'out_trade_no' => date('YmdHis') . mt_rand(100000, 999999),
            'amount' => $service->price * 100,
            'payer' => $user->openid,
            'description' => $service->title,
        ]);

        $pay = [
            'appid' => '',
            'mchid' => '',
            'description' => '',
            'out_trade_no' => '',
            'notify_url' => '',
            'amount' => [
                'total' => $order->amount,
            ],
            'payer' => [
                'openid' => '',
            ],
        ];

        $mini = new PayService();

        $prepayId = $mini->prepay($pay);

        return $this->success(['prepay_id' => $prepayId]);
    }
}
