<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;

class Index extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $paginator = Service::orderByDesc('id')->paginate();

        $data = $paginator->items();

        $meta = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];

        return $this->success($data, $meta);
    }
}
