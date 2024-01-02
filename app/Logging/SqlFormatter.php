<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

/**
 * SQL日志
 */
class SqlFormatter
{
    /**
     * 自定义给定的日志记录器实例。
     */
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                " %datetime% [%context.invocation_endpoint%-%context.invocation_id%] %message%\n",
                'H:i',
            ));
        }
    }
}
