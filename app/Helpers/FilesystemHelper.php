<?php

namespace Helpers;

class FilesystemHelper
{
    public static function getPath(string $path = ''): string
    {
        $root = self::getRoot();

        if ($path == '') {
            return $root;
        }

        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            $path = substr($path, 1, strlen($path));
        }

        return $root . DIRECTORY_SEPARATOR . $path;
    }

    public static function getRoot(): string
    {
        #Возвращает 'C:\Users\Admin\PhpstormProjects\phpeducation\app\Helpers'
        $current_path = __DIR__;

        $exploded_current_dir = explode(DIRECTORY_SEPARATOR, $current_path);
        $to_root = [];
        foreach ($exploded_current_dir as $element) {
            if ($element == 'app') {
                break;
            }

            $to_root[] = $element;
        }

        return implode(DIRECTORY_SEPARATOR, $to_root);
    }
}