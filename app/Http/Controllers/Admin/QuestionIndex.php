<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionIndex extends Controller
{
    public function __invoke(Request $request)
    {
        $paginator = Question::orderByDesc('sort')->paginate();

        $data = $paginator->items();

        $meta = [
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];

        return $this->success($data, $meta);
    }
}
