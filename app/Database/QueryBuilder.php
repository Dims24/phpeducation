<?php

namespace Database;

use Database\Contracts\DatabaseConnectionInterface;
use Database\Contracts\QueryBuilderInterface;
use Database\DatabaseConnection;

class QueryBuilder implements QueryBuilderInterface
{

    private $fields = [];
    private $conditions = [];
    private string $query;

    public function __construct(
        protected string $table,
        protected $connection
    ) {}

    public function select(string|array $select=["*"]): self
    {
        if(is_array($select)){
            $this->fields = $select;
        }
        else{
            $this->fields[] = $select;
        }
        return $this;
    }

    public function where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'AND'): self
    {
        if (is_null($operator))
        {
            $operator = '=';
        }

        $this->conditions[] = $column . $operator . $value . $boolean;

        return $this;
    }

    public function count(): int
    {
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        return $sth->rowCount();
    }

    public function toSql(): string
    {
        $this->query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $this->table
            . (empty($this->conditions) ? "" : ' WHERE ' . implode(' ', $this->conditions));
        return $this->query;
    }

    public function first(): mixed
    {
        $this->toSql();
        $this->query .=  ' LIMIT 1';
        echo $this->query;
        $c=$this->connection;
        $sth = $c->prepare($this->query);
        return $sth->fetchAll();
    }

    public function get(): mixed
    {
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        return $sth->fetchAll();
    }
}
