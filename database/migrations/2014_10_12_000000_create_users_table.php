<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('openid')->unique();
            $table->string('unionid')->unique();
            $table->tinyInteger('has_chat')->default(false)->comment('是否已订阅聊天服务');
            $table->timestamp('chat_started_at')->nullable()->comment('何时开始');
            $table->timestamp('chat_expired_at')->nullable()->comment('何时过期');
            $table->tinyInteger('chat_count')->default(20)->comment('免费次数');
            $table->tinyInteger('has_ip')->default(0)->comment('是否已订阅IP服务');
            $table->timestamp('ip_started_at')->nullable()->comment('何时开始');
            $table->timestamp('ip_expired_at')->nullable()->comment('何时过期');
            $table->string('education')->nullable()->comment('教育经历');
            $table->string('company')->nullable()->comment('公司名称');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
