<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'task 5';

try{
    $connection = new mysqli($host, $user, $password, $database);
} catch (mysqli_sql_exception $e){
    echo 'SERVER ERROR!' . $e->getMessage();
    die;
}
?>