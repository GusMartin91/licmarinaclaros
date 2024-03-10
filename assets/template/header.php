<?php include('./assets/conexion/conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="./assets/img/vegetables.png" type="image/x-icon">
    <!-- DataTables CSS-->
    <link rel="stylesheet" type="text/css" href="./assets/datatables/dataTables.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" type="text/css" href="./assets/fontawesome/css/all.min.css">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="./assets/bootstrap-5.3.0/css/bootstrap.min.css">
    <!-- Cropper CSS-->
    <link rel="stylesheet" href="./assets/cropper/cropper.min.css">
    <!-- Calendly CSS -->
    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="./assets/css/estilo_landing.css">
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="navbar-logo" src="assets/img/logo_nutricion.jpeg" alt="Logo" style="width: 100px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a id="nav_item" class="nav-link" href="#sobre_mi">Sobre mi</a>
                        </li>
                        <li class="nav-item">
                            <a id="nav_item" class="nav-link" href="#servicios">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a id="nav_item" class="nav-link" href="#testimonios">Testimonios</a>
                        </li>
                        <li class="nav-item">
                            <a href="" id="nav_item" class="nav-link" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/licmarinaclaros/30min?hide_gdpr_banner=1'});return false;">Reservar Cita</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_registro" class="nav-link">| <i class="fa-solid fa-user-plus"></i> Registrarse</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-bs-toggle="modal_login" data-bs-target="#modal_login" class="nav-link">| <i class="fa-sharp fa-solid fa-lock"></i> Iniciar sesion</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </header>