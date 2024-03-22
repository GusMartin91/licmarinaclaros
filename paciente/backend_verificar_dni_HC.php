<?php

include '../assets/conexion/conexion.php';

$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];
// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = '$dni'";
$resultDni = mysqli_query($con, $sqlDni);
$respuesta['dniExiste'] = mysqli_num_rows($resultDni) > 0;

// Obtener datos del paciente si el DNI existe
if ($respuesta['dniExiste']) {
    $paciente = mysqli_fetch_assoc($resultDni);
    $respuesta['nombre'] = $paciente['nombre'];
    $respuesta['apellido'] = $paciente['apellido'];
}

echo json_encode($respuesta);
