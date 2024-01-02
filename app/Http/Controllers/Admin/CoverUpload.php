<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CoverUpload extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        /* @var User $user */
        $user = auth()->user();

        $file = $request->file('file');

        $ext = $file->getClientOriginalExtension();

        $path = 'public/';

        $filename =  $user->id . '/cover'. date('YmdHis') . '.' . $ext;

        \Storage::disk('local')->put($path . $filename, file_get_contents($file->getRealPath()));

        $url = config('app.url') . \Storage::disk('local')->url($filename);

        return $this->success(compact('url'));
    }
}
