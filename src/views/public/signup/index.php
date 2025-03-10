<?php

use App\Repositories\UserRepository;
use App\Services\HashService;
use App\Utils\FormatPhone;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\Response;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    return TemplateResponse::render(__DIR__."/index.twig");
});

$router->post(function () {
   $userRepository = new UserRepository();
   $password = $_POST["password"];
   $passwordConfirmation = $_POST["passwordConfirmation"];

   if ($password !== $passwordConfirmation) {
       return Response::createResponse("Passwords must match");
   }

   $userExits = $userRepository->getOne([
       "email" => $_POST["email"]
   ]);

   if ($userExits != null) {
       MessageUtil::setMessage("User already exists");
       LocationUtils::redirectInternal('signup');
   }

   $userRepository->add([
       'name' => $_POST["name"],
       'lastname' => $_POST["lastname"],
       'email' => $_POST["email"],
       'password' => HashService::hashPassword($password),
       'phone' => FormatPhone::formatPhone($_POST["phoneNumber"]),
       'phone_code' =>  '',
       'phone_validation' => 0,
       'level' => 2
   ]);

   LocationUtils::redirectInternal('login');
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
