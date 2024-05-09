<?php
require '../assets/conexion/conexion.php';

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
$aplicativo = 'Archivos';
$ruta_aplicativo = './paciente/index.php';


$id_archivo_paciente = $_POST["id_archivo"] ?? '';
$titulo = $_POST["titulo"] ?? '';
$fecha_archivo = $_POST["fecha_archivo"] ?? '';
$url_archivo = $_POST["url_archivo"] ?? '';
$rutaArchivo = "../assets/file_server/" . $usuario . "/files/" . $url_archivo;
$descripcion = $_POST["descripcion"] ?? '';
$movimiento = $_POST["movimiento"] ?? '';
try {
    $con->beginTransaction();
    switch ($movimiento) {
        case 'A':
            $tipo_auditoria = 'Alta en Archivos';
            if (isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {
                // Directorio donde se guardarán los archivos
                $directorio = "../assets/file_server/" . $usuario . "/files/";

                $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                // Obtener información del archivo de archivo
                $nombreArchivo = $usuario . '_' . $randomNumber . '_' . $_FILES["archivo"]["name"];
                $rutaArchivo = $directorio . $nombreArchivo;
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true); // Crea el directorio y establece los permisos adecuados
                }
                move_uploaded_file($_FILES["archivo"]["tmp_name"], $rutaArchivo);
            } else {
                echo "Debe seleccionar un archivo.";
            }
            // Preparar la consulta SQL para insertar el registro en la base de datos
            $sqlInsert = "INSERT INTO archivos_paciente (dni_paciente, titulo, descripcion, url_archivo, fecha_archivo, usuario) VALUES (:dni_paciente, :titulo, :descripcion, :url_archivo, :fecha_archivo, :usuario)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bindParam(":dni_paciente", $usuario);
            $stmtInsert->bindParam(":titulo", $titulo);
            $stmtInsert->bindParam(":descripcion", $descripcion);
            $stmtInsert->bindParam(":url_archivo", $nombreArchivo);
            $stmtInsert->bindParam(":fecha_archivo", $fecha_archivo);
            $stmtInsert->bindParam(":usuario", $usuario);
            $stmtInsert->execute();
            $id_archivo_paciente = obtenerID($con);
            break;
        case 'B':
            $tipo_auditoria = 'Eliminacion en Archivos';
            // Preparar la consulta SQL para borrar el registro en la base de datos
            $sqlDelete = "DELETE FROM archivos_paciente WHERE id_archivo = :id_archivo";
            $stmtDelete = $con->prepare($sqlDelete);
            $stmtDelete->bindParam(":id_archivo", $id_archivo_paciente);
            $stmtDelete->execute();
            break;
    }
    $actualizacionString = "Titulo: " . $titulo . ", Fecha archivo: " . $fecha_archivo . ", URL archivo: " . $rutaArchivo . ", Descripcion: " . $descripcion;

    // Preparar la consulta SQL para auditar
    $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES (:tipo_auditoria, :id_archivo_paciente, :usuario, :aplicativo, :ruta_aplicativo, :actualizacionString, :usuario, :ip_cliente, :sistema_operativo, :navegador)";
    $stmtAudita = $con->prepare($sqlAudita);
    $stmtAudita->bindParam(':tipo_auditoria', $tipo_auditoria, PDO::PARAM_STR);
    $stmtAudita->bindParam(':id_archivo_paciente', $id_archivo_paciente, PDO::PARAM_INT);
    $stmtAudita->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmtAudita->bindParam(':aplicativo', $aplicativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':ruta_aplicativo', $ruta_aplicativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':actualizacionString', $actualizacionString, PDO::PARAM_STR);
    $stmtAudita->bindParam(':ip_cliente', $ip_cliente, PDO::PARAM_STR);
    $stmtAudita->bindParam(':sistema_operativo', $sistema_operativo, PDO::PARAM_STR);
    $stmtAudita->bindParam(':navegador', $navegador, PDO::PARAM_STR);
    $stmtAudita->execute();

    $con->commit();
    if (isset($sqlInsert)) {
        echo json_encode([
            'success' => true,
            'icon' => 'success',
            'message' => 'Archivo subido exitosamente',
            'ruta_archivo' => $rutaArchivo,
            'titulo' => $titulo,
            'descripcion' => $descripcion
        ]);
    }
    if (isset($sqlDelete)) {
        echo json_encode([
            'success' => true,
            'icon' => 'error',
            'message' => '¡El archivo ha sido borrado exitosamente!',
            'ruta_archivo' => $rutaArchivo,
            'titulo' => $titulo,
            'descripcion' => $descripcion
        ]);
    }
} catch (PDOException $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => 'Error al subir el archivo y guardar en la base de datos: ' . $e->getMessage()]);
}
