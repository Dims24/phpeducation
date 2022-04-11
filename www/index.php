<?php
namespace Database;
use Database\DatabaseConnection;

spl_autoload_register(function (string $className) {
    require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

$connect= new DatabaseConnection(
    database: "dataphp",
    username: "dimaphp",
    password: "Lbvf1998");



$test=new \Database\QueryBuilder("dima", connect: $connect->initConnection());

var_dump($test->select("name")->where("id","(1,'Dima')",operator: "AND")->where("name","Dima","=")->first());







