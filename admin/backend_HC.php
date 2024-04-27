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
$ruta_aplicativo = './admin/index.php';

$camposFormulario = [
    'id_consulta', 'dni_HC', 'fecha_consulta_HC', 'peso_HC', 'observaciones_HC', 'fecha_consulta_HC_actual',
    'peso_HC_actual', 'observaciones_HC_actual', 'movimiento_HC'
];
$datos = [];
foreach ($camposFormulario as $campo) {
    $datos[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : null;
}
$actualizacionCampos = [
    'peso_HC', 'observaciones_HC'
];
switch ($datos['movimiento_HC']) {
    case 'A':
        $tipo_auditoria = 'Alta';
        $sqlInsert = "INSERT INTO historial_consultas (dni_paciente, observaciones_nutri, fecha_consulta, peso, movimiento, usuario) 
        VALUES (:dni_HC, :observaciones_HC, :fecha_consulta_HC, :peso_HC, :movimiento_HC, :usuario)";
        $stmtInsert = $con->prepare($sqlInsert);
        $stmtInsert->bindParam(':dni_HC', $datos['dni_HC']);
        $stmtInsert->bindParam(':observaciones_HC', $datos['observaciones_HC']);
        $stmtInsert->bindParam(':fecha_consulta_HC', $datos['fecha_consulta_HC']);
        $stmtInsert->bindParam(':peso_HC', $datos['peso_HC']);
        $stmtInsert->bindParam(':movimiento_HC', $datos['movimiento_HC']);
        $stmtInsert->bindParam(':usuario', $usuario);
        break;
    case 'U':
        $tipo_auditoria = 'Actualizacion';
        $sqlUpdate = "UPDATE historial_consultas SET fecha_consulta = :fecha_consulta_HC, peso = :peso_HC, observaciones_nutri = :observaciones_HC, usuario = :usuario, movimiento = 'M'
        WHERE id_consulta = :id_consulta";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':fecha_consulta_HC', $datos['fecha_consulta_HC']);
        $stmtUpdate->bindParam(':peso_HC', $datos['peso_HC']);
        $stmtUpdate->bindParam(':observaciones_HC', $datos['observaciones_HC']);
        $stmtUpdate->bindParam(':usuario', $usuario);
        $stmtUpdate->bindParam(':id_consulta', $datos['id_consulta']);

        foreach ($actualizacionCampos as $campo) {
            $campoActual = "{$campo}_actual";
            if ($datos[$campo] != $datos[$campoActual]) {
                $actualizacionString .= "$campo = $datos[$campoActual], ";
            }
        }
        $actualizacionString = rtrim($actualizacionString, ', ');
        break;
    case 'D':
        $tipo_auditoria = 'Eliminacion Definitiva';
        $actualizacionString .= "id_consulta: {$datos['id_consulta']}, ";
        $actualizacionString .= "dni: {$datos['dni_HC']}, ";
        foreach ($actualizacionCampos as $campo) {
            $campoActual = "{$campo}_actual";
            $actualizacionString .= "$campo: $datos[$campoActual], ";
        }
        $actualizacionString = rtrim($actualizacionString, ', ');
        $sqlDelete = "DELETE FROM historial_consultas WHERE id_consulta = :id_consulta";
        $stmtDelete = $con->prepare($sqlDelete);
        $stmtDelete->bindParam(':id_consulta', $datos['id_consulta']);
        break;
}

$dni = '';
if (isset($sqlUpdate) || isset($sqlDelete)) {
    $consulta = "SELECT * FROM v_historial_consulta WHERE id_consulta = :id_consulta";
    $stmtConsulta = $con->prepare($consulta);
    $stmtConsulta->bindParam(':id_consulta', $datos['id_consulta']);
    $stmtConsulta->execute();
    $resultadoConsulta = $stmtConsulta->fetch(PDO::FETCH_ASSOC);
    if ($resultadoConsulta) {
        $dni = $resultadoConsulta['dni_paciente'];
    } else {
        echo "Error al obtener el campo";
    }
}

if (isset($sqlInsert) || isset($sqlUpdate) || isset($sqlDelete)) {
    try {
        $con->beginTransaction();
        if (isset($sqlInsert)) {
            $stmtInsert->execute();
            $dni = $datos['dni_HC'];
        }
        if (isset($sqlUpdate)) {
            $stmtUpdate->execute();
        }
        if (isset($sqlDelete)) {
            $stmtDelete->execute();
        }
        $id_consulta = $datos['id_consulta'] != null ? $datos['id_consulta'] : obtenerID($con);

        $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) 
        VALUES (:tipo_auditoria, :id_modificado, :dni_modificado, :aplicativo, :ruta_aplicativo, :ultima_actualizacion, :usuario, :ip_cliente, :sistema_operativo, :navegador)";
        $stmtAudita = $con->prepare($sqlAudita);
        $stmtAudita->bindParam(':tipo_auditoria', $tipo_auditoria);
        $stmtAudita->bindParam(':id_modificado', $id_consulta);
        $stmtAudita->bindParam(':dni_modificado', $dni);
        $stmtAudita->bindParam(':aplicativo', $aplicativo);
        $stmtAudita->bindParam(':ruta_aplicativo', $ruta_aplicativo);
        $stmtAudita->bindParam(':ultima_actualizacion', $actualizacionString);
        $stmtAudita->bindParam(':usuario', $usuario);
        $stmtAudita->bindParam(':ip_cliente', $ip_cliente);
        $stmtAudita->bindParam(':sistema_operativo', $sistema_operativo);
        $stmtAudita->bindParam(':navegador', $navegador);
        $stmtAudita->execute();

        $_SESSION['pestana'] = 'HC';
        if (isset($sqlInsert)) {
            $_SESSION['swal_message'] = [
                'icon' => 'success',
                'title' => 'Registro de consulta Exitoso',
                'text' => '¡Has registrado la consulta exitosamente!',
                'confirmButtonText' => 'OK',
            ];
        }
        if (isset($sqlUpdate)) {
            $_SESSION['swal_message'] = [
                'icon' => 'success',
                'title' => 'Actualizacion Exitosa',
                'text' => '¡Has modificado la consulta exitosamente!',
                'confirmButtonText' => 'OK',
            ];
        }
        if (isset($sqlDelete)) {
            $_SESSION['swal_message'] = [
                'icon' => 'success',
                'title' => 'Borrado Exitoso',
                'text' => '¡Has borrado la consulta exitosamente!',
                'confirmButtonText' => 'OK',
            ];
        }
        $con->commit();
        header('Location: ./index.php');
    } catch (PDOException $e) {
        $con->rollBack();
        $_SESSION['swal_message'] = [
            'icon' => 'error',
            'title' => 'Error',
            'text' => '¡Error: ' . $e->getMessage() . '!',
            'confirmButtonText' => 'OK',
        ];
        header('Location: ./index.php');
    }
    $con = null;
} else {
    echo "Error al guardar los datos.";
}
