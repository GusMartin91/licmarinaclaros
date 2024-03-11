<?php
include '../assets/conexion/conexion.php';

session_start();

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

$sqlVerificar = "SELECT * FROM pacientes WHERE dni = '$dni_login'";
$result = mysqli_query($con, $sqlVerificar);

if (mysqli_num_rows($result) === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Las credenciales proporcionadas son invalidas.'
    ]);
    exit;
} else {
    $pacienteBD = mysqli_fetch_assoc($result);

    if (password_verify($password_login, $pacienteBD['password'])) {
        $_SESSION['id_paciente'] = $pacienteBD['id_paciente'];
        $_SESSION['dni'] = $pacienteBD['dni'];
        $_SESSION['rol'] = $pacienteBD['rol'];
        $_SESSION['logged_in'] = true;

        $sqlInsert = "INSERT INTO login (dni, ip_cliente, sistema_operativo, navegador) 
        VALUES ('$dni_login', '$ip_cliente', '$sistema_operativo', '$navegador')";
        mysqli_query($con, $sqlInsert);
        echo json_encode([
            'success' => true,
            'name' => $pacienteBD['nombre'] . ' ' . $pacienteBD['apellido'],
            'message' => 'Bienvenido al sistema.'
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Las credenciales proporcionadas son invalidas.'
        ]);
        exit;
    }
}
