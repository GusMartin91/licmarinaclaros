<?php
$sqlGeneros = "SELECT * FROM generos";
$stmtGenero = $con->prepare($sqlGeneros);
$stmtGenero->execute();
?>
<div class="modal fade" id="modal_paciente" tabindex="-1" aria-labelledby="modal_pacienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #21dff8 !important;">
                <h1 class="modal-title fs-5" id="modal_pacienteLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./backend_pacientes.php" id="form_paciente" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="movimiento" id="movimiento">
                    <input type="hidden" name="id_paciente" id="id_paciente">
                    <input type="hidden" name="nombre_actual" id="nombre_actual">
                    <input type="hidden" name="apellido_actual" id="apellido_actual">
                    <input type="hidden" name="id_genero_actual" id="id_genero_actual">
                    <input type="hidden" name="fecha_nacimiento_actual" id="fecha_nacimiento_actual">
                    <input type="hidden" name="fecha_ultima_consulta_actual" id="fecha_ultima_consulta_actual">
                    <input type="hidden" name="fecha_proxima_consulta_actual" id="fecha_proxima_consulta_actual">
                    <input type="hidden" name="telefono_actual" id="telefono_actual">
                    <input type="hidden" name="email_actual" id="email_actual">
                    <input type="hidden" name="altura_actual" id="altura_actual">
                    <input type="hidden" name="peso_actual" id="peso_actual">
                    <input type="hidden" name="observaciones_actual" id="observaciones_actual">
                    <div class="row mb-3">
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="nombre" class="form-label">Nombre/s:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required></input>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="apellido" class="form-label">Apellido/s:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" require></input>
                        </div>
                        <div class="col-xs-12 col-sm-3 mb-2">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" name="dni" id="dni" class="form-control" onchange="verificarDNI()" required>
                        </div>
                        <div class="col-xs-12 col-sm-4 mb-2">
                            <label for="id_genero" class="form-label">Genero:</label>
                            <select name="id_genero" id="id_genero" class="form-select">
                                <option value="" selected disabled hidden>Seleccionar...</option>
                                <?php while ($row_genero = $stmtGenero->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?= $row_genero['id_genero'] ?>"><?= $row_genero['desc_genero'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-5 mb-2">
                            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="telefono" class="form-label">Telefono:</label>
                            <input type="number" name="telefono" id="telefono" class="form-control" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="email" class="form-label">E-mail:</label>
                            <input type="email" name="email" id="email" class="form-control" onchange="verificarEmail()" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="fecha_ultima_consulta" class="form-label">Fecha ultima consulta:</label>
                            <input type="date" name="fecha_ultima_consulta" id="fecha_ultima_consulta" class="form-control" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="fecha_proxima_consulta" class="form-label">Fecha proxima consulta:</label>
                            <input type="date" name="fecha_proxima_consulta" id="fecha_proxima_consulta" class="form-control" required>
                        </div>
                        <hr>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="password2" class="form-label">Confirmar contraseña:</label>
                            <input type="password" onchange="confirma_pass()" name="password2" id="password2" class="form-control" required>
                            <span id="mensaje_confirmacion"></span>
                        </div>
                        <hr>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="pseguridad" class="form-label">Pregunta secreta:</label>
                            <input type="text" class="form-control" id="pseguridad" name="pseguridad" placeholder="Pregunta de seguridad" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="rseguridad" class="form-label">Respuesta secreta:</label>
                            <input type="password" class="form-control" id="rseguridad" name="rseguridad" placeholder="Respuesta de seguridad" required>
                        </div>
                        <hr>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="altura" class="form-label">Altura (en cm):</label>
                            <input type="number" name="altura" id="altura" class="form-control" placeholder="Altura en cm" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 mb-2">
                            <label for="peso" class="form-label">Peso (en kg):</label>
                            <input type="number" name="peso" id="peso" class="form-control" placeholder="Peso en kg" required>
                        </div>
                        <div class="col-12">
                            <label for="observaciones" class="form-label">Observaciones: (opcional)</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="3" cols="43"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit_paciente" class="btn btn-primary"></button>
                        <button type="button" id="elbotoncerrar" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>