function verificarEmail() {
    let email = document.getElementById("email").value;
    if (!/\S+@\S+\.\S+/.test(email)) {
        mostrarAlerta("El email no tiene un formato válido.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "registro/backend_verificar_datos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.emailExiste) {
                mostrarAlerta("El email ya está registrado en el sistema.");
                document.getElementById("email").value = ''
            }
        } else {
            console.error("Error al verificar el email: " + xhr.statusText);
        }
    };
    xhr.send(`email=${email}`);
}

function verificarDNI() {
    let dni = document.getElementById("dni").value;
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "registro/backend_verificar_datos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.dniExiste) {
                mostrarAlerta("El DNI ya está registrado en el sistema.");
                document.getElementById("dni").value = ''
            }
        } else {
            console.error("Error al verificar el DNI: " + xhr.statusText);
        }
    };
    xhr.send(`dni=${dni}`);
}
function mostrarAlerta(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Atención',
        text: mensaje
    });
}