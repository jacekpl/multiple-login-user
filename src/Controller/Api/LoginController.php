<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class LoginController extends AbstractController
{
    /**
     * Login
     * @SWG\Tag(name="Login")
     *
     * @SWG\Post(
     *     @SWG\Parameter(name="body", in="body",
     *     @SWG\Schema(
     *          @SWG\Property(property="email", type="string", maxLength=180),
     *          @SWG\Property(property="password", type="string", maxLength=255),
     *     )
     * ))
     * @SWG\Response(response="400", description="Bad request")
     * @SWG\Response(response="401", description="Bad credentials or token expired")
     * @SWG\Response( response="200", description="JWT token",
     *     @SWG\Schema(
     *      @SWG\Property(property="token", example="123.456.789")
     *     )
     * )
     * @Route("/api/login", name="api_login", methods={"POST"}, defaults={"_format": "json"})
     */
    public function login()
    {
    }
}
