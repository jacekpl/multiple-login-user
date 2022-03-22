<?php

namespace App\Security\JWT\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ExpiredTokenException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Expired JWT Token';
    }
}
