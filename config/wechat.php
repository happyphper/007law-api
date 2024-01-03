<?php

return [
    'app_id' => env('WECHAT_APP_ID', ''),

    'secret' => env('WECHAT_APP_SECRET', ''),

    'token' => '007law',

    'aes_key' => '',

    'pay' => [
        'mch_id' => env('WECHAT_PAY_MCH_ID', ''),
        // v3 API 秘钥
        'secret_key' => env('WECHAT_PAY_SECRET_V3', ''),

        // 商户证书
        'private_key' => storage_path('certs/apiclient_key.pem'),

        'certificate' => storage_path('certs/apiclient_cert.pem'),

        // v2 API 秘钥
        'v2_secret_key' => '',

        // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
        // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
        'platform_certs' => [
            // 请使用绝对路径
            storage_path('certs/wechatpay_145CB30C8C38D775CFE8A82FE1F9BD23BDCAEF76.pem')
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


