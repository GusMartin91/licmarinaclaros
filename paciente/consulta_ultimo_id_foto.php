<?php
include '../assets/conexion/conexion.php';

session_start();
$dni = $_POST['dni'];

$consulta = "SELECT MAX(id_historial_foto) FROM historial_foto WHERE dni_paciente = '$dni'";

$resultado = mysqli_query($con, $consulta);

if ($resultado) {
  $idMasReciente = mysqli_fetch_row($resultado)[0];

  echo json_encode(['success' => true, 'id' => $idMasReciente]);
} else {
  echo json_encode(['success' => false, 'error' => 'Error al obtener el ID']);
}
