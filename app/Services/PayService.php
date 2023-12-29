<?php

namespace App\Services;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Pay\Application;
use EasyWeChat\Pay\Message;
use Exception;

class PayService
{
    /**
     * @var Application EastWechat 微信支付实例
     */
    public readonly Application $app;

    /**
     * @var string 商户ID
     */
    public string $mchid;

    /**
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct()
    {
        $setting = config('wechat.pay');

        $this->mchid = $setting['mch_id'];

        $this->app = new Application($setting);
    }

    public function prepay(array $data)
    {
        $url = "/v3/pay/transactions/jsapi";

        $api = $this->app->getClient();

        $response = $api->postJson($url, $data);

        try{
            $this->app->getValidator()->validate($response->toPsrResponse());
            // 验证通过
            return $response;
        } catch(Exception $e){
            // 验证失败
            return "";
        }
    }

    public function handleNotification()
    {
        $server = $this->app->getServer();

        $outTradeNo = '';

        $payerOpenId = '';

        $server->handlePaid(function (Message $message, \Closure $next) use ($outTradeNo, $payerOpenId) {
            $outTradeNo = $message->out_trade_no;

            $payerOpenId = $message->payer['openid'];

            return $next($message);
        });

        $this->transaction($outTradeNo);

        // TODO 校验支付情况
        // 支付成功


        return $server->serve();
    }

    public function transaction(string $outTradeNo)
    {
        $url = sprintf("/v3/pay/transactions/out-trade-no/%s?mchid=%s", $outTradeNo, $this->mchid);

        $api = $this->app->getClient();

        $response = $api->get($url);

        try{
            $this->app->getValidator()->validate($response->toPsrResponse());
            // 验证通过
            return $response;
        } catch(Exception $e){
            // 验证失败
            return "";
        }
    }
}
