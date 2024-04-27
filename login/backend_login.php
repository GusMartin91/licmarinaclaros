<?php
include '../assets/conexion/conexion.php';

session_start();

try {
    $con->beginTransaction();

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

    $dni_login = $_POST['dni_login'];
    $password_login = $_POST['password_login'];

    $sqlVerificar = "SELECT * FROM pacientes WHERE dni = :dni";
    $stmtVerificar = $con->prepare($sqlVerificar);
    $stmtVerificar->bindParam(":dni", $dni_login);
    $stmtVerificar->execute();

    if ($stmtVerificar->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Las credenciales proporcionadas son inválidas.'
        ]);
        exit;
    } else {
        $pacienteBD = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password_login, $pacienteBD['password'])) {
            $_SESSION['id_paciente'] = $pacienteBD['id_paciente'];
            $_SESSION['dni'] = $pacienteBD['dni'];
            $_SESSION['apellido'] = $pacienteBD['apellido'];
            $_SESSION['nombre'] = $pacienteBD['nombre'];
            $_SESSION['rol'] = $pacienteBD['rol'];
            $_SESSION['logged_in'] = true;
            $_SESSION['tiempoInicio'] = time();

            $sqlInsert = "INSERT INTO login (dni, ip_cliente, sistema_operativo, navegador) 
                    VALUES (:dni, :ip_cliente, :sistema_operativo, :navegador)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bindParam(":dni", $dni_login);
            $stmtInsert->bindParam(":ip_cliente", $ip_cliente);
            $stmtInsert->bindParam(":sistema_operativo", $sistema_operativo);
            $stmtInsert->bindParam(":navegador", $navegador);
            $stmtInsert->execute();

            echo json_encode([
                'success' => true,
                'name' => $pacienteBD['nombre'] . ' ' . $pacienteBD['apellido'],
                'message' => 'Bienvenido al sistema.',
                'rol' => $pacienteBD['rol']
            ]);
            exit;
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son inválidas.'
            ]);
            exit;
        }
    }

    $con->commit();
} catch (PDOException $e) {
    $con->rollBack();
    echo json_encode(['error' => 'Error en la transacción: ' . $e->getMessage()]);
}
$con = null;
