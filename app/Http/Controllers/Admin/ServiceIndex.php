<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceIndex extends Controller
{
    public function __invoke()
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
