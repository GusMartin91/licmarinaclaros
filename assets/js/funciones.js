let boton_cerrar_sesion_admin = document.getElementById('boton_cerrar_sesion_admin')
if (boton_cerrar_sesion_admin) {
    boton_cerrar_sesion_admin.addEventListener('click', () => {
        window.location.href = "../login/logout.php";
    })
}
// Mostrar SweetAlert indicando que la sesión ha concluido
const urlParams = new URLSearchParams(window.location.search);
const sessionExpired = urlParams.get('session_expired');

if (sessionExpired === 'true') {
    Swal.fire({
        icon: 'warning',
        title: 'Sesión Expirada',
        text: 'Por seguridad, tu sesión ha concluido. Por favor, inicia sesión de nuevo.',
        confirmButtonText: 'Salir',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = './index.php';
        }
    });
}
