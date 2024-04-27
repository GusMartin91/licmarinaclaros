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

try {
    $con->beginTransaction();

    $ip_cliente = obtenerIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $sistema_operativo = obtenerSistemaOperativo($userAgent);
    $navegador = obtenerNavegador($userAgent);

    $camposFormulario = [
        'dni', 'nombre', 'apellido', 'telefono', 'email', 'fecha_nacimiento', 'fecha_proxima_consulta', 'id_genero',
        'altura', 'peso', 'observaciones', 'pseguridad', ''
    ];
    $datos = [];
    foreach ($camposFormulario as $campo) {
        $datos[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : null;
    }

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $rseguridad = password_hash($_POST['rseguridad'], PASSWORD_BCRYPT);
    $tipo_auditoria = 'Registro';

    $sqlInsert = "INSERT INTO pacientes (dni, observaciones, nombre, apellido, fecha_nacimiento, fecha_proxima_consulta, id_genero, telefono, email, peso, altura, password, pseguridad, rseguridad) 
            VALUES (:dni, :observaciones, :nombre, :apellido, :fecha_nacimiento, :fecha_proxima_consulta, :id_genero, :telefono, :email, :peso, :altura, :password, :pseguridad, :rseguridad)";

    $stmtInsert = $con->prepare($sqlInsert);
    $stmtInsert->bindParam(":dni", $datos['dni']);
    $stmtInsert->bindParam(":observaciones", $datos['observaciones']);
    $stmtInsert->bindParam(":nombre", $datos['nombre']);
    $stmtInsert->bindParam(":apellido", $datos['apellido']);
    $fecha_nacimiento = $datos['fecha_nacimiento'] ? $datos['fecha_nacimiento'] : null;
    $stmtInsert->bindParam(":fecha_nacimiento", $fecha_nacimiento);
    $fecha_proxima_consulta = $datos['fecha_proxima_consulta'] ? $datos['fecha_proxima_consulta'] : null;
    $stmtInsert->bindParam(":fecha_proxima_consulta", $fecha_proxima_consulta);
    $stmtInsert->bindParam(":id_genero", $datos['id_genero']);
    $stmtInsert->bindParam(":telefono", $datos['telefono']);
    $stmtInsert->bindParam(":email", $datos['email']);
    $stmtInsert->bindParam(":peso", $datos['peso']);
    $stmtInsert->bindParam(":altura", $datos['altura']);
    $stmtInsert->bindParam(":password", $password);
    $stmtInsert->bindParam(":pseguridad", $datos['pseguridad']);
    $stmtInsert->bindParam(":rseguridad", $rseguridad);

    if ($stmtInsert->execute()) {
        $id_paciente = obtenerID($con);

        $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, usuario, ip_cliente, sistema_operativo, browser) VALUES (:tipo_auditoria, :id_paciente, :dni, :usuario, :ip_cliente, :sistema_operativo, :navegador)";
        $stmtAudita = $con->prepare($sqlAudita);
        $stmtAudita->bindParam(":tipo_auditoria", $tipo_auditoria);
        $stmtAudita->bindParam(":id_paciente", $id_paciente);
        $stmtAudita->bindParam(":dni", $datos['dni']);
        $usuario = $usuario != NULL ? $usuario : $datos['dni'];
        $stmtAudita->bindParam(":usuario", $usuario);
        $stmtAudita->bindParam(":ip_cliente", $ip_cliente);
        $stmtAudita->bindParam(":sistema_operativo", $sistema_operativo);
        $stmtAudita->bindParam(":navegador", $navegador);
        $stmtAudita->execute();

        $_SESSION['swal_message'] = [
            'icon' => 'success',
            'title' => 'Registro Exitoso',
            'text' => '¡Te has registrado exitosamente!',
            'confirmButtonText' => 'OK',
        ];
        $con->commit();
        header('Location: ../home/index.php');
    } else {
        throw new Exception("Error al guardar los datos: " . $stmtInsert->errorInfo()[2]);
    }
} catch (PDOException $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => 'Error al registrar el paciente: ' . $e->getMessage()]);
} catch (Exception $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => 'Error al registrar el paciente: ' . $e->getMessage()]);
}
