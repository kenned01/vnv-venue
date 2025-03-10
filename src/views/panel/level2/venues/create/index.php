<?php

use App\Repositories\VenueCategoriesRepository;
use App\Repositories\VenueRepository;
use App\Services\LoginService;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\PlacesUtils;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    $categoryRepo = new VenueCategoriesRepository();
    $cat = $categoryRepo->getAll();
    return TemplateResponse::render(__DIR__ . "/index.twig", [
        "categories" => $cat
    ]);
});

$router->post(function () {

    $venueRepo = new VenueRepository();
    $user = LoginService::getSession();

    $lat = null;
    $long = null;

    try {
        [$lat, $long] = PlacesUtils::getCoordinates($_POST["address"]);
    } catch (Exception) {
        MessageUtil::setMessage("Could not get the coordinates for ". $_POST["address"]);
    }

    $venueRepo->add([
        "name" => $_POST["name"],
        "description" => $_POST["description"],
        "address" => $_POST["address"],
        "lat" => $lat,
        "lng" => $long,
        "category_id" => $_POST["category"],
        "user_id" => $user->getId()
    ]);

    MessageUtil::setMessage("Venue created");
    LocationUtils::redirectInternal("panel/venues/home");
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
