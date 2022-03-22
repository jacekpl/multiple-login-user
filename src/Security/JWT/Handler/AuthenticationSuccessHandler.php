<?php

namespace App\Security\JWT\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Security\JWT\Manager\JWTManagerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $jwtManager;

    public function __construct(JWTManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $authentication
    ) {
        $jwt = $this->jwtManager->createToken($authentication->getUser());

        return new JsonResponse(['token' => $jwt]);
    }
}
