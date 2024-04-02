<div class="modal fade" id="modal_ficha_paciente" tabindex="-1" aria-labelledby="modal_ficha_pacienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #37ff7e !important;">
                <h1 class="modal-title ms-auto fs-5" id="modal_ficha_pacienteLabel">Ficha personal de <strong>[Paciente]</strong></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container fluid">
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
            <div class="modal-footer">
                <button type="button" id="elbotoncerrar" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>