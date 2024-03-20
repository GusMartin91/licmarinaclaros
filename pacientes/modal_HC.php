<div class="modal fade" id="modal_HC" tabindex="-1" aria-labelledby="modal_HCLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #21dff8 !important;">
                <h1 class="modal-title fs-5" id="modal_HCLabel">Historial de Consultas.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./backend_HC.php" id="form_HC" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="movimiento_HC" id="movimiento_HC">
                    <div class="row mb-3">
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="dni_HC" class="form-label">Ingrese DNI del Paciente:</label>
                            <input type="text" name="dni_HC" id="dni_HC" class="form-control" onchange="verificarDNI_HC()" required>
                            <span id="consulta_paciente"></span>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="fecha_consulta_HC" class="form-label">Fecha consulta:</label>
                            <input type="date" name="fecha_consulta_HC" id="fecha_consulta_HC" class="form-control">
                        </div>
                        <hr>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="peso_HC" class="form-label">Peso (en kg):</label>
                            <input type="number" name="peso_HC" id="peso_HC" class="form-control" placeholder="Peso en kg" required>
                        </div>
                        <div class="col-12">
                            <label for="observaciones_HC" class="form-label">Observaciones: (opcional)</label>
                            <textarea name="observaciones_HC" id="observaciones_HC" class="form-control" rows="3" cols="43"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit_HC" class="btn btn-primary"></button>
                        <button type="button" id="elbotoncerrar_HC" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>