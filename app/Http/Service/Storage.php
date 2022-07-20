<?php

namespace App\Http\Service;


use App\Models\File;

class Storage
{

    public function __construct(
        protected string $path
    )
    {
    }

    public function put($file)
    {
        $extension = $this->getExtension($file['name']);

        $name = uniqid();
        $broken_path = $this->makeDir($name);


        move_uploaded_file($file['tmp_name'], $broken_path . $name . "." . $extension);


    }

    public function get($path)
    {

    }

    private function makeDir(string $file_name): string
    {
        $directory = str_split($file_name, 2);
        $broken_path = $this->path . $directory[0] . "\\" . $directory[1] . "\\" . $directory[2] . "\\";
        if (is_dir($broken_path)) {
            return $broken_path;
        } else {
            mkdir($broken_path, 0777, true);
            return $broken_path;
        }
    }

    private function getExtension(string $file_name){
        $extension_massive = explode('.', $file_name);
        $count_extension = count($extension_massive);
        $extension = $extension_massive[$count_extension - 1];
        return $extension;
    }
}