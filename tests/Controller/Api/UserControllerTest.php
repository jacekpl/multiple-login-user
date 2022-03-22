<?php

namespace App\Tests\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @test
     * @group e2e
     */
    public function testGetDetails(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('james@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/api/users/details');
        $this->assertResponseIsSuccessful();

        //second request is not authorized
        $client->request('GET', '/api/users/details');
        $this->assertResponseIsSuccessful();
    }
}
