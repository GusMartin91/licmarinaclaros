<?php
include '../assets/conexion/conexion.php';

session_start();
$usuario = NULL;
if (isset($_SESSION['dni'])) {
    $usuario = $_SESSION['dni'];
}
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
$actualizacionString = '';
$aplicativo = 'Historial Consulta';
$ruta_aplicativo = './paciente/index.php';


$datos = json_decode(file_get_contents('php://input'), true);

$id_consulta = isset($datos['id_consulta']) ? $datos['id_consulta'] : "";
$observacion_actual = isset($datos['observacion_actual']) ? $datos['observacion_actual'] : "";
$observacion_nueva = isset($datos['observacion_nueva']) ? $datos['observacion_nueva'] : "";
$actualizacionString = 'Observacion modificada: ' . $observacion_actual;

if (!isset($id_consulta) || !isset($observacion_nueva)) {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar las observaciones. Faltan datos']);
    exit;
}

$tipo_auditoria = 'Actualizacion';
$sqlUpdate = "UPDATE historial_consultas SET observaciones_paciente = '$observacion_nueva', usuario = '$usuario', movimiento = 'M'
        WHERE id_consulta = '$id_consulta'";

$sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES ('$tipo_auditoria', '$id_consulta', '$usuario', '$aplicativo', '$ruta_aplicativo', '$actualizacionString', '$usuario', '$ip_cliente', '$sistema_operativo', '$navegador')";

if ($observacion_actual !== $observacion_nueva) {
    mysqli_query($con, $sqlUpdate);
    mysqli_query($con, $sqlAudita);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la observación, no se modifico la observacion.']);
}
