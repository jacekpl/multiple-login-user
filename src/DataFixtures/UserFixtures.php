<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Enum\Roles;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    private ObjectManager $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->createUser('James', 'james@gmail.com', '123456', [Roles::ROLE_USER]);

        $this->manager->flush();
    }

    private function createUser(string $name, string $email, string $plainPassword, array $roles): User
    {
        $user = new User($name, $email, $roles);
        $user->setPassword($this->encoder->encodePassword($user, $plainPassword));
        $this->manager->persist($user);
        return $user;
    }
}
