<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceUpload extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('file');

        $ext = $file->getClientOriginalExtension();

        $path = 'public/';

        $filename =  'services/'. date('YmdHis') . '.' . $ext;

        \Storage::disk('local')->put($path . $filename, file_get_contents($file->getRealPath()));

        $url = config('app.url') . \Storage::disk('local')->url($filename);

        return $this->success(compact('url'));
    }
}
