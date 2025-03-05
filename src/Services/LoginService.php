<?php

namespace App\Services;

use App\Entity\User;
use App\Repositories\UserRepository;
use Exception;

class LoginService
{

    /**
     * @throws Exception
     */
    public function authenticate($email, $password): void
    {
        $userRepository = new UserRepository();

        $user = $userRepository->getOne([
            'email' => $email,
            'password' => HashService::hashPassword($password)
        ]);

        if ($user == null) {
            throw new Exception("User not found or wrong password");
        }

        $userEntity = new User($user->id, $user->password, $user->email, $user->level);
        $this->setSession($userEntity);
    }

    public static function setSession(User $user): void {
        $_SESSION['user'] = $user;
    }

    public static function getSession() : ?User {
        return $_SESSION['user'] ?? null;
    }

    public static function logout(): void {
        unset($_SESSION['user']);
    }
}