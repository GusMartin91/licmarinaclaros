<?php

include '../assets/conexion/conexion.php';

$email = isset($_POST['email']) ? $_POST['email'] : "";
$dni = isset($_POST['dni']) ? $_POST['dni'] : "";

$respuesta = [];

// Verificar email
$sqlEmail = "SELECT * FROM pacientes WHERE email = '$email'";
$resultEmail = mysqli_query($con, $sqlEmail);
$respuesta['emailExiste'] = mysqli_num_rows($resultEmail) > 0;

// Verificar DNI
$sqlDni = "SELECT * FROM pacientes WHERE dni = '$dni'";
$resultDni = mysqli_query($con, $sqlDni);
$respuesta['dniExiste'] = mysqli_num_rows($resultDni) > 0;

echo json_encode($respuesta);
