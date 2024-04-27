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
$tipo_auditoria = 'Alta en Galeria';
$aplicativo = 'Galeria';
$ruta_aplicativo = './paciente/index.php';

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
    // Directorio donde se guardarán las imágenes
    $directorio = "../assets/img/gallery/";

    // Obtener información del archivo de imagen
    $nombreArchivo = $usuario . '_' . $_FILES["imagen"]["name"];
    $rutaArchivo = $directorio . $nombreArchivo;

    // Mover la imagen del directorio temporal al directorio de destino
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
        $titulo = $_POST["titulo"];
        $fecha = $_POST["fecha"];
        $descripcion = $_POST["descripcion"];
        $actualizacionString = "Titulo: " . $titulo . ", Fecha: " . $fecha . ", Descripcion: " . $descripcion;
        try {
            $con->beginTransaction();
            // Preparar la consulta SQL para insertar el registro en la base de datos
            $sqlInsert = "INSERT INTO galeria_paciente (dni_paciente, titulo, descripcion, url_imagen, fecha_imagen, usuario) VALUES (:dni_paciente, :titulo, :descripcion, :url_imagen, :fecha_imagen, :usuario)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bindParam(":dni_paciente", $usuario);
            $stmtInsert->bindParam(":titulo", $titulo);
            $stmtInsert->bindParam(":descripcion", $descripcion);
            $stmtInsert->bindParam(":url_imagen", $nombreArchivo);
            $stmtInsert->bindParam(":fecha_imagen", $fecha);
            $stmtInsert->bindParam(":usuario", $usuario);
            $stmtInsert->execute();

            $id_galeria_paciente = obtenerID($con);

            // Preparar la consulta SQL para auditar
            $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, ultima_actualizacion, usuario, ip_cliente, sistema_operativo, browser) VALUES (:tipo_auditoria, :id_galeria_paciente, :usuario, :aplicativo, :ruta_aplicativo, :actualizacionString, :usuario, :ip_cliente, :sistema_operativo, :navegador)";
            $stmtAudita = $con->prepare($sqlAudita);
            $stmtAudita->bindParam(':tipo_auditoria', $tipo_auditoria, PDO::PARAM_STR);
            $stmtAudita->bindParam(':id_galeria_paciente', $id_galeria_paciente, PDO::PARAM_INT);
            $stmtAudita->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmtAudita->bindParam(':aplicativo', $aplicativo, PDO::PARAM_STR);
            $stmtAudita->bindParam(':ruta_aplicativo', $ruta_aplicativo, PDO::PARAM_STR);
            $stmtAudita->bindParam(':actualizacionString', $actualizacionString, PDO::PARAM_STR);
            $stmtAudita->bindParam(':ip_cliente', $ip_cliente, PDO::PARAM_STR);
            $stmtAudita->bindParam(':sistema_operativo', $sistema_operativo, PDO::PARAM_STR);
            $stmtAudita->bindParam(':navegador', $navegador, PDO::PARAM_STR);
            $stmtAudita->execute();

            $con->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Imagen subida exitosamente',
                'ruta_imagen' => $rutaArchivo,
                'titulo' => $titulo,
                'descripcion' => $descripcion
            ]);
        } catch (PDOException $e) {
            $con->rollBack();
            echo json_encode(['success' => false, 'error' => 'Error al subir la imagen y guardar en la base de datos: ' . $e->getMessage()]);
        }
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "Debe seleccionar una imagen.";
}
