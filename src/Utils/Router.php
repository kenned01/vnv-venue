<?php

namespace App\Utils;

use Exception;


class Router
{

    private \Closure $postCallback;
    private \Closure $getCallback;


    public function __construct(){}

    public function post(\Closure $callback) {
        $this->postCallback = $callback;
        return $this;
    }

    public function get(\Closure $callback) {
        $this->getCallback = $callback;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $result = null;

        if ($method == "GET" && isset($this->getCallback)) {
            $callable = $this->getCallback;
            $result = $callable();
        }

        if ($method == "POST" && isset($this->postCallback)) {
            $callable = $this->postCallback;
            $result = $callable();
        }

        if ($result == null) {
            throw new Exception("Method not allowed");
        }

        if ($result instanceof Response || $result instanceof JsonResponse) {
            $result->handle();
        } else {
            echo $result;
        }


    }

}