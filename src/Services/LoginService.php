<?php

namespace App\Services;

use App\Entity\User;
use App\Repositories\UserRepository;
use App\Utils\LocationUtils;
use DateTime;
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

        $userEntity = new User(
            $user->id,
            $user->name,
            $user->lastname,
            $user->email,
            $user->password,
            $user->phone,
            $user->phone_validation,
            $user->membership_due_date,
            $user->level,
            $user->phone_code
        );
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

    // always return false to break chaining
    public static function verifyPhoneConfirmation($urlViews): bool
    {

        $user = self::getSession();

        if ($user->getLevel() === User::$ADMIN_USER_LEVEL) {
            return true;
        }

        if (
            str_contains(implode("/",$urlViews), "phone/validation") ||
            str_contains(implode("/",$urlViews), "phone/code")
        ) {
            return false;
        }

        if ($user->getPhoneValidation() == 1) {
            return true;
        }

        LocationUtils::redirectInternal("panel/phone/validation");
    }

    // always return false to break chaining

    /**
     * @throws \DateMalformedStringException
     */
    public static function verifyMembershipDueDate($urlViews): bool {
        $user = self::getSession();

        if ($user->getLevel() === User::$ADMIN_USER_LEVEL) {
            return true;
        }

        if ($user->getMembershipDueDate() != null) {
            $dueDate = new DateTime($user->getMembershipDueDate());
            $now = new DateTime();

            if ($dueDate->getTimestamp() > $now->getTimestamp() ) {
                return true;
            }
        }

        if (str_contains(implode("/",$urlViews), "membership/pay")) {
            return false;
        }

        LocationUtils::redirectInternal("panel/membership/pay");
    }

    public static function verifyMany(array $callbacks): void
    {
        foreach ($callbacks as $callback) {
            $result = $callback();

            if (!$result) {
                break;
            }
        }
    }
}