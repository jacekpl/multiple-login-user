<?php

namespace App\Security\JWT\Guard;

use App\Enum\Roles;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Security\JWT\Manager\JWTManagerInterface;
use App\Security\JWT\Exception\InvalidTokenException;
use App\Security\JWT\Exception\UserNotFoundException;
use App\Security\JWT\Exception\ExpiredTokenException;

class JWTTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtManager;

    public function __construct(JWTManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * {@inheritdoc }
     */
    public function getCredentials(Request $request)
    {
        try {
            $authorizationHeader = $request->headers->get('Authorization');
            $headerParts = explode(' ', $authorizationHeader);

            if (false !== $headerParts && !isset($headerParts[1])) {
                throw new InvalidTokenException();
            }

            $token = $headerParts[1];

            if (!$this->jwtManager->isTokenValid($token)) {
                throw new InvalidTokenException();
            }
        } catch (\Exception $e) {
            throw new InvalidTokenException();
        }

        if ($this->jwtManager->isTokenExpired($token)) {
            throw new ExpiredTokenException();
        }

        return $token;
    }

    /**
     * {@inheritdoc }
     */
    public function getUser($token, UserProviderInterface $userProvider)
    {
        try {
            $username = $this->jwtManager->readClaim($token, 'username');
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw new UserNotFoundException($username);
        } catch (\OutOfBoundsException $e) {
            throw new InvalidTokenException();
        }

        return $user;
    }

    /**
     * {@inheritdoc }
     */
    public function supports(Request $request): bool
    {
        if (!$request->headers->has('Authorization')) {
            return false;
        }

        $authorizationHeader = $request->headers->get('Authorization');

        if (false === $headerParts = explode(' ', $authorizationHeader)) {
            return false;
        }

        if ('Bearer' !== $headerParts[0]) {
            return false;
        }

        if (!(2 === count($headerParts))) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc }
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) {
        return $this->unauthorizedResponse($exception->getMessageKey());
    }

    /**
     * {@inheritdoc }
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): Response {
        return $this->unauthorizedResponse('Bad credentials');
    }

    /**
     * {@inheritdoc }
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        return;
    }

    /**
     * {@inheritdoc }
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    private function unauthorizedResponse($msg)
    {
        return new JsonResponse(
            [
                'error' => $msg
            ],
            JsonResponse::HTTP_UNAUTHORIZED
        );
    }
}
