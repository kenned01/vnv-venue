<?php

namespace App\Utils;

use App\Services\LoginService;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TemplateResponse
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public static function render(string $templateLocation, array $data = []): string {

        $folders = explode("/views/", $templateLocation, 2);

        $templateParent = $folders[0] . "/views";
        $templateChild = $folders[1];

        // Specify the directory where your templates are located
        $loader = new FilesystemLoader($templateParent);

        // Initialize Twig environment
        $twig = new Environment($loader);

        $twig->addFunction(new TwigFunction('asset_for', [LocationUtils::class, 'assetFor']));
        $twig->addFunction(new TwigFunction('path', [LocationUtils::class, 'assetFor']));

        return $twig->render($templateChild, [
            "user" => LoginService::getSession(),
            "alertMessage" => MessageUtil::getMessage(),
            ...$data
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public static function renderInTemplates(string $templateName, array $data = []): string {
        $templatesFolder  = __DIR__."/../views/templates";

        $loader = new FilesystemLoader($templatesFolder);

        // Initialize Twig environment
        $twig = new Environment($loader);

        $twig->addFunction(new TwigFunction('asset_for', [LocationUtils::class, 'assetFor']));
        $twig->addFunction(new TwigFunction('path', [LocationUtils::class, 'assetFor']));

        return $twig->render($templateName, [
            "user" => LoginService::getSession(),
            "alertMessage" => MessageUtil::getMessage(),
            ...$data
        ]);
    }

}