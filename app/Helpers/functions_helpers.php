<?php
if (! function_exists('current_user')) {
    function current_user(): ?\App\Models\User
    {
        $user_token_service = \App\Http\Service\UserTokenService::getInstance();


        return $user_token_service->getCurrentUserToken()?->user();
    }
}

if (! function_exists('now')) {
    function now(): DateTime
    {
        return new DateTime();
    }
}

if (! function_exists('helper_database_begin_transaction')) {
    function helper_database_begin_transaction() : void
    {
        /** @var \App\Foundation\Application $app */
        $app = \App\Foundation\Application::getInstance();

        $app->getDatabaseConnection()->beginTransaction();
    }
}

if (! function_exists('helper_database_commit')) {
    function helper_database_commit() : void
    {
        /** @var \App\Foundation\Application $app */
        $app = \App\Foundation\Application::getInstance();

        $app->getDatabaseConnection()->commit();
    }
}

if (! function_exists('helper_database_rollback')) {
    function helper_database_rollback() : void
    {
        /** @var \App\Foundation\Application $app */
        $app = \App\Foundation\Application::getInstance();

        $app->getDatabaseConnection()->rollBack();
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return App\Helpers\Env\Env::get($key, $default);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return App\Foundation\Config\Config::get($key, $default);
    }
}

if (!function_exists('path')) {
    function path(string $filepath): mixed
    {
        /** @var \App\Foundation\Application $app */
        $app = \App\Foundation\Application::getInstance();

        return format_path($app->getRootPath() . DIRECTORY_SEPARATOR . $filepath);
    }
}

if (!function_exists('format_path')) {
    function format_path(string $path): string
    {
        if (str_contains($path, '/')) {
            str_replace('/', DIRECTORY_SEPARATOR, $path);
        }

        if (str_contains($path, '\\')) {
            str_replace('\\', DIRECTORY_SEPARATOR, $path);
        }

        return $path;
    }
}

if (!function_exists('helper_array_is_assoc')) {
    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array  $array
     * @return bool
     */
    function helper_array_is_assoc(array $array): bool
    {
        return App\Helpers\Collection\Arr::isAssoc($array);
    }
}

if (!function_exists('helper_array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function helper_array_get(ArrayAccess|array $array, string|int|null $key, mixed $default = null): mixed
    {
        return App\Helpers\Collection\Arr::get($array, $key, $default);
    }
}

if (!function_exists('helper_array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array  $array
     * @param  string|null  $key
     * @param  mixed  $value
     * @return array
     */
    function helper_array_set(array &$array, ?string $key, mixed $value): array
    {
        return App\Helpers\Collection\Arr::set($array, $key, $value);
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @param  mixed  ...$args
     * @return mixed
     */
    function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    /**
     * Merges any number of arrays / parameters recursively, replacing
     * entries with string keys with values from latter arrays.
     * If the entry or the next value to be assigned is an array, then it
     * automagically treats both arguments as an array.
     * Numeric entries are appended, not replaced, but only if they are
     * unique
     *
     * @param  array  ...$arrays
     * @return array
     */
    function array_merge_recursive_distinct(array ...$arrays): array
    {
        $base = array_shift($arrays);
        if (!is_array($base)) {
            $base = empty($base) ? array() : array($base);
        }
        foreach ($arrays as $append) {
            if (!is_array($append)) {
                $append = array($append);
            }
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) and !is_numeric($key)) {
                    $base[$key] = $value;
                    continue;
                }
                if (is_array($value) or is_array($base[$key])) {
                    $base[$key] = array_merge_recursive_distinct($base[$key], $value);
                } else {
                    if (is_numeric($key)) {
                        if (!in_array($value, $base)) {
                            $base[] = $value;
                        }
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
        }
        return $base;
    }
}
