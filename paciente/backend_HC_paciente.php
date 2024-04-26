<?php
include '../assets/conexion/conexion.php';

session_start();
$usuario = NULL;
if (isset($_SESSION['dni'])) {
    $usuario = $_SESSION['dni'];
}

function obtenerID($con)
{
    return $con->lastInsertId();
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
$sqlUpdate = "UPDATE historial_consultas SET observaciones_paciente = :observacion_nueva, usuario = :usuario, movimiento = 'M'
        WHERE id_consulta = :id_consulta";

$sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES (:tipo_auditoria, :id_consulta, :usuario, :aplicativo, :ruta_aplicativo, :actualizacionString, :usuario, :ip_cliente, :sistema_operativo, :navegador)";

try {
    $con->beginTransaction();

    $stmtUpdate = $con->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':observacion_nueva', $observacion_nueva, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
    $stmtUpdate->execute();

    $stmtAudita = $con->prepare($sqlAudita);
    $stmtAudita->bindParam(':tipo_auditoria', $tipo_auditoria, PDO::PARAM_STR);
    $stmtAudita->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
    $stmtAudita->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmtAudita->bindParam(':aplicativo', $aplicativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':ruta_aplicativo', $ruta_aplicativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':actualizacionString', $actualizacionString, PDO::PARAM_STR);
    $stmtAudita->bindParam(':ip_cliente', $ip_cliente, PDO::PARAM_STR);
    $stmtAudita->bindParam(':sistema_operativo', $sistema_operativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':navegador', $navegador, PDO::PARAM_STR);
    $stmtAudita->execute();

    $con->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la observación: ' . $e->getMessage()]);
}
