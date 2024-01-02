<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractStore extends Controller
{
    public function __invoke(Request $request)
    {
        Contract::create($request->all());

        return $this->success();
    }
}
