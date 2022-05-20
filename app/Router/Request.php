<?php

namespace Router;

class Request
{
    private array $storage;



    public function __construct()
    {
        $this->storage = [
            'method' => $_SERVER["REQUEST_METHOD"],
            'url' => $_SERVER["REQUEST_URI"],
        ];;
    }

    public function getReguest()
    {
        return $this->storage;
    }

}