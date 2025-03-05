<?php

namespace App;

use App\Entity\User;
use App\Services\LoginService;
use App\Utils\LocationUtils;
use Exception;
use JetBrains\PhpStorm\NoReturn;

class Kernel {


    private static string $protectedFolderViews = "panel";
    private static string $publicFolderViews = "public";
    private static string $apiFolderViews = "api";

    private static string $protectedUrlPrefix = "panel";
    private static string $apiUrlPrefix = "api";

    private static string $homeIndex = "login";
    private static string $notFoundIndex = "404.php";
    private static string $errorIndex = "505.php";
    private static string $notFoundApi = "404.php";


    public function __construct()
    {

    }

    private function getUrlViews(): array
    {
        if (isset($_GET["url"])) {
            $url = rtrim($_GET["url"], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', strtolower($url));
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $urlViews = $this->getUrlViews();

            // INCLUDE HOME VIEW
            if (empty($urlViews)) {
                $this->includeViewAndExit($this->getPublicView([self::$homeIndex]));
            }

            // INCLUDE ADMIN VIEWS
            if ($urlViews[0] == self::$protectedFolderViews && $this->isLoggedIn()) {
                if (count($urlViews) == 1) {
                    LocationUtils::redirectInternal("panel/home");
                }
                $this->includeAdminViewAndExit($this->getPrivateView($urlViews));
            }

            // REDIRECT TO LOGIN WHEN TRYING ACCESSING ADMIN
            if ($urlViews[0] == self::$protectedUrlPrefix && !$this->isLoggedIn()) {
                LocationUtils::redirectInternal("login");
            }

            // REDIRECT TO API IF MATCHED
            if ($urlViews[0] == self::$apiUrlPrefix) {
                $this->includeViewAndExit($this->getApiViews($urlViews));
            }

            // INCLUDE PUBLIC VIEW
            $this->includeViewAndExit($this->getPublicView($urlViews));
        } catch (Exception $exception) {

            if ($_ENV["app_env"] == "debug") {
                throw $exception;
            }

            $this->includeViewAndExit($this->getPublicView([self::$errorIndex]));
        }
    }

    private function isLoggedIn(): bool
    {
        $user = LoginService::getSession();
        if ($user == null) {
            return false;
        }

        if ($user instanceof User) {
            return true;
        }

        return false;
    }

    private function getPrivateView(array $urlViews): array
    {
        $view = $urlViews[1];
        $user = LoginService::getSession();
        $userLevel = $user->getLevel();

        $baseView = __DIR__."/views/panel/level$userLevel";

        return $this->getView($baseView, $view);
    }

    private function getPublicView(array $urlViews): array
    {
        $view = implode(DIRECTORY_SEPARATOR, $urlViews);
        $baseView = __DIR__."/views/".self::$publicFolderViews;

        return $this->getView($baseView, $view);
    }

    private function getApiViews(array $urlViews): array
    {
        $view = $urlViews[0];
        $baseView = __DIR__."/views/".self::$apiFolderViews;

        if (file_exists("$baseView/$view.php")) {
            return ["$baseView/$view.php", false];
        }

        return [__DIR__."/views/" . self::$apiFolderViews . "/" . self::$notFoundApi, false];
    }

    #[NoReturn]
    private function includeViewAndExit(array $payload): void
    {
        $view = $payload[0];
        $isLegacy = $payload[1];

        if (!$isLegacy) {
            include $view;
            exit(0);
        }

        // handle legacy includes
        include __DIR__."/views/templates.old/base.start.php";
        include $view;
        include __DIR__."/views/templates.old/base.end.php";
        exit(0);
    }

    #[NoReturn]
    private function includeAdminViewAndExit(array $payload): void
    {
        $view = $payload[0];
        $isLegacy = $payload[1];

        if (!$isLegacy) {
            include $view;
            exit(0);
        }

        // handle legacy includes
        include __DIR__."/views/templates.old/base-admin.start.php";
        include $view;
        include __DIR__."/views/templates.old/base-admin.end.php";
        exit(0);
    }

    /**
     * @param string $baseView
     * @param mixed $view
     * @return string
     */
    public function getView(string $baseView, mixed $view): array
    {
        if (file_exists("$baseView/$view.php")) {
            return ["$baseView/$view.php", false];
        }

        if (file_exists("$baseView/$view/index.php")) {
            return ["$baseView/$view/index.php", false];
        }

        // CHECK for old system
        if (file_exists("$baseView/$view.old.php")) {
            return ["$baseView/$view.old.php", true];
        }

        return [__DIR__ . "/views/public/" . self::$notFoundIndex, false];
    }
}