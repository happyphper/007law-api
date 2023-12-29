<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('login', \App\Http\Controllers\Admin\Login::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('orders', \App\Http\Controllers\Admin\OrderIndex::class);

    Route::get('services', \App\Http\Controllers\Admin\ServiceIndex::class);

    Route::get('service-types', \App\Http\Controllers\Admin\ServiceTypes::class);

    Route::put('services', \App\Http\Controllers\Admin\ServiceUpdate::class);

    Route::get('settings', \App\Http\Controllers\Admin\SettingIndex::class);

    Route::put('settings/{setting}', \App\Http\Controllers\Admin\SettingUpdate::class);

    Route::get('users', \App\Http\Controllers\Admin\UserIndex::class);
});
