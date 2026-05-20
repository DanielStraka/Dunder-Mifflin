<?php

$host = 'localhost';
$db = 'papercompany';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";

try {

    $pdo = new PDO($dsn,$user,$pass);
    $pdo->exec("set names utf8mb4");

} catch(\PDOException $e){

    throw new \PDOException($e->getMessage());
    
}

?>