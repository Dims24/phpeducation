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
    private bool $firstflag = false;

    public function __construct(
        protected string $table,
        protected        $connection
    )
    {
    }

    public function select(string|array $select = ["*"]): self
    {
        if (is_array($select)) {
            $this->fields = $select;
        } else {
            $this->fields[] = $select;
        }
        return $this;
    }

    public function where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'AND'): self
    {
        if (!$this->conditions) {
            $boolean = 'WHERE';
        }
        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }
        if (empty($this->conditions)) {
            $this->conditions = array($column => [$boolean, $operator, $value]);
        } else {
            $this->conditions[$column] = [$boolean, $operator, $value];
        }
        if (is_array($value)) {
            $value = "(" . implode(',', $this->conditions[$column][2]) . ")";
            $this->conditions[$column] = [$boolean, $operator, $value];
        }
//        $text=$this->conditions[$column][2] . " " . key($this->conditions) . " "
//            .  implode(' ', array_slice($this->conditions[$column], 0, 2));

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
            . var_dump($this->conditions)
            . ($this->firstflag == false ? "" : ' LIMIT  1');
        return $this->query;
    }

    private function buildWhere()
    {
        foreach ($this->conditions as $key => $val) {
            echo $key;
        }
    }


    public function first(): mixed
    {
        $this->firstflag = true;
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        $sth->execute();
        return $sth->fetchAll();
    }

    public function get(): mixed
    {
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        $sth->execute();
        return $sth->fetchAll();
    }
}
