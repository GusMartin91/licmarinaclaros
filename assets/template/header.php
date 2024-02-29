<?php include('./assets/conexion/conexion.php');
// session_start();
// if (!isset($_SESSION['dni'])) {
//     header("location:../index.php");
// }
// $dni = $_SESSION['dni'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="./assets/img/vegetables.png" type="image/x-icon">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="./assets/datatables/dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="./assets/fontawesome/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="./assets/bootstrap-5.3.0/css/bootstrap.min.css">
    <!-- Cropper -->
    <link rel="stylesheet" href="./assets/cropper/cropper.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="./assets/css/estilos.css">
</head>

<body>
<header class="header">
    <nav class="navbar navbar-expand-lg bg-gradient navbar-fixed-top ">
        <div class="container-fluid justify-content-center">
            <div class="logo">
                <a href="./index.php"><img style="max-height: 150px;" src="./assets/img/logo_nutricion.jpeg" alt="vegetables food healthy fruit nutrition"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" style="cursor:pointer"> Agendar Turno |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active position-relative" style="cursor:pointer">
                                Sobre mí |
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="./links/index.php"> Links Utiles |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="./notas/index.php"> Notas |</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link mx-1 dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aplicativos</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="./gestion/index.php">Gestión CIC</a></li>
                                <li><a class="dropdown-item" href="./listado_general/listadogeneral.php">Gestión CIC
                                        TODOS</a></li>
                                <li><a class="dropdown-item" href="./escalafon_fascenso/fraccion.php">Gestión
                                        Ascensos</a></li>
                                <li><a class="dropdown-item" href="./pases_futuros/pasesactualfuturo.php">Gestión
                                        Pases</a></li>
                                <li><a class="dropdown-item" href="./informe/index.php">Estadisticas</a></li>
                                <li><a class="dropdown-item" href="./escalafon/escalafon.php">Escalafon</a></li>
                                <li><a class="dropdown-item" href="./busqueda_pertenencia/busqueda_pertenencia.php">Busqueda pertenencia</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="./destinos_maestro/index.php">Maestro
                                        Destinos</a></li>
                                <li><a class="dropdown-item" href="./auditorias/index.php">Auditoria</a></li>
                                <li><a class="dropdown-item" href="./admin/index.php">Permisos usuarios</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo $_SESSION['dni']; ?>">
                                <i class="fa-solid fa-user fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item disabled">
                                        <?php // echo $_SESSION['dni']; 
                                        ?>
                                    </a></li>
                                <li><a class="dropdown-item" href="./registro/servidor/login/logout.php">Salir del
                                        sistema</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>