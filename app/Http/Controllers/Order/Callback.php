<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\PayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Callback extends Controller
{
    public function __invoke(Request $request): \Psr\Http\Message\ResponseInterface
    {
        \Log::debug("PayCallback", $request->all());

        $service = new PayService();

        $outTradeNo = $service->handleNotification();

//        $outTradeNo = '20240103153423206655';

        $data = $service->search($outTradeNo);

//        $s = <<<EOF
//{
//	"amount": {
//		"currency": "CNY",
//		"payer_currency": "CNY",
//		"payer_total": 1,
//		"total": 1
//	},
//	"appid": "wxdace645e0bc2cXXX",
//	"attach": "",
//	"bank_type": "OTHERS",
//	"mchid": "1900006XXX",
//	"out_trade_no": "44_2126281063_5504",
//	"payer": {
//		"openid": "o4GgauJP_mgWEWictzA15WT15XXX"
//	},
//	"promotion_detail": [],
//	"success_time": "2021-03-22T10:29:05+08:00",
//	"trade_state": "SUCCESS",
//	"trade_state_desc": "支付成功",
//	"trade_type": "JSAPI",
//	"transaction_id": "4200000891202103228088184743"
//}
//EOF;
        $data = json_decode($s, true);
        if ($data['trade_state'] !== 'SUCCESS') {
            return $service->callbackResponse();
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
                    $user->chat_expired_at = $user->chat_expired_at->addYear()->addDay();
                } else {
                    $user->has_chat = true;
                    $user->chat_started_at = now();
                    $user->chat_expired_at = now()->addYear()->addDay();
                }
            } else {
                if ($user->has_ip) {
                    $user->ip_expired_at = $user->ip_expired_at->addYear()->addDay();
                } else {
                    $user->has_ip = true;
                    $user->ip_started_at = now();
                    $user->ip_expired_at = now()->addYear()->addDay();
                }
            }
            $user->save();
        });

        return $service->callbackResponse();
    }
}
