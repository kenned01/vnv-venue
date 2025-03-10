<?php

use App\Repositories\VenueCategoriesRepository;
use App\Repositories\VenuePhotosRepository;
use App\Repositories\VenueRepository;
use App\Utils\Cors;
use App\Utils\JsonResponse;
use App\Utils\LocationUtils;
use App\Utils\Router;

Cors::handle();

$router = new Router();

$router->get(function () {
    $categoryRepo = new VenueCategoriesRepository();
    $images = new VenuePhotosRepository();
    $repo = new VenueRepository();
    $limit = $_GET["limit"] ?? 0;

    $items = $repo->getAllBy(criteriaVals: [
        "status" => "APPROVED"
    ], limit: $limit);
    $categories = $categoryRepo->getAll();

    return JsonResponse::createResponse(array_map(function ($item) use ($categories, $images) {

        $cat = null;
        foreach ($categories as $category) {
            if ($category->id == $item->id) {
                $cat = $category;
                break;
            }
        }

        $photos = $images->getAllBy(criteriaVals: [
           'venue_id' => $item->id,
        ]);

        return [
            "id" => $item->id,
            "name" => $item->name,
            "description" => $item->description,
            "address" => $item->address,
            "category" => [
                "id" => $cat?->id,
                "name" => $cat?->name,
                "description" => $cat?->description,
            ],
            "photos" => array_map(function ($photo) {
                return [
                    "id" => $photo->id,
                    "url" => LocationUtils::assetFor($photo->image),
                ];
            }, $photos),
        ];
    }, $items));
});

$router->run();
