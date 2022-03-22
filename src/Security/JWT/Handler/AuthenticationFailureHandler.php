<?php

namespace App\Security\JWT\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {
        return new JsonResponse(
            ['error' => 'Bad credentials'],
            JsonResponse::HTTP_UNAUTHORIZED
        );
    }
}
