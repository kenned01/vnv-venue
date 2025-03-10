<?php

use App\Repositories\VenueCategoriesRepository;
use App\Utils\CSRF;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    return TemplateResponse::render(__DIR__ . "/index.twig");
});

$router->post(function () {

    CSRF::validateCSRF();
    $categoryRepo = new VenueCategoriesRepository();

    $categoryRepo->add([
        'name' => $_POST['name'],
        'description' => $_POST['description'],
    ]);

    MessageUtil::setMessage("Category created");
    LocationUtils::redirectInternal("panel/venue-categories/home");
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
