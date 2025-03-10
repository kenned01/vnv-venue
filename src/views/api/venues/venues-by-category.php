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
    $category = $_GET["category"] ?? 0;
    $limit = $_GET["limit"] ?? 0;

    if ($category == 0) {
        return JsonResponse::createResponse([
            "error" => "Category cannot be null or empty",
        ], 403);
    }

    $cat = $categoryRepo->getOne(["id" => $category]);

    if (is_null($cat)) {
        return JsonResponse::createResponse([
            "error" => "Category not found",
        ], 404);
    }

    $items = $repo->getAllBy(criteriaVals: [
        "category_id" => $category,
        "status" => "APPROVED"
    ], limit: $limit);

    return JsonResponse::createResponse(array_map(function ($item) use ($images, $cat) {

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
