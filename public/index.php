<?php
require_once '..\bootstrap\app.php';

$connection = new \Database\DatabaseConnection(
    database: "dataphp",
    username: "dimaphp",
    password: "Lbvf1998"
);

$query = new \Database\QueryBuilder("dima", connect: $connection);

$result = $query
    ->select(['column_first', 'column_second'])
    ->where('column_first', '=', 10)
    ->where('column_second', 'ilike', '%test%')
    ->get();

var_dump($result);
