<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class
ContractUpdate extends Controller
{
    public function __invoke($contract, Request $request): \Illuminate\Http\JsonResponse
    {
        Contract::where('id', $contract)->update($request->all());

        return $this->success();
    }
}
