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
        $tipoAuditoria = 'Alta';
        $sqlInsert = "INSERT INTO pacientes (dni, observaciones, nombre, apellido, fecha_nacimiento, fecha_ultima_consulta, fecha_proxima_consulta, id_genero, telefono, email, peso, altura, password, pseguridad, rseguridad, movimiento, usuario)
            VALUES (:dni, :observaciones, :nombre, :apellido, :fecha_nacimiento, :fecha_ultima_consulta, :fecha_proxima_consulta, :id_genero, :telefono, :email, :peso, :altura, :password, :pseguridad, :rseguridad, :movimiento, :usuario)";
        $stmtInsert = $con->prepare($sqlInsert);
        $stmtInsert->bindParam(':dni', $datos['dni']);
        $stmtInsert->bindParam(':observaciones', $datos['observaciones']);
        $stmtInsert->bindParam(':nombre', $datos['nombre']);
        $stmtInsert->bindParam(':apellido', $datos['apellido']);
        $stmtInsert->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
        $stmtInsert->bindParam(':fecha_ultima_consulta', $datos['fecha_ultima_consulta']);
        $stmtInsert->bindParam(':fecha_proxima_consulta', $datos['fecha_proxima_consulta']);
        $stmtInsert->bindParam(':id_genero', $datos['id_genero']);
        $stmtInsert->bindParam(':telefono', $datos['telefono']);
        $stmtInsert->bindParam(':email', $datos['email']);
        $stmtInsert->bindParam(':peso', $datos['peso']);
        $stmtInsert->bindParam(':altura', $datos['altura']);
        $stmtInsert->bindParam(':password', $password);
        $stmtInsert->bindParam(':pseguridad', $datos['pseguridad']);
        $stmtInsert->bindParam(':rseguridad', $rseguridad);
        $stmtInsert->bindParam(':movimiento', $datos['movimiento']);
        $stmtInsert->bindParam(':usuario', $usuario);
        break;

    case 'U':
        $tipoAuditoria = 'Actualizacion';
        $sqlUpdate = "UPDATE pacientes SET nombre = :nombre, apellido = :apellido, id_genero = :id_genero, fecha_nacimiento = :fecha_nacimiento, fecha_ultima_consulta = :fecha_ultima_consulta, fecha_proxima_consulta = :fecha_proxima_consulta, telefono = :telefono, email = :email, altura = :altura, peso = :peso, observaciones = :observaciones, usuario = :usuario, movimiento = 'M'
                       WHERE id_paciente = :id_paciente";
        $stmtUpdate = $con->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':id_paciente', $datos['id_paciente']);
        $stmtUpdate->bindParam(':nombre', $datos['nombre']);
        $stmtUpdate->bindParam(':apellido', $datos['apellido']);
        $stmtUpdate->bindParam(':id_genero', $datos['id_genero']);
        $stmtUpdate->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
        $stmtUpdate->bindParam(':fecha_ultima_consulta', $datos['fecha_ultima_consulta']);
        $stmtUpdate->bindParam(':fecha_proxima_consulta', $datos['fecha_proxima_consulta']);
        $stmtUpdate->bindParam(':telefono', $datos['telefono']);
        $stmtUpdate->bindParam(':email', $datos['email']);
        $stmtUpdate->bindParam(':altura', $datos['altura']);
        $stmtUpdate->bindParam(':peso', $datos['peso']);
        $stmtUpdate->bindParam(':observaciones', $datos['observaciones']);
        $stmtUpdate->bindParam(':usuario', $usuario);

        foreach ($actualizacionCampos as $campo) {
            $campoActual = "{$campo}_actual";
            if ($datos[$campo] != $datos[$campoActual]) {
                $actualizacionString .= "$campo = $datos[$campoActual], ";
            }
        }

        $actualizacionString = rtrim($actualizacionString, ', ');
        break;
    case 'D':
        $tipoAuditoria = 'Eliminacion Definitiva';
        $actualizacionString .= "id_paciente: {$datos['id_paciente']}, ";
        $actualizacionString .= "dni: {$datos['dni']}, ";
        $actualizacionString .= "pseguridad: {$datos['pseguridad']}, ";
        foreach ($actualizacionCampos as $campo) {
            $campoActual = "{$campo}_actual";
            if ($datos[$campo] != $datos[$campoActual]) {
                $actualizacionString .= "$campo: $datos[$campoActual], ";
            }
        }
        $actualizacionString = rtrim($actualizacionString, ', ');

        $sqlDelete = "DELETE FROM pacientes WHERE id_paciente = :id_paciente";
        $stmtDelete = $con->prepare($sqlDelete);
        $stmtDelete->bindParam(':id_paciente', $datos['id_paciente']);
        break;
}
$dni = '';

if (isset($sqlUpdate) || isset($sqlDelete)) {
    $sqlConsulta = "SELECT * FROM v_pacientes WHERE id_paciente = :id_paciente";
    $stmtConsulta = $con->prepare($sqlConsulta);
    $stmtConsulta->bindParam(':id_paciente', $datos['id_paciente']);
    $stmtConsulta->execute();

    if ($stmtConsulta->rowCount() > 0) {
        $resultadoConsulta = $stmtConsulta->fetch(PDO::FETCH_ASSOC);
        $dni = $resultadoConsulta['dni'];
    } else {
        echo "Error al obtener el campo";
    }
}

if (isset($sqlInsert) || isset($sqlUpdate) || isset($sqlDelete)) {
    try {
        $con->beginTransaction();
        if (isset($sqlInsert)) {
            $stmtInsert->execute();
            $dni = "{$datos['dni']}";
        }

        if (isset($sqlUpdate)) {
            $stmtUpdate->execute();
        }

        if (isset($sqlDelete)) {
            $stmtDelete->execute();
        }

        $idPaciente = "{$datos['id_paciente']}" != null ? "{$datos['id_paciente']}" : obtenerID($con);

        $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser)
                  VALUES (:tipo_auditoria, :id_modificado, :dni_modificado, :aplicativo, :ruta_aplicativo, :ultima_actualizacion, :usuario, :ip_cliente, :sistema_operativo, :browser)";
        $stmtAudita = $con->prepare($sqlAudita);
        $stmtAudita->bindParam(':tipo_auditoria', $tipoAuditoria);
        $stmtAudita->bindParam(':id_modificado', $idPaciente);
        $stmtAudita->bindParam(':dni_modificado', $dni);
        $stmtAudita->bindParam(':aplicativo', $aplicativo);
        $stmtAudita->bindParam(':ruta_aplicativo', $ruta_aplicativo);
        $stmtAudita->bindParam(':ultima_actualizacion', $actualizacionString);
        $stmtAudita->bindParam(':usuario', $usuario);
        $stmtAudita->bindParam(':ip_cliente', $ip_cliente);
        $stmtAudita->bindParam(':sistema_operativo', $sistema_operativo);
        $stmtAudita->bindParam(':browser', $navegador);
        $stmtAudita->execute();

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
} else {
    $errorInfo = $con->errorInfo();
    echo "Error al guardar los datos: " . $errorInfo[2];
}
