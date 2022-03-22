<?php

namespace App\Security\JWT\Manager;

use DateTime;
use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use App\Security\JWT\JWTUserInterface;

class LcobucciJWTManager implements JWTManagerInterface
{
    /**
     * @var int
     */
    private $tokenExpiredTime;

    /**
     * @var Sha256
     */
    private $signer;

    /**
     * @var Key
     */
    private $publicKey;

    /**
     * @var Key
     */
    private $privateKey;

    public function __construct(
        string $privateKeyPath,
        string $publicKeyPath,
        string $passPhrase,
        int $tokenExpiredTime
    ) {
        $this->tokenExpiredTime = $tokenExpiredTime;
        $this->publicKey = new Key('file://'.$publicKeyPath, $passPhrase);
        $this->privateKey = new Key('file://'.$privateKeyPath, $passPhrase);
        $this->signer = new Sha256();
    }

    /**
     * {@inheritdoc }
     */
    public function createToken(
        JWTUserInterface $user,
        ?\DateTime $expirationDate = null,
        ?\DateTime $sessionExpiraionDate = null
    ): string {

        $sessionDate = $sessionExpiraionDate ?: (new \DateTime('now'))->add(new \DateInterval('P7D'));
        $tokenExpiredTime = $expirationDate ? $expirationDate->getTimestamp() : (time() + $this->tokenExpiredTime);
        $expiredDate = new DateTimeImmutable();

        $token = (new Builder())
            ->withClaim('username', $user->getUsername())
            ->withClaim('roles', $user->getRoles())
            ->withClaim('exps', $sessionDate->getTimestamp())
            ->withClaim('lang', $user->getLanguage())
            ->issuedAt(new DateTimeImmutable())
            ->expiresAt($expiredDate->setTimestamp($tokenExpiredTime))
            ->getToken($this->signer, $this->privateKey);

        return $token->toString();
    }

    /**
     * {@inheritdoc }
     */
    public function isTokenExpired(string $jwtToken): bool
    {
        /* @var Token $token */
        $token = (new Parser())->parse($jwtToken);

        return $token->isExpired(new DateTimeImmutable());
    }

    /**
     * {@inheritdoc }
     */
    public function isTokenValid(string $jwtToken): bool
    {
        /* @var Token $token */
        $token = (new Parser())->parse($jwtToken);

        return $token->verify($this->signer, $this->publicKey);
    }

    /**
     * {@inheritdoc }
     * @throws \OutOfBoundsException
     */
    public function readClaim(string $jwtToken, string $claim): string
    {
        $token = (new Parser())->parse($jwtToken);

        return $token->getClaim($claim);
    }

    public function getRoles(string $jwtToken): array
    {
        /* @var Token $token */
        $token = (new Parser())->parse($jwtToken);

        $roles = [];
        $claims = $token->getClaims();

        if (isset($claims['roles']) && is_array($claims['roles']->getValue())) {
            foreach ($claims['roles']->getValue() as $role) {
                $roles[] = $role;
            }
        }

        return $roles;
    }
}
