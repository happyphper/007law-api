<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class SqlQueryListener
{
    /**
     * AppointmentControllerStore the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $sql = $event->sql;
        $bindings = $event->bindings;
        $time = $event->time;
        // 将 SQL 语句和参数合并成完整的查询语句
        $query = $sql;

        if (!empty($bindings)) {
            foreach ($bindings as $k => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$k] = $binding->format('Y-m-d H:i:s');
                }
            }

            $query = vsprintf(str_replace('?', '\'%s\'', $sql), $bindings);
        }

        $line = vsprintf("%s(ms) [<%s>%s] %s", [str_pad($time, 5, '0'), request()->method(), request()->path(), $query]);
        if (request()->path() === "/") {
            $time = is_int($time) ? $time . '.0' : $time;
            $line = sprintf("%s(ms) %s", str_pad($time, 5, '0'), $query);
        }

        // 将查询语句写入日志文件
        Log::channel('sql')->debug($line);
    }
}
