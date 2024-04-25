<?php
include '../assets/conexion/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$dni = isset($data['dni']) ? $data['dni'] : "";
$email = isset($data['email']) ? $data['email'] : "";
$rseguridad = isset($data['rseguridad']) ? $data['rseguridad'] : "";
$new_pass = isset($data['new_pass']) ? $data['new_pass'] : "";

$respuesta = [];

// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = :dni";
$stmtDni = $con->prepare($sqlDni);
$stmtDni->bindParam(":dni", $dni);
$stmtDni->execute();
$respuesta['dniExiste'] = $stmtDni->rowCount() > 0;

// Verificar email
if ($respuesta['dniExiste'] && $email !== "") {
    $sqlEmail = "SELECT * FROM pacientes WHERE email = :email";
    $stmtEmail = $con->prepare($sqlEmail);
    $stmtEmail->bindParam(":email", $email);
    $stmtEmail->execute();

    if ($stmtEmail->rowCount() > 0) {
        $paciente = $stmtEmail->fetch(PDO::FETCH_ASSOC);
        $respuesta['emailExiste'] = true;
        $respuesta['pseguridad'] = $paciente['pseguridad'];
    } else {
        $respuesta['emailExiste'] = false;
    }
}

// Verificar rseguridad
if ($respuesta['dniExiste'] && $rseguridad !== "") {
    $sqlRespuesta = "SELECT * FROM pacientes WHERE dni = :dni AND email = :email";
    $stmtRespuesta = $con->prepare($sqlRespuesta);
    $stmtRespuesta->bindParam(":dni", $dni);
    $stmtRespuesta->bindParam(":email", $email);
    $stmtRespuesta->execute();

    if ($stmtRespuesta->rowCount() > 0) {
        $paciente = $stmtRespuesta->fetch(PDO::FETCH_ASSOC);
        $rseguridadBD = $paciente['rseguridad'];
        if (password_verify($rseguridad, $rseguridadBD)) {
            $respuesta['rseguridadValida'] = true;
        } else {
            $respuesta['rseguridadValida'] = false;
        }
    } else {
        $respuesta['rseguridadValida'] = false;
    }
}

// Actualizar contraseña
if ($new_pass !== "") {
    $new_pass_hashed = password_hash($new_pass, PASSWORD_BCRYPT);
    $sqlUpdate = "UPDATE pacientes SET password = :new_pass WHERE dni = :dni";
    $stmtUpdate = $con->prepare($sqlUpdate);
    $stmtUpdate->bindParam(":new_pass", $new_pass_hashed);
    $stmtUpdate->bindParam(":dni", $dni);
    $stmtUpdate->execute();
    $respuesta['cambioExitoso'] = $stmtUpdate->rowCount() > 0;
}

$con = null;

echo json_encode($respuesta);
