const form_login = document.getElementById('form_login');
const modal_login = document.getElementById('modal_login');
const input_dni = document.getElementById('dni_login');
const boton_cerrar = document.getElementById('boton_cerrar');
const boton_iniciar_sesion = document.getElementById('boton_iniciar_sesion');
const boton_cerrar_sesion = document.getElementById('boton_cerrar_sesion');

function checkSession() {
    fetch('./login/check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                boton_cerrar_sesion.hidden = false
                boton_iniciar_sesion.hidden = true
            } else {
                boton_cerrar_sesion.hidden = true
                boton_iniciar_sesion.hidden = false
            }
        });
}
checkSession()
modal_login.addEventListener('shown.bs.modal', function () {
    input_dni.focus()
});
modal_login.addEventListener('hidden.bs.modal', function () {
    form_login.reset()
});
boton_cerrar_sesion.addEventListener('click', () => {
    fetch('./login/logout.php')
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Hasta pronto!',
                text: 'Gracias por usar nuestro sistema. Te esperamos pronto.',
                showConfirmButton: true,
                confirmButtonColor: '#51dff8',
                confirmButtonText: 'Hasta luego',
            }).then(() => {
                window.location.href = "index.php";
            });
            boton_cerrar_sesion.hidden = true
            boton_iniciar_sesion.hidden = false
        });
});
form_login.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(form_login);

    fetch('./login/backend_login.php', {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.json();
    }).then(function (respuesta) {
        if (respuesta.success) {
            boton_iniciar_sesion.hidden = true
            boton_cerrar_sesion.hidden = false
            boton_cerrar.click()
            Swal.fire({
                icon: 'success',
                title: respuesta.name,
                text: respuesta.message,
                showConfirmButton: false,
                timer: 3000
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