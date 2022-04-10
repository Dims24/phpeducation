<?php

namespace Database;

use Database\DatabaseConnection;

class QueryBuilder extends DatabaseConnection
{

    private $fields = [];
    private $conditions = [];
    private int $count;
    private $get1 = false;
    private $first = false;
    private string $query;


    protected $table;
    protected $connect;

    public function __construct(
        string $table, $connect)
    {
        $this->table = $table;
        $this->connect = $connect;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    public function __toString()
    {
        return $this->query;
    }


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

    public function where(string ...$where): self
    {
        foreach ($where as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }



    public function toSql(): string
    {

        $this->query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $this->table
            . ($this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions))
            . ($this->first == false ? '' : ' LIMIT 1');
        return $this->query;
    }

    public function first(): mixed
    {
        $this->first=true;
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
