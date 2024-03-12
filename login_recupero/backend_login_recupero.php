<?php

include '../assets/conexion/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$dni = isset($data['dni']) ? $data['dni'] : "";
$email = isset($data['email']) ? $data['email'] : "";
$rseguridad = isset($data['rseguridad']) ? $data['rseguridad'] : "";
$new_pass = isset($data['new_pass']) ? $data['new_pass'] : "";

$respuesta = [];

// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = '$dni'";
$resultDni = mysqli_query($con, $sqlDni);
$respuesta['dniExiste'] = mysqli_num_rows($resultDni) > 0;

// Verificar email
if ($respuesta['dniExiste'] && $email !== "") {
    $sqlEmail = "SELECT * FROM pacientes WHERE email = '$email'";
    $resultEmail = mysqli_query($con, $sqlEmail);

    if (mysqli_num_rows($resultEmail) > 0) {
        $paciente = mysqli_fetch_assoc($resultEmail);
        $respuesta['emailExiste'] = true;
        $respuesta['pseguridad'] = $paciente['pseguridad'];
    } else {
        $respuesta['emailExiste'] = false;
    }
}

// Verificar rseguridad
if ($respuesta['dniExiste'] && $rseguridad !== "") {
    $sqlRespuesta = "SELECT * FROM pacientes WHERE dni = '$dni' AND email = '$email'";
    $resultRespuesta = mysqli_query($con, $sqlRespuesta);

    if (mysqli_num_rows($resultRespuesta) > 0) {
        $paciente = mysqli_fetch_assoc($resultRespuesta);
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

//actualizar contraseña
if ($new_pass !== "") {
    $new_pass_hashed = password_hash($new_pass, PASSWORD_BCRYPT);
    $sqlInsert = "UPDATE pacientes SET password = '$new_pass_hashed' WHERE dni = '$dni'";
    mysqli_query($con, $sqlInsert);
    $respuesta['cambioExitoso'] = true;
}


echo json_encode($respuesta);
