<?php
include '../assets/conexion/conexion.php';

session_start();
$dni_paciente = $_POST['dni_paciente'];

$consulta = "SELECT * FROM v_pacientes WHERE dni = '$dni_paciente'";

$resultConsulta = mysqli_query($con, $consulta);
$array_pacientes = array();

while ($fila = mysqli_fetch_assoc($resultConsulta)) {
    $array_pacientes[] = $fila;
}

echo json_encode($array_pacientes, JSON_UNESCAPED_UNICODE);
