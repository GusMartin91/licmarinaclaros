<?php
session_start();
$usuario = NULL;
if (isset($_SESSION['dni'])) {
    $usuario = $_SESSION['dni'];
}
function obtenerID()
{
    global $mysqli;
    return mysqli_insert_id($mysqli);
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
    $uploadPath = $uploadDirectory . $fileName;

    if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
        $mysqli = new mysqli("localhost", "nutrihot", "nutrihot915", "licmarinaclaros");

        if ($mysqli->connect_error) {
            echo json_encode([
                "message" => "Error en la conexión a la base de datos."
            ]);
            exit;
        }
        $query = "INSERT INTO historial_foto (dni_paciente, nombre_foto, usuario, fecha_desde) VALUES (?, ?, ?, ?)";
        $queryUpdate = "UPDATE historial_foto SET fecha_hasta = '$fecha_cambio_hasta' WHERE id_historial_foto = '$id_historial_foto'";
        $queryPaciente = "UPDATE pacientes SET foto_perfil = 'img/profiles/$fileName' WHERE dni = '$dni_paciente_foto'";

        $statement = $mysqli->prepare($query);
        $statementPaciente = $mysqli->prepare($queryPaciente);
        $statementUpdate = $mysqli->prepare($queryUpdate);

        $statement->bind_param("ssss", $dni_paciente_foto, $fileName, $usuario, $fecha_cambio);

        if ($statement->execute()) {
            $statementUpdate->execute();
            $id_historial_foto_nuevo = obtenerID();
            $sqlAudita = "INSERT INTO auditorias (tipo_auditoria, id_modificado, dni_modificado, aplicativo, ruta_aplicativo, usuario, ip_cliente, sistema_operativo, browser) VALUES ('$tipo_auditoria', '$id_historial_foto_nuevo', '$dni_paciente_foto', '$aplicativo', '$ruta_aplicativo', '$usuario', '$ip_cliente', '$sistema_operativo', '$navegador')";
            $statementAudita = $mysqli->prepare($sqlAudita);
            $statementAudita->execute();
            $statementPaciente->execute();
            echo json_encode([
                "message" => "Imagen cargada exitosamente en $uploadPath y nombre de archivo guardado en la base de datos.",
                "file"    => $uploadPath,
            ]);
        } else {
            echo json_encode([
                "message" => "Error al guardar el nombre del archivo en la base de datos."
            ]);
        }

        $statement->close();
        $statementPaciente->close();
        $statementAudita->close();
        $statementUpdate->close();
        $mysqli->close();
    } else {
        echo json_encode([
            "message" => "Fallo al subir imagen"
        ]);
    }
} else {
    echo json_encode([
        "message" => "Invalid request."
    ]);
}
