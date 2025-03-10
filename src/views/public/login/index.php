<?php

use App\Services\LoginService;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
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
        try {
            $loginService->authenticate($email, $password);
        } catch (Exception $e) {
            MessageUtil::setMessage($e->getMessage());
            LocationUtils::redirectInternal("login");
        }

        LocationUtils::redirectInternal('panel/home');
    } catch (Exception $e) {
        return $e->getMessage();
    }
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
