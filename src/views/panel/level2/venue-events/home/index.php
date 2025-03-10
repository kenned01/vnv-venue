<?php

use App\Repositories\VenueCategoriesRepository;
use App\Repositories\VenueEventsRepository;
use App\Repositories\VenueRepository;
use App\Services\LoginService;
use App\Utils\FileUtils;
use App\Utils\LocationUtils;
use App\Utils\MessageUtil;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {

    $venueEventRepo = new VenueEventsRepository();
    $venueRepo = new VenueRepository();
    $user = LoginService::getSession();

    $venueId = $_GET["id"];

    $venue = $venueRepo->getOne([
        "id"=> $venueId,
        "user_id"=>$user->getId()
    ]);

    if (is_null($venue)) {
        MessageUtil::setMessage("Venue not found");
        LocationUtils::redirectInternal("panel/venues/home");
    }

    $events = $venueEventRepo->getAllBy([
       "venue_id" => $venueId
    ]);

    return TemplateResponse::render(__DIR__ . "/index.twig", [
        "data" => $events,
        "venue" => $venue
    ]);

});

$router->post(function () {
   $id = $_POST['id'];
   $venueId = $_GET['id'];
   $repo = new VenueEventsRepository();

   $event = $repo->getOne([
       "id" => $id
   ]);

   if (is_null($event)) {
       MessageUtil::setMessage("Event not found");
       LocationUtils::redirectInternal('panel/venue-events/home?id=' . $venueId);
   }

   FileUtils::removeFile($event->image);
   $repo->delete(["id" => $id]);

   MessageUtil::setMessage("Event deleted");
   LocationUtils::redirectInternal('panel/venue-events/home?id=' . $venueId);
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
