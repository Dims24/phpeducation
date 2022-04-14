<?php

namespace Database;

use Database\Contracts\DatabaseConnectionInterface;
use Database\Contracts\QueryBuilderInterface;
use Database\DatabaseConnection;

class QueryBuilder implements QueryBuilderInterface
{

    private $fields = [];
    private $conditions = [];
    private $conditionsarray = [];
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

    public function where(string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'AND'): self
    {
        var_dump(func_get_args()[0]);

        if (!$this->conditions) {
            $boolean = ' WHERE';
        }
        if (func_get_args()[1] === null and func_get_args()[2] === null and (!is_array($column))) {
            $operator = "IS";
            $value = "NULL";
        }
        if (func_get_args()[1] == "!=" and func_get_args()[2] == null and (!is_array($column))) {
            $operator = "IS NOT";
            $value = "NULL";
        }
        if (is_null($value) and !is_null($operator)) {
            $value = $operator;
            $operator = '=';
        }
        if (is_array($column)) {
            foreach ($column as $val) {
                $this->conditionsarray[] = $val;
            }
            $column1 = $this->conditionsarray[0];
            $operator = (empty($this->conditionsarray[1]) ? "" : $this->conditionsarray[1]);
            $value = (empty($this->conditionsarray[2]) ? "" : $this->conditionsarray[2]);
            $this->where($column1, $operator, $value);
            return $this;
        }
        if (is_array($value)) {
            $value = "(" . implode(', ', $value) . ")";
        }


        $this->conditions[] = array($boolean, $column, $operator, $value);
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
            . $this->buildWhere()
            . ($this->firstflag == false ? "" : ' LIMIT  1');
        return $this->query;
    }

    private function buildWhere()
    {
        $text = [];
        foreach ($this->conditions as $val) {
            foreach ($val as $id) {
                $text[] = $id;
            }

        }
        $where = implode(" ", $text);
        return $where;
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
