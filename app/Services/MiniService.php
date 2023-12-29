<?php

namespace App\Services;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\AccessToken;
use EasyWeChat\MiniApp\Application;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MiniService
{
    /**
     * @var Application EastWechat 小程序实例
     */
    public readonly Application $mini;

    /**
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct()
    {
        $this->mini = new Application(config('wechat'));

        // 设置缓存
        $this->mini->setCache(app('cache.store'));

        // 非正式服，每次自动刷新 accessToken
        if (!app()->isProduction()) {
            $this->forgetAccessToken();
        }
    }

    /**
     * @param string $code
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     */
    public function codeToSession(string $code):array
    {
        return $this->mini->getUtils()->codeToSession($code);
    }

    /**
     * @param string $code
     * @return array
     * @throws TransportExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function getPhoneByCode(string $code): array
    {
        $uri = 'wxa/business/getuserphonenumber';

        $params = ['code' => $code];

        $data = $this->mini->getClient()->postJson($uri, $params);

        if ($data->getStatusCode() != 200) {
            $this->error($data->getStatusCode(), $uri, $data->getContent(false), $params);
            throw new Exception('Wechat Http Response ' . $data->getStatusCode());
        }

        $this->debug($data->getStatusCode(), $uri, $data->getContent(false), $params);

        if ($data['errcode'] === 0) {
            return [
                'phone' => $data['phone_info']['purePhoneNumber'],
                'raw' => $data,
            ];
        }

        switch ($data['errcode']) {
            case 40001:
                $this->forgetAccessToken();
                throw new Exception($data['errmsg']);
            case 40029:
                throw new Exception($data['errmsg']);
            default:
                throw new Exception($data['errmsg']);
        }
    }

    /**
     * 清除已缓存的 AccessToken
     *
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function forgetAccessToken(): void
    {
        $token = new AccessToken(config('wechat.app_id'), config('wechat.secret'));

        app('cache.store')->delete($token->getKey());
    }

    /**
     * debug 日志
     *
     * @param int $statusCode
     * @param string $uri
     * @param string $response
     * @param array $params
     * @return void
     */
    private function debug(int $statusCode, string $uri, string $response, array $params = []): void
    {
        Log::debug('成功', [
            'status_code' => $statusCode,
            'response' => $response,
            'uri' => $uri,
            'params' => $params,
        ]);
    }

    /**
     * 错误日志
     *
     * @param int $statusCode
     * @param string $uri
     * @param string $response
     * @param array $params
     * @return void
     */
    private function error(int $statusCode, string $uri, string $response, array $params = []): void
    {
        Log::error('失败', [
            'status_code' => $statusCode,
            'response' => $response,
            'uri' => $uri,
            'params' => $params,
        ]);
    }

    /**
     * 获取用户的 url link，并缓存 30 秒
     *
     * @param string $code
     * @return string
     */
    public function getUserUrlLink(string $code): string
    {
        return Cache::remember('WX_URL_URLLINK_CODE_' . md5($code), 30, fn() => $this->getUrlLink('/pages/notification/index'));
    }

    /**
     * 获取小程序跳转链接
     *
     * @throws RedirectionExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    protected function getUrlLink(string $path, string $query = null): string
    {
        $uri = 'wxa/generate_urllink';
        $params = [
            //开发阶段使用首页
            "path" => $path,
            "query" => $query,
            "is_expire" => config('wechat.urllink.is_expire'),
            "expire_type" => config('wechat.urllink.expire_type'),
            "expire_interval" => config('wechat.urllink.expire_interval'),
            "env_version" => config('wechat.env_version'),
        ];


        $data = $this->mini->getClient()->postJson($uri, $params);

        if ($data->getStatusCode() != 200) {
            $this->error($data->getStatusCode(), $uri, $data->getContent(false), $params);
            throw new Exception('Wechat Http Response ' . $data->getStatusCode());
        }

        switch ($data['errcode']) {
            case 40001:
                $this->forgetAccessToken();
                throw new Exception($data['errmsg']);
            case 40029:
                throw new Exception();
        }

        return $data['url_link'];
    }
}
