<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Request\Api\UpdateUserDetailsRequest;
use App\Service\UserService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    private $validator;
    private $userService;

    public function __construct(
        ValidatorInterface $validator,
        UserService $userService
    ) {
        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * Get user details
     * @SWG\Tag(name="Users")
     * @SWG\Response(
     *     response="200",
     *     description="User details",
     *     @SWG\Schema(
     *      @SWG\Property(property="id", type="string", example="ff5bf0a2-3f8c-11e9-9c70-dcbb132dabd0"),
     *      @SWG\Property(property="name", type="string", example="Helena"),
     *      @SWG\Property(property="email", type="string", example="helena@willis.com"),
     *     )
     * )
     * @Route("/api/users/details", name="api_userss_details", methods={"GET"}, defaults={"_format": "json"})
     * @Security("is_granted('ROLE_USER')")
     * @\Nelmio\ApiDocBundle\Annotation\Security(name="JWT")
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        return $this->json($this->getUser());
    }

    /**
     * Update user details
     * @SWG\Tag(name="Users")
     * @SWG\Post(
     *     @SWG\Parameter(name="body", in="body",
     *     @SWG\Schema(
     *          @SWG\Property(property="name", type="string", example="Helena"),
     *     )
     * ))
     * @SWG\Response(response="200", description="Updated")
     * @SWG\Response(response="400", description="Incorrect data")
     * @SWG\Response(response="422", description="Persistence error")
     * @ParamConverter("request", converter="fos_rest.request_body")
     * @param UpdateUserDetailsRequest $request
     * @Security("is_granted('ROLE_USER')")
     * @\Nelmio\ApiDocBundle\Annotation\Security(name="JWT")
     * @Route("/api/users/details", name="api_users_details_update", methods={"POST"}, defaults={"_format": "json"})
     * @return JsonResponse
     */
    public function updateDetails(UpdateUserDetailsRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($request);

        if ($errors->count()) {
            return $this->json($errors[0]->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->userService->updateUser($user, $request->getName(),);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return $this->json($user, Response::HTTP_OK);
    }
}
