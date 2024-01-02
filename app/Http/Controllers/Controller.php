<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const CODE_OK = 0;

    const CODE_ERROR = -1;

    public function error(string $msg = 'error'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => self::CODE_ERROR,
            'msg' => $msg,
        ]);
    }

    public function success(array $data = [], array $meta = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => self::CODE_OK,
            'msg' => 'ok',
            'data' => $data,
            'meta' => $meta,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
