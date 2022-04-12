<?php
require_once '..\bootstrap\app.php';

$connection = new \Database\DatabaseConnection(
    database: "dataphp",
    username: "dimaphp",
    password: "Lbvf1998"
);

$query = new \Database\QueryBuilder("dima", connection: $connection->getConn());

$result = $query
    ->select()
    ->where('column_first',value: ['name',10])
//    ->where('column_second', 'ilike', '%test%')
    ->toSql();

var_dump($result);
