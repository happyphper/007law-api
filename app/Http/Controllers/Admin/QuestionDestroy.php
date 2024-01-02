<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionDestroy extends Controller
{
    public function __invoke($question, Request $request)
    {
       Question::where('id', $question)->delete();

        return $this->success();
    }
}
