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
//    ->where('user_id', "=",44)
//    ->where('name', "!=", null)
//    ->where('name', 'ilike', '%PLC%')
//    ->where('user_id', 'in', [58],"or")
    ->where(["user_id"=>72])
//    ->orderby("user_id","ASC")
//    ->limit(5)
//    ->skip(2)
    ->first();
var_dump($result);
