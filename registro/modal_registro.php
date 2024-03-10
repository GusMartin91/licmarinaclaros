<?php
$sqlGeneros = "SELECT * FROM generos";
$generos = $con->query($sqlGeneros);
?>
<div class="modal fade" id="modal_registro" tabindex="-1" aria-labelledby="modal_registroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #21dff8 !important;">
                <h1 class="modal-title fs-5" id="modal_registroLabel">Registro del Paciente.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal_registro" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./registro/backend_registro.php" id="form" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-6 mb-2">
                            <label for="nombre" class="form-label">Nombre/s:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required></input>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="apellido" class="form-label">Apellido/s:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" require></input>
                        </div>
                        <div class="col-3 mb-2">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" name="dni" id="dni" class="form-control" onchange="verificarDNI()" required>
                        </div>
                        <div class="col-4 mb-2">
                            <label for="genero" class="form-label">Genero:</label>
                            <select name="genero" id="genero" class="form-select">
                                <option value="" selected disaled hidden>Seleccionar...</option>
                                <?php while ($row_genero = $generos->fetch_assoc()) { ?>
                                    <option value="<?php echo $row_genero["id_genero"]; ?>"><?= $row_genero["desc_genero"] ?></option>
                                <?php }; ?>
                            </select>
                        </div>
                        <div class="col-5 mb-2">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                        </div>
                        <div class="col-5 mb-2">
                            <label for="telefono" class="form-label">Telefono:</label>
                            <input type="number" name="telefono" id="telefono" class="form-control" required>
                        </div>
                        <hr>
                        <div class="col-6 mb-2">
                            <label for="email" class="form-label">E-mail:</label>
                            <input type="email" name="email" id="email" class="form-control" onchange="verificarEmail()" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="password" class="form-label">Contraseña para iniciar sesion:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <hr>
                        <div class="col-6 mb-2">
                            <label for="pseguridad" class="form-label">Pregunta secreta:</label>
                            <input type="text" class="form-control" id="pseguridad" name="pseguridad" placeholder="Pregunta de seguridad" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="rseguridad" class="form-label">Respuesta secreta:</label>
                            <input type="text" class="form-control" id="rseguridad" name="rseguridad" placeholder="Respuesta de seguridad" required>
                        </div>
                        <hr>
                        <div class="col-5 mb-2">
                            <label for="altura" class="form-label">Altura (en cm):</label>
                            <input type="number" name="altura" id="altura" class="form-control" placeholder="Altura en cm" required>
                        </div>
                        <div class="col-5 mb-2">
                            <label for="peso" class="form-label">Peso (en kg):</label>
                            <input type="number" name="peso" id="peso" class="form-control" placeholder="Peso en kg" required>
                        </div>
                        <div class="col-12">
                            <label for="observaciones" class="form-label">Observaciones: (opcional)</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="3" cols="43"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i> Registrarse</button>
                        <button type="button" id="elbotoncerrar" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>