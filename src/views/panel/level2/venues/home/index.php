<?php

use App\Repositories\VenueCategoriesRepository;
use App\Repositories\VenueRepository;
use App\Services\LoginService;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {

    $venueCategoryRepository = new VenueCategoriesRepository();
    $venueRepository = new VenueRepository();
    $user = LoginService::getSession();

    $categories = $venueCategoryRepository->getAll();

    $venues = $venueRepository->getAllBy([
       "user_id" => $user->getId()
    ]);



    return TemplateResponse::render(__DIR__ . "/index.twig", [
        "categories" => $categories,
        "venues" => $venues
    ]);

});

$router->post(function () {
   $id = $_POST['id'];
   $repo = new VenueRepository();

   $cat = $repo->getOne([
       "id" => $id
   ]);

   if (is_null($cat)) {
       MessageUtil::setMessage("Venue not found");
       LocationUtils::redirectInternal('panel/venues/home');
   }

   $repo->delete([
       "id" => $id
   ]);
   MessageUtil::setMessage("Venue deleted");
   LocationUtils::redirectInternal('panel/venues/home');
});
try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
