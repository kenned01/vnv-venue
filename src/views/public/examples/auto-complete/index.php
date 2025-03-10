<?php

use App\Utils\TemplateResponse;

$data = TemplateResponse::render(__DIR__."/index.twig");

echo $data;