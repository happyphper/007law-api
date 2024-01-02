<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminServiceUpdateRequest;
use App\Models\Service;

class ServiceUpdate extends Controller
{
    public function __invoke($service, AdminServiceUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $service = Service::findOrFail($service);

        $service->fill($request->all());
        $service->save();

        return $this->success();
    }
}
