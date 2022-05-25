<?php

namespace App\Common\Patterns;

abstract class Singleton
{
    private static $instances = [];

    private function __construct() { }
    private function __clone() { }

    final public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a " . get_class(self::getInstance()));
    }

    final public static function getInstance(): Singleton
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }
}
