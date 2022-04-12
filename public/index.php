<?php
require_once '..\bootstrap\app.php';

$connection = new \Database\DatabaseConnection(
    database: "phptest",
    username: "dimaphp",
    password: "1234"
);
//database: "dataphp",
//    username: "dimaphp",
//    password: "Lbvf1998"

$query = new \Database\QueryBuilder("dima", connection: $connection->getConnection());

$result = $query
    ->select()
    ->where('column_first',10)
    ->where('column_second', 'ilike', '%test%')
    ->where('column_third', 'in', [10,"dima"])
    ->first();

var_dump($result);
