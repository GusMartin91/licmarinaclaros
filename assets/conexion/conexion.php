<?php

$host = "localhost";
$db = "licmarinaclaros";
$user = "nutrihot";
$password = "nutrihot915";

try {
    $con = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
    die();
}
