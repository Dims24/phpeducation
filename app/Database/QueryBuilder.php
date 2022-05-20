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
    private $execute = [];
    private string $sortorder;
    private string $query;
    private int $limits;
    private int $offs;

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
        /*
        IF COLUMN IS ARRAY
        */
        if (is_array(func_get_args()[0])) {
            foreach ($column as $key=>$val) {
                $this->where($key,$val);;
            }
            return $this;
        }
        if (!$this->conditions) {
            $boolean = ' WHERE';
        }
        if (!array_key_exists("1", func_get_args()) and !array_key_exists("2", func_get_args())) {
            return $this;
        } else if (array_key_exists("1", func_get_args()) and array_key_exists("2", func_get_args())) {
            /*
            IS NOT NULL
            */
            if (func_get_args()[1] == "!=" and (func_get_args()[2] == null or func_get_args()[2] == 'null') and (!is_array($column)))
            {
                $operator = "IS NOT";
                $value = "NULL";
                $this->conditions[] = array($boolean, $column, $operator, $value);
                return $this;
            }
            /*
            VALUE IS ARRAY
            */
            if (is_array($value))
            {
                foreach ($value as $val)
                {
                    $this->execute[] = $val;
                }
                $value = str_repeat('?,', count($value) - 1) . '?';
                $this->conditions[] = array($boolean, $column, $operator, "($value)");
                return $this;
            }
            $this->execute[] = $value;
            $this->conditions[] = array($boolean, $column, $operator, "?");
            return $this;
        }
        else if (array_key_exists("1", func_get_args()) and !array_key_exists("2", func_get_args()))
        {
            /*
            IS NULL
            */
            if ((func_get_args()[1] == null or func_get_args()[1] == 'null') and (!is_array($column)))
            {
                $operator = "IS";
                $value = "NULL";
                $this->conditions[] = array($boolean, $column, $operator, $value);
                return $this;
            }
            /*
            IF VALUE EMPTY (null)
            */
            if (array_key_exists("1", func_get_args()) and is_null($value) and !is_null($operator))
            {
                $value = $operator;
                $operator = '=';
                $this->execute[] = $value;
                $this->conditions[] = array($boolean, $column, $operator, "?");
                return $this;
            }
            $this->execute[] = $value;
            $this->conditions[] = array($boolean, $column, $operator, "?");
            return $this;
        } else {
            return $this;
        }
    }

    /**
     * @param string|array $sort параметр по которому сортируем
     * @param string|null $order может быть "ASC" или "DESC"
     */
    public function orderby(string|array $sort, string $order = null): self
    {
        if (array_key_exists("1", func_get_args())) {
            $this->sortorder = $sort . ' ' . strtoupper($order);
        } else {
            if (is_array($sort)) {
                $this->sortorder = implode(', ', $sort);
            } else {
                $this->sortorder = $sort;
            }
        }
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limits = $limit;
        return $this;
    }

    public function skip(int $offset): self
    {
        $this->offs = $offset;
        return $this;
    }

    public function count(): int
    {
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        $sth->execute($this->execute);
        return $sth->rowCount();
    }

    public function toSql(): string
    {
        $this->query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $this->table
            . $this->buildWhere()
            . (empty($this->sortorder) ? "" : ' ORDER BY' . " " . $this->sortorder)
            . (isset($this->limits) ? ' LIMIT ' . $this->limits : "")
            . (empty($this->offs) ? "" : ' OFFSET ' . $this->offs);
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
        $this->limits = 1;
        $this->toSql();
//        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        $sth->execute($this->execute);
        return $sth->fetch();
    }

    public function get(): mixed
    {
        $this->toSql();
        echo $this->query;
        $sth = $this->connection->prepare($this->query);
        $sth->execute($this->execute);
        return $sth->fetchAll();
    }
}
