const form_registro = document.getElementById('form_registro');
const modal_registro = document.getElementById('modal_registro');
const input_nombre = document.getElementById('nombre');


modal_registro.addEventListener('hidden.bs.modal', function () {
    form_registro.reset()
});
modal_registro.addEventListener('shown.bs.modal', function () {
    input_nombre.focus()
});

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