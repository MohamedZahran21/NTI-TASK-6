<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'task 5';

try{
    $connection = mysqli_connect($host, $user, $password, $database);
} catch (mysqli_sql_exception $e){
    echo 'SERVER ERROR FOUND!' . $e->getMessage();
    die;
}
?>