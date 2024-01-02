<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractView extends Controller
{
    public function __invoke($contract, Request $request): \Illuminate\Http\JsonResponse
    {
        Contract::where('id', $contract)->increment('view');

        return $this->success();
    }
}
