<?php

namespace App\Utils;

class JsonResponse
{


    private string $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function createResponse(string $data): Response
    {
        return new Response($data);
    }

    public function handle(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}