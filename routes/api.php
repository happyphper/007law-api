<?php

use App\Http\Controllers\ChatController\ChatControllerDestroy;
use App\Http\Controllers\ChatController\ChatControllerIndex;
use App\Http\Controllers\ChatController\ChatControllerShow;
use App\Http\Controllers\ChatController\ChatControllerStore;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('auth/code', \App\Http\Controllers\Mini\Code::class);
Route::post('auth/register', \App\Http\Controllers\Mini\Register::class);
Route::get('services', \App\Http\Controllers\Service\Index::class);
Route::get('questions', \App\Http\Controllers\Question\QuestionIndex::class);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('conversations', ChatControllerIndex::class);
    Route::get('conversations/{id}', ChatControllerShow::class);
    Route::post('conversations', ChatControllerStore::class);
    Route::delete('conversations/{id}', ChatControllerDestroy::class);
    Route::post('user/update', \App\Http\Controllers\User\Update::class);
    Route::post('user/avatar', \App\Http\Controllers\User\Avatar::class);
});
