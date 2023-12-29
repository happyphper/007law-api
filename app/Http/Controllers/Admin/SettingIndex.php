<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingIndex extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = Setting::get();

        return $this->success($data);
    }
}
