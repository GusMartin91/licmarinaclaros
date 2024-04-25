<?php
include '../assets/conexion/conexion.php';

session_start();

$sql = "SELECT * FROM v_historial_consulta";

$stmt = $con->prepare($sql);
$stmt->execute();

$arrayHC = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $arrayHC[] = $row;
}

echo json_encode($arrayHC, JSON_UNESCAPED_UNICODE);
