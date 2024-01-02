<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractIndex extends Controller
{
    public function __invoke(Request $request)
    {
        $paginator = Contract::orderByDesc('id')->paginate(100);

        $data = $paginator->items();

        $meta = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];

        return $this->success($data, $meta);
    }
}
