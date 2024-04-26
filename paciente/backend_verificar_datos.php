<?php

include '../assets/conexion/conexion.php';

$email = isset($_POST['email']) ? $_POST['email'] : "";
$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];

try {
    // Verificar email
    $sqlEmail = "SELECT * FROM pacientes WHERE email = :email";
    $stmtEmail = $con->prepare($sqlEmail);
    $stmtEmail->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtEmail->execute();
    $rowCountEmail = $stmtEmail->rowCount();
    $respuesta['emailExiste'] = $rowCountEmail > 0;

    // Verificar DNI
    $sqlDni = "SELECT * FROM pacientes WHERE dni = :dni";
    $stmtDni = $con->prepare($sqlDni);
    $stmtDni->bindParam(':dni', $dni, PDO::PARAM_STR);
    $stmtDni->execute();
    $rowCountDni = $stmtDni->rowCount();
    $respuesta['dniExiste'] = $rowCountDni > 0;
} catch (PDOException $e) {
    // Manejo de errores de PDO
    $respuesta['error'] = "Error al ejecutar la consulta: " . $e->getMessage();
}

echo json_encode($respuesta);
