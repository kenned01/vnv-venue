<?php

use App\Utils\TemplateResponse;


$delimiter = '<div class="container-fluid p-0" id="content">';
$includeView = $_SESSION["includeView"];


$data = TemplateResponse::renderInTemplates('base.admin.twig');
[$bodyStart, $bodyEnd] = explode($delimiter, $data, 2);


echo "$bodyStart $delimiter";
include $includeView;
echo $bodyEnd;

$_SESSION["includeView"] = null;
