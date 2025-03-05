<?php

namespace App\Utils;

class MessageUtil
{

    public static function setMessage(string $message, $title="Success", $type="success"): void {

        $_SESSION['message'] = [
          "message" => $message,
          "title" => $title,
          "type" => $type
        ];
    }

    public static function getMessage(): mixed {
        $message = $_SESSION['message'] ?? null;
        $_SESSION['message'] = null;

        return $message;
    }
}