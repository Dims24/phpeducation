<?php
use JetBrains\PhpStorm\NoReturn;

if (!function_exists('dd')) {
    #[NoReturn] function dd(...$args): void
    {
        foreach ($args as $arg) {
            var_dump($arg);
        }

        exit(0);
    }
}

if (!function_exists('array_is_assoc')) {
    function array_is_assoc(array $arr): bool
    {
        if (array() === $arr) return false;

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}