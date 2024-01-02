<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionIndex extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $questions = Question::orderByDesc('sort')->pluck('title');

        return $this->success($questions->toArray());
    }
}
