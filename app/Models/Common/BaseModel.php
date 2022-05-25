<?php

namespace App\Models\Common;

use App\Common\Hydrate\CanHydrateInterface;
use App\Foundation\Database\QueryBuilder;
use App\Foundation\Database\DatabaseConnection;

abstract class BaseModel implements CanHydrateInterface
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
        $connection = new DatabaseConnection(
            database: "phptest",
            username: "dataphp",
            password: "1234"
        );

        return new QueryBuilder(
            self::getSelfReflector()->getTable(),
            $connection->getConnection(),
            self::getSelfReflector()
        );
    }

    public static function hydrateFromCollection(mixed $data): array
    {
        if (!is_array($data) ||!count($data)) {
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

    public static function hydrateFromSingle(mixed $data): ?BaseModel
    {
        if (!is_array($data) ||!count($data)) {
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

    public function toArray(): array
    {
        $result = [];

        $model_reflection = new \ReflectionClass(static::class);
        foreach ($model_reflection->getProperties() as $model_property) {
            if ($model_property->isPublic()) {
                $result[$model_property->getName()] = $this->{$model_property->getName()};
            }
        }

        return $result;
    }


}