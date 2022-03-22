<?php

namespace App\Security\JWT\Manager;

use App\Security\JWT\JWTUserInterface;

interface JWTManagerInterface
{
    /**
     * Create JWT Token
     * @param AppUserInterface $user
     * @param \DateTime|null $expirationDate
     * @param \DateTime|null $sessionExpiraionDate
     * @return string
     */
    public function createToken(
        JWTUserInterface $user, 
        ?\DateTime $expirationDate = null,
        ?\DateTime $sessionExpiraionDate = null
    ): string;
    
    /**
     * Check if JWT Token signature is valid
     * @param string $jwtToken
     * @return bool
     */
    public function isTokenValid(string $jwtToken): bool;

    /**
     * Check if JWT Token is expired
     * @param string $jwtToken
     * @return bool
     */
    public function isTokenExpired(string $jwtToken): bool;

    /**
     * Read claim from JWT Token payload
     * @param string $jwtToken
     * @param string $claim claim name
     * @return string
     */
    public function readClaim(string $jwtToken, string $claim): string;

    public function getRoles(string $jwtToken): array;
}
