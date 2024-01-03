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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('service_id');
            $table->tinyInteger('status')->default(1);
            $table->string('out_trade_no');
            $table->decimal('amount');
            $table->string('description');
            $table->timestamp('paid_at')->nullable();
            $table->decimal('paid_amount')->nullable();
            $table->text('response')->nullable();
            $table->string('prepay_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
