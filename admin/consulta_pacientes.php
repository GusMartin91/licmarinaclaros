<?php
include '../assets/conexion/conexion.php';

session_start();

$sql = "SELECT * FROM v_pacientes";

$stmt = $con->prepare($sql);
$stmt->execute();

$arrayPacientes = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $arrayPacientes[] = $row;
}

echo json_encode($arrayPacientes, JSON_UNESCAPED_UNICODE);