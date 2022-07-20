<?php

namespace App\Http\Service;


use App\Models\File;

class Storage
{
    protected const OCTET_LENGTH = 2;

    public function __construct(
        protected string $path = ''
    )
    {
        if ($this->path === '') {
            $this->path = path("storage\app");
        }
    }

    public function put($file): string
    {
        $extension = $this->getExtension($file['name']);

        $name = uniqid();
        $result_folder = $this->makeDir($name);
        $path_to_file = $result_folder . DIRECTORY_SEPARATOR . $name . "." . $extension;
        move_uploaded_file($file['tmp_name'], $path_to_file);

        $relative_path = str_replace($this->path, '', $path_to_file);

        return $relative_path;
    }

    public function get($path)
    {

    }

    private function makeDir(string $file_name): string
    {
        $sliced_directory_path = str_split($file_name, self::OCTET_LENGTH);
        $result_folder = $this->path;

        for ($i = 0; $i < 3; $i++) {
            $result_folder .= DIRECTORY_SEPARATOR . $sliced_directory_path[$i];
        }

        if (!is_dir($result_folder)) {
            mkdir($result_folder, 0777, true);
        }

        return $result_folder;
    }

    private function getExtension(string $file_name){
        $extension_massive = explode('.', $file_name);
        $count_extension = count($extension_massive);
        $extension = $extension_massive[$count_extension - 1];
        return $extension;
    }
}
