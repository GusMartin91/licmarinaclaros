<?php
include '../assets/conexion/conexion.php';

session_start();

$consulta = "SELECT * FROM v_pacientes";

$resultConsulta = mysqli_query($con, $consulta);
$array_pacientes = array();

while ($fila = mysqli_fetch_assoc($resultConsulta)) {
    $array_pacientes[] = $fila;
}

echo json_encode($array_pacientes, JSON_UNESCAPED_UNICODE);
