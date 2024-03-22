<?php require '../assets/template/header.php';
$ir_a_pestana = '';
if (isset($_SESSION['pestana']) && $_SESSION['pestana'] == 'HC') {
    $ir_a_pestana = 'HC';
}
?>
<input type="hidden" name="dni_paciente" id="dni_paciente" value="<?php echo $_SESSION['dni'] ?>">
<title>Panel de Admin</title>

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pacientes-tab" data-bs-toggle="tab" data-bs-target="#pacientes-tab-pane" type="button" role="tab" aria-controls="pacientes-tab-pane" aria-selected="true">Pacientes</button>
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
    </div>
    <div class="tab-pane fade" id="historial_consulta-tab-pane" role="tabpanel" aria-labelledby="historial_consulta-tab" tabindex="0">
        <div class="container align-items-center justify-content-center mt-3">
            <div class="table">
                <table id="tabla_HC_paciente" class="table table-bordered table-striped responsive nowrapx">
                    <thead>
                        <tr>
                            <th>Fecha de Consulta</th>
                            <th>Peso</th>
                            <th>Observaciones Nutricionista</th>
                            <th>Observaciones Paciente</th>
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