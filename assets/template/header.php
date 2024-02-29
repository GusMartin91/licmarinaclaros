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
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient navbar-fixed-top ">
            <div class="container-fluid">
                <a class="navbar-brand" href="./index.php"><img src="./assets/img/vegetables.png" alt="vegetables food healthy fruit nutrition"></a>
                <a class="navbar-brand fs-5" href="./index.php"> Lic. en Nutricion
                    <br><strong>Marina Claros</strong></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" style="cursor:pointer" onclick="openNewWindow('../calendario/index.php', 'Calendario')"><i class="fa-regular fa-calendar fa-lg"></i> Solicitar Turno |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active position-relative" style="cursor:pointer" onclick="openNewWindow('../mensajeria/index.php', 'Mensajeria')">
                                <i class="fa-regular fa-comments fa-lg"></i> Mensajería
                                <span class="position-absolute top-5 start-90 translate-middle badge rounded-pill bg-danger" id="spanBadge"></span> |
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="./links/index.php"><i class="fa-solid fa-link fa-lg"></i> Links Utiles |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="./notas/index.php"><i class="fa-regular fa-book fa-lg"></i> Notas |</a>
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
                                        <?php // echo $_SESSION['dni']; ?>
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