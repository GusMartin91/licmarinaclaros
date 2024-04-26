<?php

include '../assets/conexion/conexion.php';

$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];

try {
    // Verificar DNI
    $sqlDni = "SELECT * FROM pacientes WHERE dni = :dni";
    $stmtDni = $con->prepare($sqlDni);
    $stmtDni->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmtDni->execute();
    $rowCount = $stmtDni->rowCount();
    $respuesta['dniExiste'] = $rowCount > 0;

    // Obtener datos del paciente si el DNI existe
    if ($respuesta['dniExiste']) {
        $paciente = $stmtDni->fetch(PDO::FETCH_ASSOC);
        $respuesta['nombre'] = $paciente['nombre'];
        $respuesta['apellido'] = $paciente['apellido'];
    }
} catch (PDOException $e) {
    // Manejo de errores de PDO
    $respuesta['error'] = "Error al ejecutar la consulta: " . $e->getMessage();
}

echo json_encode($respuesta);
