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
//        var_dump(array_key_exists("2", func_get_args()));
//        var_dump(boolval(!array_key_exists("1", func_get_args())));
//        var_dump(boolval(!array_key_exists("2", func_get_args())));
//        var_dump(is_array(func_get_args()[0]));
        /*
        IF COLUMN IS ARRAY
        */
        if (is_array(func_get_args()[0])) {
            foreach ($column as $val) {
                $this->conditionsarray[] = $val;
            }
            var_dump($this->conditionsarray);
            $column = $this->conditionsarray[0];
//            $operator = (empty($this->conditionsarray[1]) ? null : $this->conditionsarray[1]);
            $operator = $this->conditionsarray[1];
            $value = $this->conditionsarray[2];
            $boolean = (empty($this->conditionsarray[4]) ? 'AND' : $this->conditionsarray[3]);
            echo $column, $operator, $value, $boolean;
            $this->where($column, $operator, $value, $boolean);
            return $this;
        }

        if (!array_key_exists("1", func_get_args()) and !array_key_exists("2", func_get_args())) {
            return $this;
        } else if (array_key_exists("1", func_get_args()) and array_key_exists("2", func_get_args())) {
            if (!$this->conditions) {
                $boolean = ' WHERE';
            }
            /*
            IS NULL
            */
            if ((func_get_args()[1] == null or func_get_args()[1] == 'null') and (!is_array($column))) {

                $operator = "IS";
                $value = "NULL";
//                $this->execute[] = $column;
                $this->conditions[] = array($boolean, $column, $operator, $value);
                return $this;
            }
            /*
            IS NOT NULL
            */
            if (func_get_args()[1] == "!=" and (func_get_args()[2] == null or func_get_args()[2] == 'null') and (!is_array($column))) {
                $operator = "IS NOT";
                $value = "NULL";
//                $this->execute[] = $column;
                $this->conditions[] = array($boolean, $column, $operator, $value);
                return $this;
            }
            /*
            VALUE IS ARRAY
            */
            if (is_array($value)) {
                foreach ($value as $val) {
                    $this->execute[] = $val;
                }
                $value = str_repeat('?,', count($value) - 1) . '?';
//                $this->execute[] = $column;
                $this->conditions[] = array($boolean, $column, $operator, "($value)");
                return $this;
            }
//            $this->execute[] = $column;
            $this->execute[] = $value;
            $this->conditions[] = array($boolean, $column, $operator, "?");
            return $this;
        } else if (array_key_exists("1", func_get_args()) and !array_key_exists("2", func_get_args())) {
            echo 1;
            if (!$this->conditions) {
                $boolean = ' WHERE';
            }
            /*
            IS NULL
            */
            if ((func_get_args()[1] == null or func_get_args()[1] == 'null') and (!is_array($column))) {

                $operator = "IS";
                $value = "NULL";
//                $this->execute[] = $column;
                $this->conditions[] = array($boolean, $column, $operator, $value);
                return $this;
            }
            /*
            IF VALUE EMPTY (null)
            */
            if (array_key_exists("1", func_get_args()) and is_null($value) and !is_null($operator)) {
                $value = $operator;
                $operator = '=';
//                $this->execute[] = $column;
                $this->execute[] = $value;
                $this->conditions[] = array($boolean, $column, $operator, "?");
                return $this;
            }
//            $this->execute[] = $column;
            $this->execute[] = $value;
            $this->conditions[] = array($boolean, $column, $operator, "?");
            return $this;
        } else {
            return $this;
        }
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
            . ($this->firstflag == false ? "" : ' LIMIT  1');
        return $this->query;
    }

    private function buildWhere()
    {
        var_dump($this->conditions);
        var_dump($this->execute);
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
        $sth->execute($this->execute);
        return $sth->fetchAll();
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
