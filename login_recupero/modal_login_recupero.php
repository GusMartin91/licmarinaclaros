<div class="modal fade" id="modal_login_recupero" tabindex="-1" aria-labelledby="modal_login_recuperoLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #21dff8 !important;">
                <h1 class="modal-title fs-5" id="modal_login_recuperoLabel">Cambio/recupero de contraseña.</h1>
                <button type="button" id="boton_cerrar_re" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <form action="./login_recupero/backend_login_recupero.php" id="form_login_recupero" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <label for="dni_login_recupero" id="label_dni" class="form-label">DNI:</label>
                            <input type="text" onchange="verificarDNIValido()" name="dni_login_recupero" id="dni_login_recupero" class="form-control" required>
                        </div>
                        <div class="col-12 mb-2" id="div_email" hidden>
                            <label for="email_login_recupero" id="label_email" class="form-label">E-mail:</label>
                            <input type="email" onchange="verificarEmailValido()" name="email_login_recupero" id="email_login_recupero" class="form-control" required disabled>
                        </div>
                        <div class="col-12 mb-2" id="div_pseg" hidden>
                            <label for="pseguridad_login_recupero" class="form-label">Pregunta de seguridad:</label>
                            <input type="text" name="pseguridad_login_recupero" id="pseguridad_login_recupero" class="form-control" required disabled>
                        </div>
                        <div class="col-12 mb-2" id="div_rseg" hidden>
                            <label for="rseguridad_login_recupero" id="label_rseg" class="form-label">Respuesta secreta:</label>
                            <input type="password" onchange="verificarRSValida()" name="rseguridad_login_recupero" id="rseguridad_login_recupero" class="form-control" required disabled>
                        </div>
                        <div class="col-12 mb-2" id="div_new_pass" hidden>
                            <label for="new_pass" class="form-label">Ingrese nueva contraseña:</label>
                            <input type="password" name="new_pass" id="new_pass" class="form-control" required>
                        </div>
                        <div class="col-12 mb-2" id="div_new_pass_re" hidden>
                            <label for="new_pass_re" class="form-label">Repita nueva contraseña</label>
                            <input type="password" name="new_pass_re" id="new_pass_re" class="form-control" required>
                        </div>
                        <div class="text-center mt-3 mb-3" id="div_siguiente">
                            <button type="button" id="submit_siguiente" class="btn btn-primary"><i class="fa-regular fa-arrow"></i> Siguiente</button>
                        </div>
                        <div class="text-center mt-3 mb-3" id="div_submit" hidden>
                            <button type="button" onclick="submit_recupero()" id="submit_login_recupero" class="btn btn-primary"><i class="fa-regular fa-key"></i> Cambiar/recuperar contraseña</button>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_login" class="nav-link"><i class="fa-solid fa-question"></i> Volver a iniciar sesión</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>