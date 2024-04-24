<?php

include '../assets/conexion/conexion.php';

$email = isset($_POST['email']) ? $_POST['email'] : "";
$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];

// Verificar email
$sqlEmail = "SELECT * FROM pacientes WHERE email = :email";
$stmtEmail = $con->prepare($sqlEmail);
$stmtEmail->bindParam(":email", $email);
$stmtEmail->execute();
$respuesta['emailExiste'] = $stmtEmail->rowCount() > 0;

// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = :dni";
$stmtDni = $con->prepare($sqlDni);
$stmtDni->bindParam(":dni", $dni);
$stmtDni->execute();
$respuesta['dniExiste'] = $stmtDni->rowCount() > 0;

echo json_encode($respuesta);
