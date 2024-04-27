<?php
include '../assets/conexion/conexion.php';

session_start();
$dni = $_POST['dni'];

try {
  $consulta = "SELECT MAX(id_historial_foto) FROM historial_foto WHERE dni_paciente = :dni";
  $statement = $con->prepare($consulta);
  $statement->bindParam(':dni', $dni);
  $statement->execute();

  $idMasReciente = $statement->fetchColumn();

  if ($idMasReciente !== false) {
    echo json_encode(['success' => true, 'id' => $idMasReciente]);
  } else {
    echo json_encode(['success' => false, 'error' => 'No se pudo obtener el ID más reciente']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Error al obtener el ID: ' . $e->getMessage()]);
}
$con = null;
