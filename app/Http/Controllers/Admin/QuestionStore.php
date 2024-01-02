<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionStore extends Controller
{
    public function __invoke(Request $request)
    {
        Question::create($request->all());

        return $this->success();
    }
}
