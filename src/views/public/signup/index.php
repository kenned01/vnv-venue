<?php

use App\Repositories\UserRepository;
use App\Services\HashService;
use App\Utils\LocationUtils;
use App\Utils\Response;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    return TemplateResponse::render(__DIR__."/index.twig");
});

$router->post(function () {
   $userRepository = new UserRepository();

   $email = $_POST["email"];
   $password = $_POST["password"];
   $passwordConfirmation = $_POST["passwordConfirmation"];

   if ($password !== $passwordConfirmation) {
       return Response::createResponse("Passwords must match");
   }

   $userRepository->add([
      "email" => $email,
      "password" => HashService::hashPassword($password)
   ]);

   LocationUtils::redirectInternal('login');
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
