<?php
require_once '..\bootstrap\app.php';

$connection = new \Database\DatabaseConnection(
    database: "phptest",
    username: "dataphp",
    password: "1234"
);
//database: "dataphp",
//    username: "dimaphp",
//    password: "Lbvf1998"

$query = new \Database\QueryBuilder("articles", connection: $connection->getConnection());

$result = $query
    ->select()
    ->where('user_id', "=",44)
//    ->where('column_first',null)
//    ->where('column_second', 'ilike', '%test%')
//    ->where('column_third', 'in', [10,"dima"],"or")
//    ->where(["colum"=>"colum"])
    ->count();
var_dump($result);
