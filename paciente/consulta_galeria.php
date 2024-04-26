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
    $consulta = "SELECT * FROM galeria_paciente WHERE dni_paciente = :dni_paciente";
    $statement = $con->prepare($consulta);
    $statement->bindParam(':dni_paciente', $dni_paciente);
    $statement->execute();

    $array_HC = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($array_HC, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener la galería del paciente: ' . $e->getMessage()]);
} finally {
    if ($con !== null) {
        $con = null;
    }
}
