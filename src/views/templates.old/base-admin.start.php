<?php
    use App\Utils\LocationUtils;
    use App\Services\LoginService;

    $user = LoginService::getSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="knx" />
    <title>Dashboard old</title>

    <link rel="shortcut icon" href="<?=LocationUtils::assetFor('template/img/icons/icon-48x48.png')?>" />
    <link href="<?=LocationUtils::assetFor('template/css/app.css')?>" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />
</head>

<body>
<div class="wrapper">

    <?php if ($user->getLevel() == 1): ?>
        <?php include  __DIR__."/sidebars/sidebar.php" ?>
    <?php endif; ?>

    <div class="main">
        <?php include  __DIR__."/headers/header.php" ?>

        <main class="content">
            <div class="container-fluid p-0">