<?php
include '../assets/conexion/conexion.php';

session_start();

$consulta = "SELECT * FROM v_historial_consulta";

$resultConsulta = mysqli_query($con, $consulta);
$array_HC = array();

while ($fila = mysqli_fetch_assoc($resultConsulta)) {
    $array_HC[] = $fila;
}

echo json_encode($array_HC, JSON_UNESCAPED_UNICODE);
