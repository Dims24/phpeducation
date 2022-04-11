<?php

namespace Database;

use Database\Contracts\DatabaseConnectionInterface;
use Database\Contracts\QueryBuilderInterface;
use Database\DatabaseConnection;

class QueryBuilder implements QueryBuilderInterface
{

    private $fields = [];
    private $whereflag = false;
    private string $conditions;
    private int $count;
    private $first = false;
    private string $query;

    public function __construct(
        protected string $table,
        protected DatabaseConnectionInterface $connect
    ) {}


    public function select(string ...$select): self
    {
        if ($select == null) {
            $this->fields = ["*"];
            return $this;
        } else {
            $this->fields = $select;
            return $this;
        }

    }

    public function where(string $colum, string $meaning, mixed $conditions=null, string $type = ''): self
    {
        if (is_null($conditions))
        {
            $conditions = '=';
        }
        if (empty($this->conditions)) {
            if ((bool)$type) {
                $this->conditions = "{$colum} {$conditions} {$meaning} {$type} ";
            } else {
                $this->conditions = "{$colum} {$conditions} {$meaning}";
            }
        } else {
            if ((bool)$type) {
                $this->conditions .= "{$colum} {$conditions} {$meaning} {$type} ";
            } else {
                $this->conditions .= "{$colum} {$conditions} {$meaning}";
            }
        }
        return $this;
    }


    public function toSql(): string
    {

        $this->query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $this->table
            . (empty($this->conditions) ? "" : ' WHERE ' . $this->conditions)
            . ($this->first == false ? '' : ' LIMIT 1')
            . (empty($this->count) ? '' : ' LIMIT ' . $this->count);
        return $this->query;
    }

    public function first(): mixed
    {
        $this->first = true;
        $this->toSql();
        echo $this->query;
        $c = $this->connect;
        $sth = $c->prepare($this->query);
        $sth->execute();
        return $sth->fetchAll();
    }

    public function count(int $count): mixed
    {
        $this->count = $count;
        $this->toSql();
        echo $this->query;
        $c = $this->connect;
        $sth = $c->prepare($this->query);
        $sth->execute();
        return $sth->fetchAll();
    }

    public function get(): mixed
    {
        $this->toSql();
        echo $this->query;
        $c = $this->connect;
        $sth = $c->prepare($this->query);
        $sth->execute();
        return $sth->fetchAll();
    }
}
