<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->remove($user);
    }

    public function updateUser(User $user, string $name): void {

        $user->changeName($name);
        $this->userRepository->save($user);
    }
}
