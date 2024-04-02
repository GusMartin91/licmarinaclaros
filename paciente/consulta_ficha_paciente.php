<?php
include '../assets/conexion/conexion.php';

session_start();
$dni_paciente = $_POST['dni_paciente'];

$consulta = "SELECT * FROM v_pacientes WHERE dni = '$dni_paciente'";

$resultadoConsulta = mysqli_query($con, $consulta);

if ($resultadoConsulta) {
    $paciente = mysqli_fetch_assoc($resultadoConsulta);

    echo json_encode($paciente, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al obtener la información del paciente']);
    exit;
}
