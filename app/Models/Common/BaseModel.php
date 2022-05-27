<?php

namespace App\Models\Common;

use App\Common\Hydrate\CanHydrateInterface;
use App\Foundation\Database\QueryBuilder;
use App\Foundation\Database\DatabaseConnection;
use App\Foundation\HTTP\Exceptions\NotFoundException;

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
        $connection = new DatabaseConnection(...config('database.connection'));

        return new QueryBuilder(
            self::getSelfReflector()->getTable(),
            self::getPrimaryKey(),
            $connection->getConnection(),
            self::getSelfReflector()
        );
    }

    public static function hydrateFromCollection(mixed $data): array
    {
        if (!is_array($data) ||!count($data)) {
            return [];
        }

        if (helper_array_is_assoc($data)) {
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

        $reflector = self::getSelfReflector();

        $tmp = new static();
        foreach ($reflector->getFillable() as $column) {
            if (array_key_exists($column, $data)) {
                $tmp->{$column} = $data[$column];
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

        return $query
            ->select()
            ->get();
    }

    //Получение записи по id
    public static function find($value): ?BaseModel
    {
        return self::query()
            ->select()
            ->where(self::getPrimaryKey(), $value)
            ->first();
    }

    /**
     * @throws NotFoundException
     */
    public static function findOrFail($value): ?BaseModel
    {
        return self::query()
            ->select()
            ->where(self::getPrimaryKey(), $value)
            ->firstOrFail();
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->getFillable() as $column) {
            $result[$column] = $this->{$column};
        }

        return $result;
    }

    public function save(): void
    {
        $data = $this->toArray();

        if($data[self::getPrimaryKey()] == null){
            $result = self::query()->insert($this->toArray());
        }else{
            $result = self::query()->update($this->toArray());
        }


        foreach ($this->getFillable() as $column)
        {
            $this->{$column} = $result->{$column};
        }
    }

    public function delete(): void
    {
        $data = $this->toArray();

        if($data[self::getPrimaryKey()] == null){
            $result = self::query()->insert($this->toArray());
        }else{
            $result = self::query()->update($this->toArray());
        }


        foreach ($this->getFillable() as $column)
        {
            $this->{$column} = $result->{$column};
        }
    }

    public function getFillable(): array
    {
        $reflector = new \ReflectionClass(static::class);
        $properties = $reflector->getProperties();

        $result = [];
        foreach ($properties as $model_property)
        {
            if ($model_property->isPublic())
            {
                $result[] = $model_property->getName();
            }
        }

        return $result;
    }

}
