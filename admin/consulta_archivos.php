<?php
include '../assets/conexion/conexion.php';

$dni_paciente = $_POST['dni'];
if (empty($dni_paciente)) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener el DNI del paciente.'
    ]);
    exit;
}

try {
    $consulta = "SELECT * FROM archivos_paciente WHERE dni_paciente = :dni_paciente";
    $statement = $con->prepare($consulta);
    $statement->bindParam(':dni_paciente', $dni_paciente);
    $statement->execute();

    $array_archivos = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($array_archivos, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener los archivos del paciente: ' . $e->getMessage()]);
}
$con = null;
