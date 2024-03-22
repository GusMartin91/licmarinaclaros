<?php
include('../assets/conexion/conexion.php');

session_start();
$UrlActual = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;
if ($UrlActual !== 'localhost/licmarinaclaros/home/index.php') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: ../home/index.php');
        exit;
    }
}
if (isset($_SESSION['tiempoInicio']) && (time() - $_SESSION['tiempoInicio'] > 18000)) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?session_expired=true");
    exit;
}

$userFullName;
$userRole;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $userFullName = htmlspecialchars($_SESSION['apellido'] . ", " . $_SESSION['nombre']);
    $userRole = htmlspecialchars($_SESSION['rol']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Esta es una pagina sobre atencion nutricional de la Licenciada Marina Soledad Claros">
    <meta name="keywords" content="Nutrición,Alimentación saludable, Dietética, Dieta equilibrada, Nutricionista, Plan de alimentación, Consejos nutricionales, Salud y nutrición, Control de peso, Nutrición deportiva, Nutrición infantil, Nutrición clínica, Planificación de comidas, Evaluación nutricional, Dieta balanceada, Suplementos nutricionales, Guía alimentaria, Hábitos alimenticios, Bienestar nutricional, Educación nutricional">
    <link rel="shortcut icon" href="../assets/img/vegetables.png" type="image/x-icon">
    <!-- DataTables CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/datatables/dataTables.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/fontawesome/css/all.min.css">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-5.3.0/css/bootstrap.min.css">
    <!-- Cropper CSS-->
    <link rel="stylesheet" href="../assets/cropper/cropper.min.css">
    <!-- Calendly CSS -->
    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/css/estilo_landing.css">
</head>

<body>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $userFullName = htmlspecialchars($_SESSION['apellido'] . ", " . $_SESSION['nombre']);
        $userRole = htmlspecialchars($_SESSION['rol']);
    ?>
        <input type="hidden" name="usuario_hidden" id="usuario_hidden_admin" value="<?php echo $userFullName . " - " . $userRole; ?>">
    <?php }
    ?>
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="../home/index.php">
                    <img class="navbar-logo" src="../assets/img/logo_nutricion.jpeg" alt="Logo" style="width: 100px;">
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
                            <a href="#" id="boton_registrarse" data-bs-toggle="modal" data-bs-target="#modal_registro" class="nav-link">| <i class="fa-solid fa-user-plus"></i> Registrarse</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" id="boton_iniciar_sesion" data-bs-toggle="modal" data-bs-target="#modal_login" class="nav-link">| <i class="fa-solid fa-key"></i> Iniciar sesion</a>
                            <a href="#" id="boton_cerrar_sesion" class="nav-link" hidden>| <i class="fa-sharp fa-solid fa-lock"></i> Cerrar sesion</a>
                        </li>
                        <li class="nav-item" id="div_boton_admin">
                            <?php if (isset($_SESSION['rol'])) { ?>
                                <?php if ($_SESSION['rol'] == "admin") { ?>
                                    <a href="../admin/index.php" id="boton_admin" class="nav-link">| <i class="fa-solid fa-gear"></i> Panel Admin</a>
                                <?php } elseif ($_SESSION['rol'] == "paciente") { ?>
                                    <a href="../paciente/index.php" id="boton_paciente" class="nav-link">| <i class="fa-solid fa-user"></i> Panel Paciente</a>
                                <?php } ?>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>