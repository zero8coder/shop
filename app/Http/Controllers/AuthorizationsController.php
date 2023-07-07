<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationsController extends AccessTokenController
{
    use ApiResponse;
    public function store(ServerRequestInterface $request)
    {
        return $this->success(json_decode($this->issueToken($request)->getContent(), true), '请求成功', \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    public function update(ServerRequestInterface $request): Response
    {
        return $this->issueToken($request);
    }

    /**
     * @throws AuthenticationException
     */
    public function destroy()
    {
        if (auth('api')->check()) {
            auth('api')->user()->token()->revoke();
            return response(null, 204);
        } else {
            throw new AuthenticationException('The token is invalid.');
        }

    }
}
