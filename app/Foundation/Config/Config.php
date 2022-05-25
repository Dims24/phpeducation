<?php
declare(strict_types=1);

namespace App\Foundation\Config;

use App\Foundation\Application;

class Config
{
    protected static ?array $loaded_data = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        self::init();

        return helper_array_get(self::$loaded_data, $key, $default);
    }

    protected static function init(): void
    {
        if (is_null(self::$loaded_data)) {
            /** @var Application $app */
            $app = Application::getInstance();

            $config_folder = $app->getRootPath() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

            self::$loaded_data = [];
            foreach (scandir($config_folder) as $file_name) {
                if (in_array($file_name, ['.', '..'])) {
                    continue;
                }

                if (str_ends_with($file_name, '.php')) {
                    $tmp = require $config_folder . $file_name;

                    $exploded_file_name = explode('.', $file_name);
                    array_pop($exploded_file_name);
                    $config_file_key = implode('.', $exploded_file_name);

                    self::$loaded_data = array_merge_recursive_distinct(self::$loaded_data, [$config_file_key => $tmp]);
                }
            }
        }
    }
}
