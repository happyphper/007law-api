<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSettingRequest;
use App\Models\Setting;

class SettingUpdate extends Controller
{
    public function __invoke(Setting $setting, AdminSettingRequest $request): \Illuminate\Http\JsonResponse
    {
        $setting->content = $request->input('content');
        $setting->save();

        return $this->success();
    }
}
