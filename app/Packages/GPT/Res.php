<?php

namespace App\Packages\GPT;

class Res
{
    const CODE_START = 1;
    const CODE_ALIVE = 2;
    const CODE_CLOSED = 3;

    /**
     * 写到 data buffer
     *
     * @param string|null $content
     * @param int $code
     * @param int $conversationId
     * @return void
     */
    public static function write(?string $content, int $code, int $conversationId): void
    {
        if ($content != null) {
            echo json_encode(['code' => $code, 'time' => date('Y-m-d H:i:s'), 'content' => $content, 'cid' => $conversationId], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        }

        flush();
    }

    /**
     * 开始
     *
     * @param string|null $content
     * @param int $conversationId
     * @return void
     */
    public static function start(?string $content, int $conversationId): void
    {
        static::write($content, self::CODE_START, $conversationId);
    }

    /**
     * 发送
     *
     * @param string|null $content
     * @param int $conversationId
     * @return void
     */
    public static function send(?string $content, int $conversationId): void
    {
        static::write($content, self::CODE_ALIVE, $conversationId);
    }

    /**
     * 结束
     *
     * @param string|null $content
     * @param int $conversationId
     * @return void
     */
    public static function end(?string $content, int $conversationId = 0): void
    {
        static::write($content, self::CODE_CLOSED, $conversationId);
    }
}
