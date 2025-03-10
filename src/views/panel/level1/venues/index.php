<?php

use App\Repositories\VenueCategoriesRepository;
use App\Repositories\VenueRepository;
use App\Services\LoginService;
use App\Utils\CSRF;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {

    $venueCategoryRepository = new VenueCategoriesRepository();
    $venueRepository = new VenueRepository();
    $status = $_GET["status"] ?? "PENDING";

    if (!in_array($status, VenueRepository::STATUSES)) {
        MessageUtil::setMessage("STATUS NOT FOUND");
        LocationUtils::reload();
    }

    $categories = $venueCategoryRepository->getAll();

    $venues = $venueRepository->getAllBy([
        "status" => $status
    ]);

    return TemplateResponse::render(__DIR__ . "/index.twig", [
        "categories" => $categories,
        "venues" => $venues,
        "statuses" => VenueRepository::STATUSES,
        "statusPage" => $status
    ]);

});

$router->post(function () {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $repo = new VenueRepository();

    $cat = $repo->getOne([
       "id" => $id
    ]);

    if (is_null($cat)) {
       MessageUtil::setMessage("Venue not found");
       LocationUtils::redirectInternal('panel/venues');
    }

    $repo->update(data: [
       "status" => $status
    ], criteriaVals: [
       "id" => $id,
    ]);

    MessageUtil::setMessage("Venue updated successfully");
LocationUtils::redirectInternal('panel/venues?status=' . $status);
});
try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
