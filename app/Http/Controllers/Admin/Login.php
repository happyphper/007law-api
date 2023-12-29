<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;

class Login extends Controller
{
    public function __invoke(AdminLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $admin = Admin::where('phone', $request->input('phone'))->first();

        if (!\Hash::check($request->input('password'), $admin->password)) {
            return $this->error('账号或密码错误');
        }

        $token = $admin->createToken($admin->phone)->plainTextToken;

        return $this->success(['token' => $token]);
    }
}
