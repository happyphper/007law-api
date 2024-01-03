<?php

namespace App\Services;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Pay\Application;
use EasyWeChat\Pay\Message;
use Exception;
use Nyholm\Psr7\Response;

class PayService
{
    /**
     * @var Application EastWechat 微信支付实例
     */
    public readonly Application $app;
    /**
     * @var string
     */
    public string $appId;

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

        $this->appId = config('wechat.app_id');

        $this->mchid = $setting['mch_id'];

        $this->app = new Application($setting);
    }

    /**
     * 预支付
     *
     * @param array $data
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function prepay(array $data)
    {
        $url = "/v3/pay/transactions/jsapi";

        $response = $this->app->getClient()->postJson($url, $data);

        return $response->toArray(false)['prepay_id'];
    }

    /**
     * 预支付参数
     *
     * @param $prepayId
     * @return mixed[]
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function prepayParameters($prepayId)
    {
        return $this->app->getUtils()->buildMiniAppConfig($prepayId, $this->appId);
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

        return $outTradeNo;
    }

    public function callbackResponse()
    {
        return $this->app->getServer()->serve();
    }

    public function search(string $outTradeNo)
    {
        $response = $this->app->getClient()->get("v3/pay/transactions/out-trade-no/{$outTradeNo}", [
            'query' => [
                'mchid' => $this->app->getMerchant()->getMerchantId()
            ]
        ]);

        return $response->toArray();
    }

    public function transaction(string $outTradeNo)
    {
        $url = sprintf("/v3/pay/transactions/out-trade-no/%s?mchid=%s", $outTradeNo, $this->mchid);

        $api = $this->app->getClient();

        $response = $api->get($url);

        try {
            $this->app->getValidator()->validate($response->toPsrResponse());
            // 验证通过
            return $response;
        } catch (Exception $e) {
            // 验证失败
            return "";
        }
    }
}
