<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserIndex extends Controller
{
    public function __invoke(Request $request)
    {
        $paginator = User::orderByDesc('id')
            ->when($request->query('phone'), fn($q) => $q->where('phone', 'like', sprintf('%%%s%%', $request->query('phone'))))
            ->when($request->query('name'), fn($q) => $q->where('name', 'like', sprintf('%%%s%%', $request->query('name'))))
            ->paginate();

        $data = $paginator->items();

        $meta = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];

        return $this->success($data, $meta);
    }
}
