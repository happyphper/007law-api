<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class QuestionIndex extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $questions = Setting::where('title', Setting::TITLE_QUESTION)->take(4)->first();

        return $this->success($questions->content);
    }
}
