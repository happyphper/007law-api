<?php

namespace App\Http\Controllers\Mini;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRegisterRequest;
use App\Models\User;
use App\Services\MiniService;

class Register extends Controller
{
    public function __invoke(ApiRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        // 根据 openid 查询客户是否存在
        if (!$user = User::where('openid', $request->input('openid'))->first()) {
            $user = $this->fetchApi($request);
        }

        // TODO 清除之前的 Token

        $token = $user->createToken($user->phone)->plainTextToken;

        return $this->success(compact('token'), compact('user'));
    }

    public function fetchApi(ApiRegisterRequest $request): User
    {
        $service = new MiniService();

        $data = $service->getPhoneByCode($request->input('code'));

        $phone = $data['phone'];

        return User::create([
            'name' => trim($request->input('name')),
            'avatar' => $request->input('avatar'),
            'phone' => $phone,
            'openid' => $request->input('openid'),
            'unionid' => $request->input('unionid'),
        ]);
    }
}
