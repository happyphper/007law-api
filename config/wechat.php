<?php

return [
    'app_id' => env('WECHAT_APP_ID', ''),

    'secret' => env('WECHAT_APP_SECRET', ''),

    'token' => '007law',

    'aes_key' => '',

    'pay' => [
        'mch_id' => 1538637291,
        // v3 API 秘钥
        'secret_key' => 'f1ee0dd096a91aJZ10e6c2f359OdL274',

        // 商户证书
        'private_key' => __DIR__ . '/certs/apiclient_key.pem',
        'certificate' => __DIR__ . '/certs/apiclient_cert.pem',

        // v2 API 秘钥
        'v2_secret_key' => '26db3e15cfedb44abfbb5fe94fxxxxx',

        // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
        // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
        'platform_certs' => [
            // 请使用绝对路径
            // '/path/to/wechatpay/cert.pem',
        ],

        /**
         * 接口请求相关配置，超时时间等，具体可用参数请参考：
         * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
         */
        'http' => [
            'throw' => true, // 状态码非 200、300 时是否抛出异常，默认为开启
            'timeout' => 5.0,
            // 'base_uri' => 'https://api.mch.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
        ],
    ],
];
