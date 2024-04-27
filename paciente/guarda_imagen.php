<?php
session_start();
$usuario = isset($_SESSION['dni']) ? $_SESSION['dni'] : null;

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
$aplicativo = 'Foto Paciente';
$tipo_auditoria = 'Cambio de Foto';
$ruta_aplicativo = './paciente/index.php';
if (isset($_FILES['image']) && isset($_POST['dni_paciente_foto']) && isset($_POST['id_historial_foto'])) {
    $image = $_FILES['image'];
    $dni_paciente_foto = $_POST['dni_paciente_foto'];
    $id_historial_foto = $_POST['id_historial_foto'];
    $fecha_cambio = date("Y-m-d");
    $fecha_cambio_hasta = strtotime('-1 day', strtotime($fecha_cambio));
    $fecha_cambio_hasta = date("Y-m-d", $fecha_cambio_hasta);
    $uploadDirectory = '../assets/img/profiles/';
    $randomNumber = rand(0000, 9999);
    $fileName = $dni_paciente_foto . '_' . $randomNumber . '.jpg';
    $foto_perfil = "img/profiles/" . $fileName;
    $uploadPath = $uploadDirectory . $fileName;

    try {
        $con = new PDO("mysql:host=localhost;dbname=licmarinaclaros", "nutrihot", "nutrihot915");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->beginTransaction();
        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
            $query = "INSERT INTO historial_foto (dni_paciente, nombre_foto, usuario, fecha_desde) VALUES (?, ?, ?, ?)";
            $queryUpdate = "UPDATE historial_foto SET fecha_hasta = ? WHERE id_historial_foto = ?";
            $queryPaciente = "UPDATE pacientes SET foto_perfil = ? WHERE dni = ?";
        } else {
            echo json_encode([
                "message" => "Error al mover el archivo al directorio de destino."
            ]);
            exit;
        }

        $statement = $con->prepare($query);
        $statementPaciente = $con->prepare($queryPaciente);
        $statementUpdate = $con->prepare($queryUpdate);

        $statement->bindParam(1, $dni_paciente_foto);
        $statement->bindParam(2, $fileName);
        $statement->bindParam(3, $usuario);
        $statement->bindParam(4, $fecha_cambio);

        if ($statement->execute()) {
            $id_historial_foto_nuevo = obtenerID($con);
            $statementUpdate->bindParam(1, $fecha_cambio_hasta);
            $statementUpdate->bindParam(2, $id_historial_foto);
            $statementUpdate->execute();
            $statementPaciente->bindParam(1, $foto_perfil);
            $statementPaciente->bindParam(2, $dni_paciente_foto);
            $statementPaciente->execute();

            $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, usuario, ip_cliente, sistema_operativo, browser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statementAudita = $con->prepare($sqlAudita);
            $statementAudita->bindParam(1, $tipo_auditoria);
            $statementAudita->bindParam(2, $id_historial_foto_nuevo);
            $statementAudita->bindParam(3, $dni_paciente_foto);
            $statementAudita->bindParam(4, $aplicativo);
            $statementAudita->bindParam(5, $ruta_aplicativo);
            $statementAudita->bindParam(6, $usuario);
            $statementAudita->bindParam(7, $ip_cliente);
            $statementAudita->bindParam(8, $sistema_operativo);
            $statementAudita->bindParam(9, $navegador);
            $statementAudita->execute();

            $con->commit();

            echo json_encode([
                "message" => "Imagen cargada exitosamente en $uploadPath y nombre de archivo guardado en la base de datos.",
                "file"    => $uploadPath,
            ]);
        } else {
            echo json_encode([
                "message" => "Error al guardar el nombre del archivo en la base de datos."
            ]);
        }
    } catch (PDOException $e) {
        $con->rollBack();
        echo "Error de conexión: " . $e->getMessage();
    }
    $con = null;
} else {
    echo json_encode([
        "message" => "Invalid request."
    ]);
}
