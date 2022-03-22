<?php

namespace App\Security\JWT\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserNotFoundException extends AuthenticationException
{
    /**
     * @var string
     */
    private $username;

    /**
     * @param string $username
     */
    public function __construct(string $username)
    {
        parent::__construct();
        $this->username = $username;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return sprintf('Unable to load an user "%s"', $this->username);
    }
}
