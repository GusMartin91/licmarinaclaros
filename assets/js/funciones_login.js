const form_login = document.getElementById('form_login');
const modal_login = document.getElementById('modal_login');
const input_dni = document.getElementById('dni_login');
const boton_cerrar = document.getElementById('boton_cerrar');
const div_boton_admin = document.getElementById('div_boton_admin');
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
            boton_cerrar_sesion.hidden = false
            if (respuesta.rol == 'admin') {
                div_boton_admin.innerHTML = '<a href="../admin/index.php" id="boton_admin" class="nav-link">| <i class="fa-solid fa-gear"></i> Panel Admin</a>'
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
