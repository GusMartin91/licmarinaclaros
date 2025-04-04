const form_registro = document.getElementById('form_registro');
const modal_registro = document.getElementById('modal_registro');
const input_email = document.getElementById('email');
const input_nombre = document.getElementById('nombre');
const input_apellido = document.getElementById('apellido');
const mensaje = document.getElementById('mensaje_confirmacion');
const input_password = document.getElementById('password');
const input_password2 = document.getElementById('password2');
const input_fecha_proxima_consulta = document.getElementById('fecha_proxima_consulta');
const fechaProximaConsultaParam = urlParams.get("event_start_time");
const nombreParam = urlParams.get("invitee_first_name");
const apellidoParam = urlParams.get("invitee_last_name");
const emailParam = urlParams.get("invitee_email");
let fechaProximaConsulta;
if (fechaProximaConsultaParam) {
    fechaProximaConsulta = moment(fechaProximaConsultaParam).format("YYYY-MM-DD HH:mm");
}

modal_registro.addEventListener('hidden.bs.modal', function () {
    form_registro.reset()
});
modal_registro.addEventListener('shown.bs.modal', function () {
    input_nombre.focus()
    if (typeof fechaProximaConsulta !== 'undefined') {
        input_fecha_proxima_consulta.value = fechaProximaConsulta
        input_nombre.value = nombreParam
        input_apellido.value = apellidoParam
        input_email.value = emailParam
    }
});

function verificarEmail() {
    let email = document.getElementById("email").value;
    if (!/\S+@\S+\.\S+/.test(email)) {
        mostrarAlerta("El email no tiene un formato válido.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../registro/backend_verificar_datos.php", true);
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
    xhr.open("POST", "../registro/backend_verificar_datos.php", true);
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

function mostrarAlertaRegistro(swalMessage) {
    if (swalMessage != '') {
        Swal.fire({
            icon: swalMessage.icon,
            title: swalMessage.title,
            text: swalMessage.text,
            confirmButtonText: swalMessage.confirmButtonText
        });
    }
}

function confirma_pass() {
    if (input_password.value === input_password2.value) {
        input_password2.style.border = '2px solid green';
        mensaje.innerText = 'Las contraseñas coinciden';
        mensaje.style.color = 'green';
    } else {
        input_password2.style.border = '2px solid red';
        mensaje.innerText = 'Las contraseñas no coinciden';
        mensaje.style.color = 'red';
    }
}