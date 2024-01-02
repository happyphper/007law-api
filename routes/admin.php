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
    Route::get('me', \App\Http\Controllers\Admin\Me::class);

    Route::get('orders', \App\Http\Controllers\Admin\OrderIndex::class);

    Route::get('services', \App\Http\Controllers\Admin\ServiceIndex::class);

    Route::get('service-types', \App\Http\Controllers\Admin\ServiceTypes::class);

    Route::put('services/{service}', \App\Http\Controllers\Admin\ServiceUpdate::class);

    Route::get('settings', \App\Http\Controllers\Admin\SettingIndex::class);

    Route::put('settings', \App\Http\Controllers\Admin\SettingUpdate::class);

    Route::get('users', \App\Http\Controllers\Admin\UserIndex::class);

    Route::post('upload', \App\Http\Controllers\Admin\CoverUpload::class);

    Route::get('contracts', \App\Http\Controllers\Admin\ContractIndex::class);
    Route::post('contracts/upload', \App\Http\Controllers\Admin\ContractUpload::class);
    Route::post('contracts', \App\Http\Controllers\Admin\ContractStore::class);
    Route::put('contracts/{contract}', \App\Http\Controllers\Admin\ContractUpdate::class);

    Route::get('questions', \App\Http\Controllers\Admin\QuestionIndex::class);
    Route::post('questions', \App\Http\Controllers\Admin\QuestionStore::class);
    Route::put('questions/{question}', \App\Http\Controllers\Admin\QuestionUpdate::class);
    Route::delete('questions/{question}', \App\Http\Controllers\Admin\QuestionDestroy::class);

});
