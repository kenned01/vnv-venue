<?php

use App\Utils\FileUtils;
use App\Utils\LocationUtils;
use App\Utils\Router;
use App\Utils\TemplateResponse;


$router = new Router();


$router->get(function () {
    $url = isset($_GET['file']) ? urldecode($_GET['file']) : null;

   return TemplateResponse::render(__DIR__."/index.twig", [
       "file" => $url
   ]);
});

$router->post(function () {
    $fileLocation = FileUtils::saveFile($_FILES["file"], "examples");
    LocationUtils::redirectInternal('examples/file-example?file='. urlencode($fileLocation) );
});


$router->run();
