<?php

use App\Services\LoginService;
use App\Utils\LocationUtils;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    return TemplateResponse::render(__DIR__."/index.twig");
});

$router->post(function () {

    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loginService = new LoginService();
        $loginService->authenticate($email, $password);

        LocationUtils::redirectInternal('panel/example');
    } catch (Exception $e) {
        return $e->getMessage();
    }
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
