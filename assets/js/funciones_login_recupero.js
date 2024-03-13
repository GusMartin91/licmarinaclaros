let dni_login_recupero = document.getElementById('dni_login_recupero')
let label_dni = document.getElementById('label_dni')
let email_login_recupero = document.getElementById('email_login_recupero')
let label_email = document.getElementById('label_email')
let pseguridad_login_recupero = document.getElementById('pseguridad_login_recupero')
let rseguridad_login_recupero = document.getElementById('rseguridad_login_recupero')
let label_rseg = document.getElementById('label_rseg')
let recupero_new_pass = document.getElementById('new_pass')
let recupero_new_pass_re = document.getElementById('new_pass_re')
let submit_login_recupero = document.getElementById('submit_login_recupero')
let modal_login_recupero = document.getElementById('modal_login_recupero');
let form_login_recupero = document.getElementById('form_login_recupero');
let div_email = document.getElementById('div_email');
let div_pseg = document.getElementById('div_pseg');
let div_rseg = document.getElementById('div_rseg');
let div_new_pass = document.getElementById('div_new_pass');
let div_new_pass_re = document.getElementById('div_new_pass_re');
let div_siguiente = document.getElementById('div_siguiente');
let div_submit = document.getElementById('div_submit');
let boton_cerrar_re = document.getElementById('boton_cerrar_re');

modal_login_recupero.addEventListener('hidden.bs.modal', function () {
    form_login_recupero.reset()
    email_login_recupero.disabled = true
    email_login_recupero.removeAttribute('placeholder')
    rseguridad_login_recupero.disabled = true
    rseguridad_login_recupero.removeAttribute('placeholder')
    label_dni.innerHTML = 'DNI:'
    label_email.innerHTML = 'E-mail:'
    label_rseg.innerHTML = 'Respuesta secreta:'
    div_siguiente.hidden = false
    div_submit.hidden = true
    div_email.hidden = true
    div_pseg.hidden = true
    div_rseg.hidden = true
    div_new_pass.hidden = true
    div_new_pass_re.hidden = true

});
modal_login_recupero.addEventListener('shown.bs.modal', function () {
    dni_login_recupero.focus()
});
function verificarDNIValido() {
    let dni = dni_login_recupero.value;
    const data = {
        dni,
    };
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "login_recupero/backend_login_recupero.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.dniExiste) {
                div_email.hidden = false
                email_login_recupero.disabled = false
                email_login_recupero.setAttribute('placeholder', 'Ingrese su E-mail.')
                label_dni.innerHTML = 'DNI: <i class="fa-solid fa-circle-check" style="color: #05a808;"></i>'
            } else {
                div_email.hidden = true
                email_login_recupero.disabled = true
                label_dni.innerHTML = 'DNI: <i class="fa-solid fa-xmark" style="color: #eb1e1e;"></i> ingrese un DNI valido.'
                email_login_recupero.removeAttribute('placeholder')
            }
        } else {
            console.error("Error al verificar el email: " + xhr.statusText);
        }
    };
    xhr.send(JSON.stringify(data));
}

function verificarEmailValido() {
    let dni = dni_login_recupero.value;
    let email = email_login_recupero.value;
    if (!/\S+@\S+\.\S+/.test(email)) {
        mostrarAlerta("El email no tiene un formato válido.");
        return;
    }
    const data = {
        dni,
        email,
    };

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "login_recupero/backend_login_recupero.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.emailExiste) {
                div_pseg.hidden = false
                div_rseg.hidden = false
                rseguridad_login_recupero.disabled = false
                rseguridad_login_recupero.setAttribute('placeholder', 'Ingrese su respuesta secreta')
                label_email.innerHTML = 'E-mail: <i class="fa-solid fa-circle-check" style="color: #05a808;"></i>'
                pseguridad_login_recupero.value = respuesta.pseguridad
            } else {
                div_pseg.hidden = true
                div_rseg.hidden = true
                rseguridad_login_recupero.disabled = true
                rseguridad_login_recupero.removeAttribute('placeholder')
                label_email.innerHTML = 'E-mail: <i class="fa-solid fa-xmark" style="color: #eb1e1e;"></i> ingrese un E-mail valido.'
            }
        } else {
            console.error("Error al verificar el email: " + xhr.statusText);
        }
    };
    xhr.send(JSON.stringify(data));
}

function verificarRSValida() {
    let dni = dni_login_recupero.value;
    let email = email_login_recupero.value;
    let rseguridad = rseguridad_login_recupero.value;
    const data = {
        dni,
        email,
        rseguridad,
    };
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "login_recupero/backend_login_recupero.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.rseguridadValida) {
                div_new_pass.hidden = false
                div_new_pass_re.hidden = false
                div_siguiente.hidden = true
                div_submit.hidden = false
                label_rseg.innerHTML = 'Respuesta secreta: <i class="fa-solid fa-circle-check" style="color: #05a808;"></i>'
            } else {
                div_new_pass.hidden = true
                div_new_pass_re.hidden = true
                div_siguiente.hidden = false
                div_submit.hidden = true
                console.error("Error al verificar la respuesta de seguridad " + xhr.statusText);
                label_rseg.innerHTML = 'Respuesta secreta: <i class="fa-solid fa-xmark" style="color: #eb1e1e;"></i> respuesta invalida.'
            }
        } else {
            console.error("Error al verificar la respuesta de seguridad " + xhr.statusText);
        }
    };
    xhr.send(JSON.stringify(data));
}

function submit_recupero() {
    let dni = dni_login_recupero.value
    let new_pass = recupero_new_pass.value
    let new_pass_re = recupero_new_pass_re.value
    if (new_pass === new_pass_re) {
        const data = {
            dni,
            new_pass,
        };
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "login_recupero/backend_login_recupero.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const respuesta = JSON.parse(xhr.responseText);
                if (respuesta.cambioExitoso) {
                    Swal.fire({
                        icon: 'success',
                        title: "¡Contraseña cambiada exitosamente!",
                        text: "Su contraseña ha sido actualizada correctamente. Puede iniciar sesión con su nueva contraseña.",
                        confirmButtonText: 'Iniciar Sesión',
                        showCancelButton: true,
                        cancelButtonText: 'Salir',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('boton_iniciar_sesion').click();
                        }
                    });
                    boton_cerrar_re.click()
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cambiar/recuperar contraseña.',
                        html: 'Algo salio mal, por favor volve a intentar cambiar/recuperar la contraseña o ponete en contacto.',
                        footer: '<a href="https://wa.me/91136146176?text=Hola,%20necesito%20ayuda%20para%20cambiar/recuperar%20mi%20contraseña." class="whatsapp-btn btn btn-success" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp fa-lg"></i><span class="text">¿Necesitas Ayuda?</span></a>',
                        confirmButtonText: 'Aceptar',
                    });
                    boton_cerrar_re.click()
                }
            } else {
                console.error("Error al cambiar/recuperar contraseña: " + xhr.statusText);
            }
        };
        xhr.send(JSON.stringify(data));
    } else {
        Swal.fire({
            icon: 'error',
            title: "Las contraseñas no coinciden",
            text: "Por favor, asegúrate de que las contraseñas sean iguales.",
            confirmButtonText: 'Aceptar',
        });
    }
}

function mostrarAlerta(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Atención',
        text: mensaje
    });
}