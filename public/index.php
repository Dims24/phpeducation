<?php
/** @var Application $app */
$app = require_once '..\bootstrap\app.php';

$app->run();
//
//$connection = new \Database\DatabaseConnection(
//    database: "phptest",
//    username: "dataphp",
//    password: "1234"
//);
////database: "dataphp",
////    username: "dimaphp",
////    password: "Lbvf1998"
//
//$query = new \Database\QueryBuilder("articles", connection: $connection->getConnection());
//
//$result = $query
//    ->select()
////    ->where('user_id', "=",44)
////    ->where('name', "!=",null)
//    ->where('name', 'ilike',"%a%")
////    ->where('user_id', 'in', [1])
////    ->where(["user_id"=>1])
//    ->orderby("id")
//    ->limit(5)
//    ->skip(2)
//    ->first();
//var_dump($result);


