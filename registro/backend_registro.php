<?php
include '../assets/conexion/conexion.php';

session_start();

function obtenerID()
{
    global $con;
    return mysqli_insert_id($con);
}
function obtenerIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function obtenerSistemaOperativo($userAgent)
{
    if (strpos($userAgent, 'Windows') !== false) {
        return 'Windows';
    } elseif (strpos($userAgent, 'Android') !== false) {
        return 'Android';
    } elseif (strpos($userAgent, 'Linux') !== false) {
        return 'Linux';
    } elseif (strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS X') !== false) {
        return 'macOS';
    } elseif (strpos($userAgent, 'iOS') !== false || strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
        return 'iOS';
    } else {
        return 'Otro';
    }
}

function obtenerNavegador($userAgent)
{
    $infoNavegador = get_browser(null, true);
    return $infoNavegador['browser'];
}

$ip_cliente = obtenerIP();
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$sistema_operativo = obtenerSistemaOperativo($userAgent);
$navegador = obtenerNavegador($userAgent);

$camposFormulario = [
    'dni', 'nombre', 'apellido', 'telefono', 'email', 'fecha_nacimiento', 'genero',
    'altura', 'peso', 'observaciones', 'pseguridad', ''
];
$datos = [];
foreach ($camposFormulario as $campo) {
    $datos[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : null;
}

$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$rseguridad = password_hash($_POST['rseguridad'], PASSWORD_BCRYPT);
$tipo_auditoria = 'Registro';
$sqlInsert = "INSERT INTO pacientes (dni, observaciones, nombre, apellido, fecha_nacimiento, genero, telefono, email, peso, altura, password, pseguridad, rseguridad) 
        VALUES ('{$datos['dni']}', '{$datos['observaciones']}', '{$datos['nombre']}', '{$datos['apellido']}', " . ($datos['fecha_nacimiento'] ? "'{$datos['fecha_nacimiento']}'" : 'NULL') . ", '{$datos['genero']}', '{$datos['telefono']}', '{$datos['email']}', '{$datos['peso']}', '{$datos['altura']}', '$password', '{$datos['pseguridad']}', '$rseguridad')";

if (mysqli_query($con, $sqlInsert)) {
    $id_paciente = obtenerID();
    $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, ip_cliente, sistema_operativo, browser) VALUES ('$tipo_auditoria', '$id_paciente', '{$datos['dni']}', '$ip_cliente', '$sistema_operativo', '$navegador')";
    mysqli_query($con, $sqlAudita);
    header("Location: ../index.php");
} else {
    echo "Error al guardar los datos: " . mysqli_error($con);
}

?>
<script src="../assets/dataTables/datatables.min.js"></script>