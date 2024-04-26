<?php
include '../assets/conexion/conexion.php';

session_start();
$dni_paciente = $_POST['dni_paciente'];

try {
    $consulta = "SELECT * FROM v_pacientes WHERE dni = :dni_paciente";
    $statement = $con->prepare($consulta);
    $statement->bindParam(':dni_paciente', $dni_paciente);
    $statement->execute();

    $paciente = $statement->fetch(PDO::FETCH_ASSOC);

    if ($paciente) {
        echo json_encode($paciente, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró al paciente con el DNI proporcionado']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener la información del paciente: ' . $e->getMessage()]);
}
