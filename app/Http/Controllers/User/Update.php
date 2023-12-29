<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class Update extends Controller
{
    public function __invoke(UserUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        /* @var User $user */
        $user = auth()->user();

        $user->fill([$request->input('field') => $request->input('value')]);
        $user->save();

        return $this->success(compact('user'));
    }
}
