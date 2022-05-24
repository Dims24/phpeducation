<?php

namespace MyProject\Models;

use Database\QueryBuilder;

abstract class BaseModel
{
    protected string $table = '';
    protected string $primary_key = 'id';

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    protected static function getSelfReflector(): BaseModel
    {
        return new static();
    }

    public static function getPrimaryKey(): string
    {
        return self::getSelfReflector()->primary_key;
    }

    public static function query(): QueryBuilder
    {
        $connection = new \Database\DatabaseConnection(
            database: "phptest",
            username: "dataphp",
            password: "1234"
        );


        return new QueryBuilder(self::getSelfReflector()->getTable(), $connection->getConnection());
    }

    protected static function hydrateFromCollection(array|bool|null $data): array
    {
        if ((is_bool($data) && !$data) || is_null($data) || !is_array($data) ||!count($data)) {
            return [];
        }

        if (array_is_assoc($data)) {
            $data = [$data];
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = self::hydrateFromSingle($item);
        }

        return $result;
    }

    protected static function hydrateFromSingle(array|bool|null $data): ?BaseModel
    {
        if ((is_bool($data) && !$data) || is_null($data) || !is_array($data) ||!count($data)) {
            return null;
        }

        $tmp = new static();
        $model_reflection = new \ReflectionClass(static::class);
        foreach ($model_reflection->getProperties() as $model_property) {
            if ($model_property->isPublic()) {
                if (array_key_exists($model_property->getName(), $data)) {
                    $tmp->{$model_property->getName()} = $data[$model_property->getName()];
                }
            }
        }

        return $tmp;
    }

    //Получение всех записей
    /**
     * @return static
     */
    public static function all(): array
    {
        $query = self::query();

        $result = $query
            ->select()
            ->get();

        return self::hydrateFromCollection($result);
    }

    //Получение записи по id
    public static function find($value): ?BaseModel
    {
        $result = self::query()
            ->select()
            ->where(self::getPrimaryKey(), $value)
            ->first()
        ;

        return self::hydrateFromSingle($result);
    }


}