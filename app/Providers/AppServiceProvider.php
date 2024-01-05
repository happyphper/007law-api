<?php

namespace App\Providers;

use App\Listeners\SqlQueryListener;
use App\Models\Token;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

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
        Sanctum::usePersonalAccessTokenModel(Token::class);

        // TODO SQL 日志可以推送到日志服务
        Event::listen(QueryExecuted::class, SqlQueryListener::class);
    }
}
