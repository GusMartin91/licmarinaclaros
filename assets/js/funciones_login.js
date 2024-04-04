const form_login = document.getElementById('form_login');
const modal_login = document.getElementById('modal_login');
const input_dni = document.getElementById('dni_login');
const boton_cerrar = document.getElementById('boton_cerrar');
const div_boton_admin = document.getElementById('div_boton_admin');
const boton_paciente_a = document.getElementById('boton_paciente_a');
const boton_paciente_p = document.getElementById('boton_paciente_p');
const boton_admin = document.getElementById('boton_admin');

modal_login.addEventListener('shown.bs.modal', function () {
    input_dni.focus()
});
modal_login.addEventListener('hidden.bs.modal', function () {
    form_login.reset()
});

form_login.addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(form_login);
    fetch('../login/backend_login.php', {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.json();
    }).then(function (respuesta) {
        if (respuesta.success) {
            boton_iniciar_sesion.hidden = true
            boton_registrarse.hidden = true
            if (respuesta.rol == 'admin') {
                div_boton_admin.innerHTML = `<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i> ${respuesta.name}</a><ul class="dropdown-menu"><li><a class="dropdown-item nav-link" href="../admin/index.php" id="boton_admin" class="nav-link"><i class="fa-solid fa-gear"></i> Panel Admin</a></li><li><a class="dropdown-item nav-link" href="../paciente/index.php" id="boton_paciente_a" class="nav-link"><i class="fa-solid fa-gear"></i> Panel Paciente</a></li><li><hr class="dropdown-divider"></li><li><a href="#" id="boton_cerrar_sesion" onclick="cerrar_sesion()" class="nav-link"><i class="fa-sharp fa-solid fa-lock"></i> Cerrar sesion</a></li>`
            } else if (respuesta.rol == 'paciente') {
                div_boton_admin.innerHTML = `<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i> ${respuesta.name}</a><ul class="dropdown-menu"><li><a class="dropdown-item nav-link" href="../paciente/index.php" id="boton_paciente_p" class="nav-link"><i class="fa-solid fa-gear"></i> Panel Paciente</a></li><li><hr class="dropdown-divider"></li><li><a href="#" id="boton_cerrar_sesion" onclick="cerrar_sesion()" class="nav-link"><i class="fa-sharp fa-solid fa-lock"></i> Cerrar sesion</a></li>`
            }
            boton_cerrar.click()
            Swal.fire({
                icon: 'success',
                title: respuesta.name,
                text: respuesta.message,
                showConfirmButton: false,
                timer: 2000
            })
        } else {
            form_login.reset()
            input_dni.focus()
            Swal.fire({
                icon: 'error',
                title: 'Error de inicio de sesión',
                text: respuesta.message,
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
});
