<?php

namespace App\Utils;

class LocationUtils
{

    public static function assetFor(string $uri): string {
        return self::getBasePath()."/".$uri;
    }

    public static function pathFor(string $uri): string {
        return self::getBasePath()."/".$uri;
    }

    public static function getBasePath(): string {

        $requestUri = $_SERVER['REQUEST_URI'];
        $serverName = $_SERVER['SERVER_NAME'];
        $serverPort = $_SERVER['SERVER_PORT'];
        $serverScheme = $_SERVER['REQUEST_SCHEME'];

        $fileName = "$serverScheme://$serverName";

        if ($serverPort != 80 && $serverPort != 443) {
            $fileName .= ":$serverPort";
        }

        if (str_contains($requestUri, self::getRootFolderName())) {
            $fileName .= "/" . self::getRootFolderName();
        }

        return $fileName;
    }

    public static function getRootFolderName() : string
    {
        $folderLocation = dirname(__DIR__, 2);
        $folderLocationArray = explode(DIRECTORY_SEPARATOR, $folderLocation);
        return end($folderLocationArray);
    }

    public static function redirectTo(string $path): never
    {
        header("Location: $path");
        exit();
    }

    public static function redirectInternal(string $path): never
    {
        $internalPath = self::pathFor($path);
        header("Location: $internalPath");
        exit();
    }

    public static function reload(): void {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $fullUrl = $protocol . "://" . $host . $uri;

        header("Location: $fullUrl");
        exit();
    }
}