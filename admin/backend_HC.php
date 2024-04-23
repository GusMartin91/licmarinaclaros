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
$ruta_aplicativo = './admin/index.php';

$camposFormulario = [
    'id_consulta', 'dni_HC', 'fecha_consulta_HC', 'fecha_proxima_consulta_HC', 'peso_HC', 'observaciones_HC', 'fecha_proxima_consulta_HC_actual',
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
        VALUES ('{$datos['dni_HC']}', '{$datos['observaciones_HC']}', " . ($datos['fecha_consulta_HC'] ? "'{$datos['fecha_consulta_HC']}'" : 'NULL') . ", '{$datos['peso_HC']}', '{$datos['movimiento_HC']}', '$usuario')";
        break;
    case 'U':
        $tipo_auditoria = 'Actualizacion';
        $sqlUpdate = "UPDATE historial_consultas SET fecha_consulta = " . ($datos['fecha_consulta_HC'] ? "'{$datos['fecha_consulta_HC']}'" : 'NULL') . ", peso = '{$datos['peso_HC']}', observaciones_nutri = '{$datos['observaciones_HC']}', usuario = '$usuario', movimiento = 'M'
        WHERE id_consulta = '{$datos['id_consulta']}'";
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
        $sqlDelete = "DELETE FROM historial_consultas WHERE id_consulta = '{$datos['id_consulta']}'";
        break;
}
$dni = '';
if (isset($sqlUpdate) || isset($sqlDelete)) {
    $consulta = "SELECT * FROM v_historial_consultas WHERE id_consulta = '{$datos['id_consulta']}'";
    $resultConsulta = mysqli_query($con, $consulta);
    if ($resultConsulta) {
        $dni = mysqli_fetch_assoc($resultConsulta)['dni_paciente'];
    } else {
        echo "Error al obtener el campo";
    }
}

if (isset($sqlInsert) || isset($sqlUpdate) || isset($sqlDelete)) {
    if (isset($sqlInsert)) {
        mysqli_query($con, $sqlInsert);
        $dni = "{$datos['dni_HC']}";
    }
    if (isset($sqlUpdate)) {
        mysqli_query($con, $sqlUpdate);
    }
    if (isset($sqlDelete)) {
        mysqli_query($con, $sqlDelete);
    }
    $id_consulta = "{$datos['id_consulta']}" != null ? "{$datos['id_consulta']}" : obtenerID();
    $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES ('$tipo_auditoria', '$id_consulta', '$dni', '$aplicativo', '$ruta_aplicativo', '$actualizacionString', '$usuario', '$ip_cliente', '$sistema_operativo', '$navegador')";
    mysqli_query($con, $sqlAudita);
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
    header('Location: ./index.php');
} else {
    echo "Error al guardar los datos: " . mysqli_error($con);
}
