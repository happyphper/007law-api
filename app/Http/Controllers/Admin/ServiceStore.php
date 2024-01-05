<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminServiceStoreRequest;
use App\Models\Service;

class ServiceStore extends Controller
{
    public function __invoke(AdminServiceStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->get('type') === Service::TYPE_CHAT) {
            return $this->error('已有该类型的服务，请选择其他类型');
        }

        Service::create($request->all());

        return $this->success();
    }
}
