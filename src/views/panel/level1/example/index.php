<?php

use App\Repositories\UserRepository;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {

    $userRepository = new UserRepository();
    $user = $userRepository->getAll(limit: 1);

    return TemplateResponse::render(__DIR__."/index.twig");
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
