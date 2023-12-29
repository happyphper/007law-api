<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Avatar extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        /* @var User $user */
        $user = auth()->user();

        $file = $request->file('avatar');

        $ext = $file->getClientOriginalExtension();

        $filename = 'public/' . $user->id . '/avatar'. date('YmdHis') . '.' . $ext;

        \Storage::disk('local')->put($filename, file_get_contents($file->getRealPath()));

        $url = config('app.url') . \Storage::disk('local')->url($filename);

        $user->avatar = $filename;
        $user->save();

        return $this->success(compact('url'));
    }
}
