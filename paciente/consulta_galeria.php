<?php
include '../assets/conexion/conexion.php';

$dni_paciente = $_POST['dni'];
if (empty($dni_paciente)) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener el DNI del paciente.'
    ]);
    exit;
}

$consulta = "SELECT * FROM galeria_paciente WHERE dni_paciente = $dni_paciente";

$resultConsulta = mysqli_query($con, $consulta);

$array_HC = array();
if (mysqli_num_rows($resultConsulta) > 0) {
    while ($fila = mysqli_fetch_assoc($resultConsulta)) {
        $array_HC[] = $fila;
    }
} else {
    $array_HC = [];
}

echo json_encode($array_HC, JSON_UNESCAPED_UNICODE);

mysqli_close($con);
