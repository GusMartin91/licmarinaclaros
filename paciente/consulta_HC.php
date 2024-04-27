<?php
include '../assets/conexion/conexion.php';

$dni_paciente = $_POST['dni'];
if (empty($dni_paciente)) {
    echo json_encode([
        'success' => false,
        'message' => 'El DNI no puede estar vacío.'
    ]);
    exit;
}

try {
    $consulta = "SELECT * FROM v_historial_consulta WHERE dni_paciente = :dni_paciente";
    $statement = $con->prepare($consulta);
    $statement->bindParam(':dni_paciente', $dni_paciente);
    $statement->execute();

    $array_HC = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($array_HC, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener el historial de consulta: ' . $e->getMessage()]);
}
$con = null;
