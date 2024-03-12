<div class="modal fade" id="modal_login" tabindex="-1" aria-labelledby="modal_loginLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #21dff8 !important;">
                <h1 class="modal-title fs-5" id="modal_loginLabel">Inicio de sesion.</h1>
                <button type="button" id="boton_cerrar" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <form action="./login/backend_login.php" id="form_login" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <label for="dni_login" class="form-label">DNI:</label>
                            <input type="text" name="dni_login" id="dni_login" class="form-control" required>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="password_login" class="form-label">Contraseña:</label>
                            <input type="password" name="password_login" id="password_login" class="form-control" required>
                        </div>
                        <div class="text-center mt-3 mb-3">
                            <button type="submit" id="submit_login" class="btn btn-primary"><i class="fa-regular fa-key"></i> Iniciar sesion</button>
                        </div>
                        <hr>
                        <div class="text-center mt-1">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_registro" class="nav-link"><i class="fa-solid fa-user-plus"></i> Registrarse</a>
                        </div>
                        <div class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_login_recupero" class="nav-link"><i class="fa-solid fa-question"></i> Olvide mi Contraseña</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>