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
$aplicativo = 'Admin';
$ruta_aplicativo = './admin/index.php';

$camposFormulario = [
    'id_paciente', 'dni', 'nombre', 'apellido', 'telefono', 'email', 'fecha_nacimiento', 'fecha_ultima_consulta', 'fecha_proxima_consulta', 'id_genero',
    'altura', 'peso', 'observaciones', 'nombre_actual', 'apellido_actual', 'telefono_actual', 'email_actual', 'fecha_nacimiento_actual', 'fecha_ultima_consulta_actual', 'fecha_proxima_consulta_actual', 'id_genero_actual',
    'altura_actual', 'peso_actual', 'observaciones_actual', 'pseguridad', 'movimiento'
];
$datos = [];
foreach ($camposFormulario as $campo) {
    $datos[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : null;
}
$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
$rseguridad = isset($_POST['rseguridad']) ? password_hash($_POST['rseguridad'], PASSWORD_BCRYPT) : '';

$actualizacionCampos = [
    'nombre', 'apellido', 'telefono', 'email', 'fecha_nacimiento', 'fecha_ultima_consulta', 'fecha_proxima_consulta', 'id_genero',
    'altura', 'peso', 'observaciones'
];
switch ($datos['movimiento']) {
    case 'A':
        $tipo_auditoria = 'Alta';
        $sqlInsert = "INSERT INTO pacientes (dni, observaciones, nombre, apellido, fecha_nacimiento, fecha_ultima_consulta, fecha_proxima_consulta, id_genero, telefono, email, peso, altura, password, pseguridad, rseguridad, movimiento, usuario) 
        VALUES ('{$datos['dni']}', '{$datos['observaciones']}', '{$datos['nombre']}', '{$datos['apellido']}', " . ($datos['fecha_nacimiento'] ? "'{$datos['fecha_nacimiento']}'" : 'NULL') . ", " . ($datos['fecha_ultima_consulta'] ? "'{$datos['fecha_ultima_consulta']}'" : 'NULL') . ", " . ($datos['fecha_proxima_consulta'] ? "'{$datos['fecha_proxima_consulta']}'" : 'NULL') . ", '{$datos['id_genero']}', '{$datos['telefono']}', '{$datos['email']}', '{$datos['peso']}', '{$datos['altura']}', '$password', '{$datos['pseguridad']}', '$rseguridad', '{$datos['movimiento']}', '$usuario')";
        break;
    case 'U':
        $tipo_auditoria = 'Actualizacion';
        $sqlUpdate = "UPDATE pacientes SET nombre = '{$datos['nombre']}', apellido = '{$datos['apellido']}', id_genero = '{$datos['id_genero']}', fecha_nacimiento = " . ($datos['fecha_nacimiento'] ? "'{$datos['fecha_nacimiento']}'" : 'NULL') . ", fecha_ultima_consulta = " . ($datos['fecha_ultima_consulta'] ? "'{$datos['fecha_ultima_consulta']}'" : 'NULL') . ", fecha_proxima_consulta = " . ($datos['fecha_proxima_consulta'] ? "'{$datos['fecha_proxima_consulta']}'" : 'NULL') . ", telefono = '{$datos['telefono']}', email = '{$datos['email']}', altura = '{$datos['altura']}', peso = '{$datos['peso']}', observaciones = '{$datos['observaciones']}', usuario = '$usuario', movimiento = 'M'
        WHERE id_paciente = '{$datos['id_paciente']}'";
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
        $actualizacionString .= "id_paciente: {$datos['id_paciente']}, ";
        $actualizacionString .= "dni: {$datos['dni']}, ";
        $actualizacionString .= "pseguridad: {$datos['pseguridad']}, ";
        foreach ($actualizacionCampos as $campo) {
            $campoActual = "{$campo}_actual";
            $actualizacionString .= "$campo: $datos[$campoActual], ";
        }
        $actualizacionString = rtrim($actualizacionString, ', ');
        $sqlDelete = "DELETE FROM pacientes WHERE id_paciente = '{$datos['id_paciente']}'";
        break;
}
$dni = '';
if (isset($sqlUpdate) || isset($sqlDelete)) {
    $consulta = "SELECT * FROM v_pacientes WHERE id_paciente = '{$datos['id_paciente']}'";
    $resultConsulta = mysqli_query($con, $consulta);
    if ($resultConsulta) {
        $dni = mysqli_fetch_assoc($resultConsulta)['dni'];
    } else {
        echo "Error al obtener el campo";
    }
}

if (isset($sqlInsert) || isset($sqlUpdate) || isset($sqlDelete)) {
    if (isset($sqlInsert)) {
        mysqli_query($con, $sqlInsert);
        $dni = "{$datos['dni']}";
    }
    if (isset($sqlUpdate)) {
        mysqli_query($con, $sqlUpdate);
    }
    if (isset($sqlDelete)) {
        mysqli_query($con, $sqlDelete);
    }
    $id_paciente = "{$datos['id_paciente']}" != null ? "{$datos['id_paciente']}" : obtenerID();
    $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES ('$tipo_auditoria', '$id_paciente', '$dni', '$aplicativo', '$ruta_aplicativo', '$actualizacionString', '$usuario', '$ip_cliente', '$sistema_operativo', '$navegador')";
    mysqli_query($con, $sqlAudita);
    if (isset($sqlInsert)) {
        $_SESSION['swal_message'] = [
            'icon' => 'success',
            'title' => 'Registro Exitoso',
            'text' => '¡Te has registrado exitosamente!',
            'confirmButtonText' => 'OK',
        ];
    }
    if (isset($sqlUpdate)) {
        $_SESSION['swal_message'] = [
            'icon' => 'success',
            'title' => 'Actualizacion Exitosa',
            'text' => '¡Has modificado el registro exitosamente!',
            'confirmButtonText' => 'OK',
        ];
    }
    if (isset($sqlDelete)) {
        $_SESSION['swal_message'] = [
            'icon' => 'success',
            'title' => 'Borrado Exitoso',
            'text' => '¡Has borrado el registro exitosamente!',
            'confirmButtonText' => 'OK',
        ];
    }
    header('Location: ./index.php');
} else {
    echo "Error al guardar los datos: " . mysqli_error($con);
}
