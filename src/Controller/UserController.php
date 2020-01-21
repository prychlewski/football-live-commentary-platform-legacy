<?php

namespace App\Controller;

use App\Model\Request\UserRequestModel;
use App\Model\Response\UserResponse;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        UserService $teamService,
        ValidatorInterface $validator
    ) {
        $this->userService = $teamService;
        $this->validator = $validator;
    }

    /**
     * @Rest\Post("/user/register", name="user_register")
     */
    public function register(Request $request)
    {
        $userRequestModel = new UserRequestModel(
            $request->request->get('username'),
            $request->request->get('password')
        );

        $validationErrors = $this->validator->validate($userRequestModel);
        $this->handleErrors($validationErrors);

        $username = $userRequestModel->getUsername();
        $password = $userRequestModel->getPassword();

        $this->userService->createRegularUser($username, $password);

        return $this->redirectToRoute(
            'api_auth_login',
            [
                'username' => $username,
                'password' => $password,
            ],
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @Rest\Post("/user/admin", name="user_add_admin")
     */
    public function addAdmin(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $userRequestModel = new UserRequestModel(
            $request->request->get('username'),
            $request->request->get('password')
        );

        $validationErrors = $this->validator->validate($userRequestModel);
        $this->handleErrors($validationErrors);

        $admin = $this->userService->createAdministrationUser(
            $userRequestModel->getUsername(),
            $userRequestModel->getPassword()
        );

        $response = new UserResponse(
            $admin->getId(),
            $admin->getUsername(),
            $admin->getRoles()
        );

        return $this->view($response);
    }

    /**
     * @Rest\Get("/me", name="user_me")
     */
    public function me()
    {
        $user = $this->getUser();

        return $this->view($user);
    }
}
