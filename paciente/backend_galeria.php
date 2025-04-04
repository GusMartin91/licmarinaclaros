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
$aplicativo = 'Galeria';
$ruta_aplicativo = './paciente/index.php';


$id_galeria_paciente = $_POST["id_galeria"] ?? '';
$titulo = $_POST["titulo"] ?? '';
$fecha_imagen = $_POST["fecha_imagen"] ?? '';
$url_imagen = $_POST["url_imagen"] ?? '';
$rutaArchivo = "../assets/file_server/" . $usuario . "/gallery/" . $url_imagen;
$descripcion = $_POST["descripcion"] ?? '';
$movimiento = $_POST["movimiento"] ?? '';
try {
    $con->beginTransaction();
    switch ($movimiento) {
        case 'A':
            $tipo_auditoria = 'Alta en Galeria';
            if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
                // Directorio donde se guardarán las imágenes
                $directorio = "../assets/file_server/" . $usuario . "/gallery/";

                $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                // Obtener información del archivo de imagen
                $nombreArchivo = $usuario . '_' . $randomNumber . '_' . $_FILES["imagen"]["name"];
                $rutaArchivo = $directorio . $nombreArchivo;
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true); // Crea el directorio y establece los permisos adecuados
                }
                move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo);
            } else {
                echo "Debe seleccionar una imagen.";
            }
            // Preparar la consulta SQL para insertar el registro en la base de datos
            $sqlInsert = "INSERT INTO galeria_paciente (dni_paciente, titulo, descripcion, url_imagen, fecha_imagen, usuario) VALUES (:dni_paciente, :titulo, :descripcion, :url_imagen, :fecha_imagen, :usuario)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bindParam(":dni_paciente", $usuario);
            $stmtInsert->bindParam(":titulo", $titulo);
            $stmtInsert->bindParam(":descripcion", $descripcion);
            $stmtInsert->bindParam(":url_imagen", $nombreArchivo);
            $stmtInsert->bindParam(":fecha_imagen", $fecha_imagen);
            $stmtInsert->bindParam(":usuario", $usuario);
            $stmtInsert->execute();
            $id_galeria_paciente = obtenerID($con);
            break;
        case 'B':
            $tipo_auditoria = 'Eliminacion en Galeria';
            // Preparar la consulta SQL para borrar el registro en la base de datos
            $sqlDelete = "DELETE FROM galeria_paciente WHERE id_galeria = :id_galeria";
            $stmtDelete = $con->prepare($sqlDelete);
            $stmtDelete->bindParam(":id_galeria", $id_galeria_paciente);
            $stmtDelete->execute();
            break;
    }
    $actualizacionString = "Titulo: " . $titulo . ", Fecha imagen: " . $fecha_imagen . ", URL imagen: " . $rutaArchivo . ", Descripcion: " . $descripcion;

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
    if (isset($sqlInsert)) {
        echo json_encode([
            'success' => true,
            'icon' => 'success',
            'message' => 'Imagen subida exitosamente',
            'ruta_imagen' => $rutaArchivo,
            'titulo' => $titulo,
            'descripcion' => $descripcion
        ]);
    }
    if (isset($sqlDelete)) {
        echo json_encode([
            'success' => true,
            'icon' => 'error',
            'message' => '¡La imagen ha sido borrada exitosamente!',
            'ruta_imagen' => $rutaArchivo,
            'titulo' => $titulo,
            'descripcion' => $descripcion
        ]);
    }
} catch (PDOException $e) {
    $con->rollBack();
    echo json_encode(['success' => false, 'error' => 'Error al subir la imagen y guardar en la base de datos: ' . $e->getMessage()]);
}
