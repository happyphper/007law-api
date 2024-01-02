<?php

namespace App\Providers;

use App\Listeners\SqlQueryListener;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // TODO SQL 日志可以推送到日志服务
        Event::listen(QueryExecuted::class, SqlQueryListener::class);
    }
}
