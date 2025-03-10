<?php

use App\Repositories\UserRepository;
use App\Services\LoginService;
use App\Services\StripeService;
use App\Utils\JsonResponse;
use App\Utils\Router;
use App\Utils\TemplateResponse;

$router = new Router();

$router->get(function () {
    return TemplateResponse::render(__DIR__ . "/index.twig", [
        "stripe_key" => $_ENV["STRIPE_PUBLIC"],
        "membership_value" => $_ENV["MEMBERSHIP_VALUE"]
    ]);
});

$router->post(function () {
    $stripeService = new StripeService();
    $userRepository = new UserRepository();
    $membershipAmount = floatval($_ENV["MEMBERSHIP_VALUE"]);

    $user = LoginService::getSession();
    $token = $_POST["token"] ?? "";

    if (empty($token)) {
       return JsonResponse::createResponse([
           "success" => false,
           "message" => "Token is required",
       ]);
    }

    $paymentSuccess = $stripeService->createChargeV1($token, $membershipAmount);

    if (!$paymentSuccess) {
        return JsonResponse::createResponse([
            "success" => false,
        ]);
    }

    // TODO: update membership for user
    $newDate = (new DateTime())->add(new DateInterval("P1Y"))->format('Y-m-d');

    $userRepository->update(
        [
            "membership_due_date" => $newDate,
        ],
        [
            "id" => $user->getId()
        ]
    );

    $user->setMembershipDueDate($newDate);
    LoginService::setSession($user);

    return JsonResponse::createResponse([
        "success" => true
    ]);
});

try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
