<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceTypes extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $data = Service::types();

        return $this->success($data);
    }
}
