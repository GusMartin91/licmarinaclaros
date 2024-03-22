const boton_cerrar_sesion = document.getElementById('boton_cerrar_sesion')
const boton_iniciar_sesion = document.getElementById('boton_iniciar_sesion');
const boton_registrarse = document.getElementById('boton_registrarse');

const urlParams = new URLSearchParams(window.location.search);
const sessionExpired = urlParams.get('session_expired');
if (sessionExpired === 'true') {
    Swal.fire({
        icon: 'warning',
        title: 'Sesión Expirada',
        text: 'Por seguridad, tu sesión ha concluido. Por favor, inicia sesión nuevamente.',
        confirmButtonText: 'Salir',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../home/index.php';
        }
    });
}
function checkSession() {
    fetch('../login/check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                boton_cerrar_sesion.hidden = false
                boton_iniciar_sesion.hidden = true
                boton_registrarse.hidden = true
            } else {
                boton_cerrar_sesion.hidden = true
                if (boton_admin) {
                    boton_admin.hidden = true
                }
                if (boton_paciente) {
                    boton_paciente.hidden = true
                }
                boton_registrarse.hidden = false
                boton_iniciar_sesion.hidden = false
            }
        });
}
checkSession()

boton_cerrar_sesion.addEventListener('click', () => {
    fetch('../login/logout.php')
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Hasta pronto!',
                text: 'Gracias por usar nuestro sistema. Te esperamos pronto.',
                showConfirmButton: true,
                confirmButtonColor: '#2141f8',
                confirmButtonText: 'Hasta luego',
            }).then(() => {
                window.location.href = "index.php";
            });
            boton_cerrar_sesion.hidden = true
            if (boton_admin) {
                boton_admin.hidden = true
            }
            boton_registrarse.hidden = false
            boton_iniciar_sesion.hidden = false
        });
});