<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionUpdate extends Controller
{
    public function __invoke($question, Request $request)
    {
        $q = Question::findOrFail($question);

        $q->update($request->all());

        return $this->success();
    }
}
