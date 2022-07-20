<?php

namespace App\Models;

use App\Models\Common\BaseModel;

class File extends BaseModel
{
    protected string $table = "files";

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var string */
    public $essence;

    /** @var int */
    public $essence_id;

    /** @var string */
    public $path;

    public static function make(string $path, ?BaseModel $model = null): File
    {
        $file = new File();
        $name = explode("\\", $path);
        $type = explode(".", $name[count($name) - 1]);
        $file->path = $path;
        $file->name = $name[count($name) - 1];

        if (!is_null($model)) {
            $file->essence = $model->getTable();
            $file->essence_id = $model->id;
        }

        $file->type = $type[count($type) - 1];
        $file->save();

        return $file;
    }
}
