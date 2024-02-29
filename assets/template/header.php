<?php include('../assets/conexion/conexion.php');
session_start();
if (!isset($_SESSION['dni'])) {
    header("location:../index.php");
}
$dni = $_SESSION['dni'];
// Consulta SQL Tags
$sqlTags = "SELECT tag_combinado FROM v_usuarios WHERE dni = $dni";
$resultados_tags = $con->query($sqlTags);
// Inicializamos el array para almacenar los resultados
$tagsUsuario = array();
// Recorremos los resultados y almacenamos cada tag_combinado en el array
while ($row = $resultados_tags->fetch_assoc()) {
    $tagsUsuario[] = $row['tag_combinado'];
}
?>
<script>
    let tagsUsuario = <?php echo json_encode($tagsUsuario); ?>;
    // Todo este código se ejecutará después de que la página haya cargado completamente
    document.addEventListener('DOMContentLoaded', function() {
        // Recuperamos el array desde PHP y lo convertimos a un array de JavaScript
        // Función para comprobar si el tag del botón está en el array
        function verificarTagEnArray(tag) {
            return tagsUsuario.includes(tag) || tagsUsuario.includes("SUALL");
        }
        // Obtener todos los botones con la clase "tagged-button"
        let taggedButtons = document.querySelectorAll(".taggedButton");
        // Recorrer cada botón y verificar si debe mostrarse o no
        taggedButtons.forEach(function(button) {
            let tagDelBoton = button.getAttribute("data-bs-tag");
            if (verificarTagEnArray(tagDelBoton)) {
                button.style.display = "inline-block"; // Si el tag está en el array, muestra el botón
            } else {
                button.style.display = "none"; // Si el tag NO está en el array, oculta el botón
                button.removeAttribute("data-bs-url");
            }
        });
    });
</script>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../assets/img/cic.svg" type="image/x-icon">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="../assets/datatables/dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos.css">
    <script type="text/javascript" charset="utf8" src="../assets/datatables/dataTables.min.js"></script>
    <!-- Cropper -->
    <link rel="stylesheet" href="../assets/cropper/cropper.min.css">
</head>
<script>
    let dni_mensaje = <?php echo $dni ?>;
    let intervalId = setInterval(function() {
        $(document).ready(function() {
            $.ajax({
                url: "../assets/template/act_mensajes.php",
                type: "POST",
                data: {
                    dni: dni_mensaje
                },
                dataType: "json",
                success: function(response) {
                    let mensajesNuevos = response[0]['mensajes_nuevos'];
                    let spanBadge = document.getElementById('spanBadge')
                    if (mensajesNuevos !== 0) {
                        spanBadge.innerHTML = mensajesNuevos
                    } else {
                        spanBadge.innerHTML = ''
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                },
            });
        });
    }, 5000);
</script>

<body class="fondo-gradient">
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient navbar-fixed-top ">
            <div class="container-fluid">
                <a class="navbar-brand" href="../gestion/index.php"><img src="../assets/img/cic.png" alt=""></a>
                <a class="navbar-brand fs-5" href="../gestion/index.php"> Sistema de Gestión
                    <br><strong>Personal</strong></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" style="cursor:pointer" onclick="openNewWindow('../calendario/index.php', 'Calendario')"><i class="fa-regular fa-calendar fa-lg"></i> Calendario |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active position-relative" style="cursor:pointer" onclick="openNewWindow('../mensajeria/index.php', 'Mensajeria')">
                                <i class="fa-regular fa-comments fa-lg"></i> Mensajería
                                <span class="position-absolute top-5 start-90 translate-middle badge rounded-pill bg-danger" id="spanBadge"></span> |
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="../links/index.php"><i class="fa-solid fa-link fa-lg"></i> Links Utiles |</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-1 active" href="../notas/index.php"><i class="fa-regular fa-book fa-lg"></i> Notas |</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link mx-1 dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aplicativos</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="../gestion/index.php">Gestión CIC</a></li>
                                <li><a class="dropdown-item" href="../listado_general/listadogeneral.php">Gestión CIC
                                        TODOS</a></li>
                                <li><a class="dropdown-item" href="../escalafon_fascenso/fraccion.php">Gestión
                                        Ascensos</a></li>
                                <li><a class="dropdown-item" href="../pases_futuros/pasesactualfuturo.php">Gestión
                                        Pases</a></li>
                                <li><a class="dropdown-item" href="../informe/index.php">Estadisticas</a></li>
                                <li><a class="dropdown-item" href="../escalafon/escalafon.php">Escalafon</a></li>
                                <li><a class="dropdown-item" href="../busqueda_pertenencia/busqueda_pertenencia.php">Busqueda pertenencia</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="../destinos_maestro/index.php">Maestro
                                        Destinos</a></li>
                                <li><a class="dropdown-item" href="../auditorias/index.php">Auditoria</a></li>
                                <li><a class="dropdown-item" href="../admin/index.php">Permisos usuarios</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo $_SESSION['dni']; ?>">
                                <i class="fa-solid fa-user fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item disabled">
                                        <?php echo $_SESSION['dni']; ?>
                                    </a></li>
                                <li><a class="dropdown-item" href="../registro/servidor/login/logout.php">Salir del
                                        sistema</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <script>
        function openNewWindow(urli, windowNamei) {
            const url = urli; // Reemplaza con la URL que desees
            const windowName = windowNamei;
            const windowFeatures = 'width=1024,height=720,scrollbars=yes';
            const nuevaVentana = window.open(url, windowName, windowFeatures);
        }
    </script>