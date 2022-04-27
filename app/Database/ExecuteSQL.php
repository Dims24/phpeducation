<?php

namespace Database;

class ExecuteSQL
{

    public function __construct(
        protected string $sql,
        protected $execute
    )
    {}

    private function execute{

    }
}