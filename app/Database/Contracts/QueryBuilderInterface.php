<?php
declare(strict_types=1);

namespace Database\Contracts;

interface QueryBuilderInterface
{
    public function select(string|array $select): self;
    public function where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and'): self;

    public function toSql(): string;
    public function count(): int;
    public function get(): mixed;
    public function first(): mixed;
}
