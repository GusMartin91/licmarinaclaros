<?php

include '../assets/conexion/conexion.php';
$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];

// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = :dni";
$stmtDni = $con->prepare($sqlDni);
$stmtDni->bindParam(":dni", $dni);
$stmtDni->execute();

$respuesta['dniExiste'] = $stmtDni->rowCount() > 0;

// Obtener datos del paciente si el DNI existe
if ($respuesta['dniExiste']) {
    $paciente = $stmtDni->fetch(PDO::FETCH_ASSOC);
    $respuesta['nombre'] = $paciente['nombre'];
    $respuesta['apellido'] = $paciente['apellido'];
}

echo json_encode($respuesta);
