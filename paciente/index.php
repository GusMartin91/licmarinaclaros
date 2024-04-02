<?php require '../assets/template/header.php';
$ir_a_pestana = '';
if (isset($_SESSION['pestana']) && $_SESSION['pestana'] == 'HC') {
    $ir_a_pestana = 'HC';
}
?>
<input type="hidden" name="dni_paciente" id="dni_paciente" value="<?php echo $_SESSION['dni'] ?>">
<title>Panel del Paciente</title>

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pacientes-tab" onclick="fichaPaciente()" data-bs-toggle="tab" data-bs-target="#pacientes-tab-pane" type="button" role="tab" aria-controls="pacientes-tab-pane" aria-selected="true">Ficha Paciente</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="historial_consulta-tab" onclick="tabla_HC_paciente()" data-bs-toggle="tab" data-bs-target="#historial_consulta-tab-pane" type="button" role="tab" aria-controls="historial_consulta-tab-pane" aria-selected="false">Historial de consultas</button>
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
        <div class="container fluid">
            <h2 class="text-center mt-3 mb-2" id="titulo_ficha"></h2>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Información básica</h4>
                        </div>
                        <div class="card-body row">
                            <div class="col-xs-12 col-sm-4" id="fotoPaciente">
                                <img src="../assets/img/profile_demo.jpeg" class="rounded-circle  w-100" alt="Foto del paciente" id="foto-paciente">
                            </div>
                            <ul class="list-group col-xs-12 col-sm-8">
                                <li class="list-group-item">Nombre completo: <strong>[Nombre completo del paciente]</strong></li>
                                <li class="list-group-item">Fecha de nacimiento: <strong>[Fecha de nacimiento del paciente]</strong></li>
                                <li class="list-group-item">Edad: <strong>[Edad del paciente]</strong></li>
                                <li class="list-group-item">Genero: <strong>[Genero del paciente]</strong></li>
                                <li class="list-group-item">DNI: <strong>[DNI del paciente]</strong></li>
                                <li class="list-group-item">Número de teléfono: <strong>[Número de teléfono del paciente]</strong></li>
                                <li class="list-group-item">Correo electrónico: <strong>[Correo electrónico del paciente]</strong></li>
                                <li class="list-group-item">Dirección: <strong>[Dirección del paciente]</strong></li>
                            </ul>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 borderMID">
                                <div class="card-header">
                                    <h4>Información médica general.</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li id="historial_medico" class="list-group-item">Historial médico: <strong>[Historial médico del paciente]</strong></li>
                                        <li id="alergias" class="list-group-item">Alergias: <strong>[Alergias del paciente]</strong></li>
                                        <li id="intolerancia" class="list-group-item">Intolerante a: <strong>[Intolerante a...]</strong></li>
                                        <li id="medicamentos" class="list-group-item">Medicamentos actuales: <strong>[Medicamentos actuales del paciente]</strong></li>
                                        <li id="vacunas" class="list-group-item">Vacunas: <strong>[Vacunas del paciente]</strong></li>
                                        <li id="enfermedades" class="list-group-item">Enfermedades crónicas: <strong>[Enfermedades crónicas del paciente]</strong></li>
                                        <li id="pruebas_diagnosticas" class="list-group-item">Resultados de pruebas diagnósticas: <strong>[Resultados de pruebas diagnósticas del paciente]</strong></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="card-header">
                                    <h4>Información nutricional</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li id="plato_preferido" class="list-group-item">Comida/Plato preferida/o: <strong>[Comida/Plato preferida/o del paciente]</strong></li>
                                        <li id="bebida_preferida" class="list-group-item">Bebida preferida: <strong>[Bebida preferida del paciente]</strong></li>
                                        <li id="debilidad" class="list-group-item">Debilidad: <strong>[Debilidad del paciente]</strong></li>
                                        <li id="cocina" class="list-group-item">Cocina: <strong>[Cocina el paciente]</strong></li>
                                        <li id="comidas_x_dia" class="list-group-item">Cantidad de comidas/dia: <strong>[Cant comidas/dia el paciente]</strong></li>
                                        <li id="horario_comidas" class="list-group-item">Horarios de comida: <strong>[Horarios de comida el paciente]</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="historial_consulta-tab-pane" role="tabpanel" aria-labelledby="historial_consulta-tab" tabindex="0">
        <div class="container align-items-center justify-content-center mt-3">
            <div class="table">
                <table id="tabla_HC_paciente" style="width: 100%" class="table table-bordered table-striped responsive nowrapx">
                    <thead>
                        <tr>
                            <th>Nro de Consulta</th>
                            <th>Fecha de Consulta</th>
                            <th>Peso (en kg)</th>
                            <th>Observaciones Nutricionista</th>
                            <th>Mis Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">

    </div>
    <div class="tab-pane fade" id="otro-tab-pane" role="tabpanel" aria-labelledby="otro-tab" tabindex="0">

    </div>
</div>

<?php
require '../assets/template/footer.php';
?>
<script src="../assets/js/funciones_paciente.js"></script>
<script src="../assets/js/funciones_paciente_HC.js"></script>
<script>
    const irAPestana = <?php echo json_encode($ir_a_pestana); ?>;
    if (irAPestana == 'HC') {
        setTimeout(() => {
            historial_consulta.click()
        }, 2000);
    }
</script>