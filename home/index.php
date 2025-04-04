<?php require '../assets/template/header.php';
$swal_message = [];
if (isset($_SESSION['swal_message'])) {
  $swal_message = $_SESSION['swal_message'];
  unset($_SESSION['swal_message']);
}
$resultado;
$fecha_proxima_consulta;
$email_paciente;
if (isset($_GET['event_start_time']) && isset($_GET['invitee_email'])) {
  $fecha_proxima_consulta = $_GET['event_start_time'];
  $email_paciente = $_GET['invitee_email'];

  try {
    if (!$con) {
      throw new PDOException('No database connection established.');
    }

    $sqlComprobarEmail = "SELECT * FROM pacientes WHERE email = :email";
    $stmt = $con->prepare($sqlComprobarEmail);
    $stmt->bindParam(":email", $email_paciente);
    $stmt->execute();

    $paciente = $stmt->fetch();
    $emailExiste = !empty($paciente);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>

<title>Lic. Marina Claros</title>
<div class="container mt-5">
  <div class="row mb-4">
    <div class="col-md-8" id="sobre_mi">
      <h2>Bienvenido a tu cambio de habito.</h2>
      <p>Me presento! Mi nombre es Marina, soy licenciada en Nutrición 🥦, egresada de la UBA en 2012. Actualmente trabajo para el municipio de Lanús en el área de la salud pública, donde realizo diferentes tareas como atención en consultorio, dictado de talleres y charlas en diferentes instituciones y además recibo estudiantes que realizan sus prácticas pre profesionales.
        Soy docente universitaria de la universidad nacional de Lanus en 3 asignaturas y de la UAI, como referente en prácticas.
        Además realizo asesorías on line para todo el mundo 🌎 tanto de niños como adultos. Brindo atención en diferentes situaciones como embarazo y adulto mayor. Realizo educación alimentaria, como pilar de los tratamientos. Las consultas pueden ser para aprender a alimentarse mejor o brindar lineamientos frente a alguna enfermedad como diabetes, sobrepeso, hipertension, colesterol alto, etc.
        Espero que les sirva la info que les voy a ir compartiendo y no duden en consultarme. Cuando quieras soy tu nutri 😉. Un abrazo 🤗</p>
    </div>
    <div class="col-md-4 mt-3">
      <img class="img-fluid" src="../assets/img/marina_claros.png">
    </div>
  </div>

  <section id="servicios" class="section section-bg" style="background-image: url('../assets/img/img101.jpg');">
    <div class="bg-overlay"></div>
    <div class="container">
      <h2 class="section-text">Servicios</h2>
      <p class="section-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla commodo massa nec felis sagittis, sit amet dictum justo volutpat. Integer finibus velit ac quam condimentum, vel efficitur ligula tempus. Sed condimentum, quam at interdum vehicula, lectus lectus sodales justo, nec posuere orci turpis sit amet nulla.</p>
    </div>
  </section>

  <section id="testimonios" class="section section-bg" style="background-image: url('../assets/img/img102.jpg');">
    <div class="bg-overlay"></div>
    <div class="container">
      <h2 class="section-text">Testimonios</h2>
      <p class="section-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla commodo massa nec felis sagittis, sit amet dictum justo volutpat. Integer finibus velit ac quam condimentum, vel efficitur ligula tempus. Sed condimentum, quam at interdum vehicula, lectus lectus sodales justo, nec posuere orci turpis sit amet nulla.</p>
    </div>
  </section>

  <section id="contacto" class="section section-bg" style="background-image: url('../assets/img/img103.jpg');">
    <div class="bg-overlay"></div>
    <div class="container">
      <h2 class="section-text">Contacto</h2>
      <p class="section-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla commodo massa nec felis sagittis, sit amet dictum justo volutpat. Integer finibus velit ac quam condimentum, vel efficitur ligula tempus. Sed condimentum, quam at interdum vehicula, lectus lectus sodales justo, nec posuere orci turpis sit amet nulla.</p>
    </div>
  </section>
</div>

<a href="" id="nav_item" title="Agendar consulta." class="consulta-btn btn">
  <i class="fa-regular fa-calendar-check fa-lg"></i><span class="text ms-2">Reservar cita.</span>
</a>

<a href="https://wa.me/91136146176?text=Hola,%20me%20gustaría%20reservar%20una%20cita!" class="whatsapp-btn btn btn-success" target="_blank" rel="noopener noreferrer">
  <i class="fab fa-whatsapp fa-lg"></i>
  <span class="text">¿Cómo puedo ayudarte?</span>
</a>

<?php
include '../registro/modal_registro.php';
include '../login/modal_login.php';
include '../login_recupero/modal_login_recupero.php';
require '../assets/template/footer.php';
?>
<script src="../assets/js/funciones_login.js"></script>
<script src="../assets/js/funciones_login_recupero.js"></script>
<script src="../assets/js/funciones_registro.js"></script>
<?php
if (isset($paciente) && empty($paciente)) {
  echo "<script>
    Swal.fire({
      icon: 'warning',
      title: 'Aun no te registraste.',
      text: 'Clickea en Registrarme para poder ver tus historiales de consultas, tus planes, poder subir tus fotos a tu ficha personal, etc.',
      showCancelButton: false,
      showConfirmButton: true,
      confirmButton: 'Registrarme'
    }).then((result) => {
      if (result.isConfirmed) {
        boton_registrarse.click();
      }
    });
  </script>";
} elseif (isset($paciente) && $paciente !== false) {
  $sqlNuevaConsulta = "UPDATE pacientes SET fecha_proxima_consulta = :fecha_proxima_consulta WHERE email = :email";
  $stmt = $conn->prepare($sqlNuevaConsulta);
  $stmt->bindParam(":fecha_proxima_consulta", $fecha_proxima_consulta);
  $stmt->bindParam(":email", $email_paciente);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    echo "<script>
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'La próxima consulta se ha actualizado correctamente.',
        timer: 2000
      });
    </script>";
  }
}
?>
<script>
  const swalMessage = <?php echo json_encode($swal_message); ?>;
  mostrarAlertaRegistro(swalMessage);
</script>



</body>

</html>