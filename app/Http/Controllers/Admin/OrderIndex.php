<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderIndex extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $paginator = Order::orderByDesc('id')->paginate();

        $data = $paginator->items();

        $meta = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];

        return $this->success($data, $meta);
    }
}
