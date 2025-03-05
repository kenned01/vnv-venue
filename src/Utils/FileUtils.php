<?php

namespace App\Utils;

use Mimey\MimeTypes;

class FileUtils
{

    public static function generateName(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, // Version 4
            mt_rand(0, 0x3fff) | 0x8000, // Variant
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function saveFile(array $file, string $folder): string
    {

        $mime = new MimeTypes();

        $type = $file['type'];
        $tmpName = $file['tmp_name'];

        $extension = $mime->getExtension($type);
        $fileName = self::generateName() . '.' . $extension;

        $dirLocation = dirname(__DIR__, 2) . '/public/files/' . $folder . '/';

        if (!file_exists($dirLocation)) {
            mkdir($dirLocation, 0777, true);
        }

        move_uploaded_file($tmpName, $dirLocation . $fileName);

        return "files/" . $folder . "/". $fileName;
    }
}