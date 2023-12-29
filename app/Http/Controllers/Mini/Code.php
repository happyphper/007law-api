<?php

namespace App\Http\Controllers\Mini;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserService;
use App\Services\MiniService;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class Code extends Controller
{
    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     */
    public function __invoke(Request $request)
    {
        $code = $request->get('code');

        $service = new MiniService();

        $data = $service->codeToSession($code);

        $meta = [];
        $user = User::where('openid', $data['openid'])->first();
        if ($user) {
            $token = $user->createToken($user->phone)->plainTextToken;
            $meta['user'] = $user;
            $meta['token'] = $token;
        }

        return $this->success($data, $meta);
    }
}
