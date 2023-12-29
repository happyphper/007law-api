<?php

namespace App\Http\Controllers\Mini;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRegisterRequest;
use App\Services\PayService;

class Notification extends Controller
{
    public function __invoke(ApiRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $service = new PayService();

        $service->handleNotification();
    }
}
