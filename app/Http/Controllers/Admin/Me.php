<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class Me extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        /* @var Admin $admin */
        $admin = auth()->user();

        return $this->success([
            'name' => $admin->phone,
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
        ]);
    }
}
