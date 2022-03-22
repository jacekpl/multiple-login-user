<?php

namespace App\Security\JWT;

interface JWTUserInterface
{
    public function getUsername();
    public function getRoles();
}
