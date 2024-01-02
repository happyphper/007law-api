<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSettingRequest;
use App\Models\Setting;

class SettingUpdate extends Controller
{
    public function __invoke(AdminSettingRequest $request): \Illuminate\Http\JsonResponse
    {
        $setting = Setting::first();
        $setting->content = $request->all();
        $setting->save();

        return $this->success();
    }
}
