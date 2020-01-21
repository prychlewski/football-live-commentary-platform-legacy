<?php


namespace App\Service;


use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;

class UserService
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;


    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function createRegularUser(string $username, string $password)
    {
        return $this->createAndSaveUser($username, $password, ['ROLE_USER']);
    }

    public function createAdministrationUser(string $username, string $password)
    {
        return $this->createAndSaveUser($username, $password, ['ROLE_ADMIN']);
    }

    private function createAndSaveUser(string $username, string $password, array $roles )
    {
        // Unfortunate FOSRest user handling
        $user = new User();
        $user
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEnabled(true)
            ->setRoles($roles)
            ->setSuperAdmin(false);

        $this->userManager->updateUser($user, true);

        return $user;
    }
}
