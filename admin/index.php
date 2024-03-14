<?php require '../assets/template/header_2.php';
$swal_message = [];
if (isset($_SESSION['swal_message'])) {
    $swal_message = $_SESSION['swal_message'];
    unset($_SESSION['swal_message']);
}
?>
<title>Panel de Admin</title>

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pacientes-tab" data-bs-toggle="tab" data-bs-target="#pacientes-tab-pane" type="button" role="tab" aria-controls="pacientes-tab-pane" aria-selected="true">Pacientes</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="otro-tab" data-bs-toggle="tab" data-bs-target="#otro-tab-pane" type="button" role="tab" aria-controls="otro-tab-pane" aria-selected="false">Otro</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="pacientes-tab-pane" role="tabpanel" aria-labelledby="pacientes-tab" tabindex="0">
        <div class="container align-items-center justify-content-center mt-3">
            <div class="d-flex justify-content-end">
                <a href="#" class="btn btn-success" onclick="botonNuevo()" data-bs-toggle="modal" data-bs-target="#modal_paciente"><i class="fa-regular fa-user-plus"></i> Ingresar Paciente</a>
            </div>
            <div class="table">
                <table id="tabla_pacientes" class="table table-bordered table-striped responsive nowrapx">
                    <thead>
                        <tr>
                            <th>Apellido</th>
                            <th>Nombre</th>
                            <th>Genero</th>
                            <th>Edad</th>
                            <th>Ultima Consulta</th>
                            <th>Proxima Consulta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

    </div>
    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">

    </div>
    <div class="tab-pane fade" id="otro-tab-pane" role="tabpanel" aria-labelledby="otro-tab" tabindex="0">

    </div>
</div>

<?php
require './modal_paciente.php';
require '../assets/template/footer_2.php';
?>
<script>
    const swalMessage = <?php echo json_encode($swal_message); ?>;
    mostrarAlertaRegistro(swalMessage);
</script>