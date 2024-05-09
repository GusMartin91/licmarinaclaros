const form_paciente = document.getElementById('form_paciente');
const pacientes_tab_pane = document.getElementById('pacientes-tab-pane');
const modal_paciente = document.getElementById('modal_paciente');
const modal_pacienteLabel = document.getElementById('modal_pacienteLabel');
const submit_paciente = document.getElementById('submit_paciente');
const input_nombre = document.getElementById('nombre');
const input_dni = document.getElementById('dni');
const mensaje = document.getElementById('mensaje_confirmacion');
const input_password = document.getElementById('password');
const input_password2 = document.getElementById('password2');
const input_pseguridad = document.getElementById('pseguridad');
const input_rseguridad = document.getElementById('rseguridad');
let dni_paciente = document.getElementById('dni_paciente').value
let usuario = document.getElementById('usuario_hidden_admin').value
let today = new Date();
let currentMonth = ('0' + (today.getMonth() + 1)).substr(-2);
let currentDate = ('0' + today.getDate()).substr(-2);
let currentHours = ('0' + today.getHours()).substr(-2);
let currentMins = ('0' + today.getMinutes()).substr(-2);
let currentSecs = ('0' + today.getSeconds()).substr(-2);
let date = currentDate + '-' + currentMonth + '-' + today.getFullYear();
let time = currentHours + ":" + currentMins + ":" + currentSecs;
let dateTime = date + ' a las ' + time;

function fichaPaciente() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_ficha_paciente.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const paciente = JSON.parse(xhr.responseText);
            if (paciente) {
                let dni = paciente.dni
                const fechaNacimiento = moment(paciente.fecha_nacimiento).format("DD-MM-YYYY");
                document.getElementById('titulo_ficha').innerHTML = `Ficha personal de <strong>${paciente.apellido}, ${paciente.nombre}</strong>`
                document.getElementById('dni_paciente_foto').value = dni;
                const formData = new FormData();
                formData.append('dni', dni);
                const url = './consulta_ultimo_id_foto.php';
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let idMasReciente = data.id ? data.id : 0;
                            document.getElementById('id_historial_foto').value = idMasReciente;
                        } else {
                            console.error(data.error);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
                const fotoPerfil = paciente.foto_perfil || '';
                const fotoPerfilUrl = `../assets/file_server/${paciente.dni}/profile/${fotoPerfil}`;
                const defaultImageUrl = `../assets/img/default_profile.png`;

                async function setProfileImage(fotoPerfilUrl) {
                    try {
                        let exists = await checkImage(fotoPerfilUrl);
                        if (!exists) {
                            document.getElementById('foto-paciente').src = defaultImageUrl;
                            document.querySelector('#fotoPaciente img').alt = `Foto por defecto`;
                        } else {
                            document.getElementById('foto-paciente').src = fotoPerfilUrl;
                            document.querySelector('#fotoPaciente img').alt = `Foto de ${paciente.apellido}, ${paciente.nombre}`;
                        }
                    } catch (error) {
                        console.error("Error al verificar la imagen:", error);
                    }
                }
                setProfileImage(fotoPerfilUrl);
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(1) strong').textContent = paciente.apellido + ", " + paciente.nombre;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(2) strong').textContent = fechaNacimiento;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(3) strong').textContent = paciente.edad;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(4) strong').textContent = paciente.desc_genero;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(5) strong').textContent = dni;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(6) strong').textContent = paciente.telefono;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(7) strong').textContent = paciente.email;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(8) strong').textContent = paciente.direccion;
                pacientes_tab_pane.querySelector('#historial_medico strong').textContent = paciente.historial_medico;
                pacientes_tab_pane.querySelector('#alergias strong').textContent = paciente.alergias;
                pacientes_tab_pane.querySelector('#intolerancia strong').textContent = paciente.intolerancia;
                pacientes_tab_pane.querySelector('#medicamentos strong').textContent = paciente.medicamentos;
                pacientes_tab_pane.querySelector('#vacunas strong').textContent = paciente.vacunas;
                pacientes_tab_pane.querySelector('#enfermedades strong').textContent = paciente.enfermedades;
                pacientes_tab_pane.querySelector('#pruebas_diagnosticas strong').textContent = paciente.pruebas_diagnosticas;
                pacientes_tab_pane.querySelector('#plato_preferido strong').textContent = paciente.plato_preferido;
                pacientes_tab_pane.querySelector('#bebida_preferida strong').textContent = paciente.bebida_preferida;
                pacientes_tab_pane.querySelector('#debilidad strong').textContent = paciente.debilidad;
                pacientes_tab_pane.querySelector('#cocina strong').textContent = paciente.cocina;
                pacientes_tab_pane.querySelector('#comidas_x_dia strong').textContent = paciente.comidas_x_dia;
                pacientes_tab_pane.querySelector('#horario_comidas strong').textContent = paciente.horario_comidas;
            } else {
                console.error(response.error);
            }
        }
    }
    xhr.send(`dni_paciente=${dni_paciente}`);
};
fichaPaciente()

if (fotoPaciente) {
    fotoPaciente.addEventListener('mouseenter', () => {
        icon_camera.hidden = false
    })
    fotoPaciente.addEventListener('mouseleave', () => {
        icon_camera.hidden = true
    })
}
function checkImage(imagePath) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./check_image.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                resolve(response.exists);
            } else {
                reject(xhr.statusText);
            }
        };
        xhr.onerror = function () {
            reject("Error de red al intentar verificar la imagen.");
        };
        xhr.send(`image_path=${imagePath}`);
    });
}
function mostrarAlertaPaciente(swalMessage) {
    if (swalMessage != '') {
        Swal.fire({
            icon: swalMessage.icon,
            title: swalMessage.title,
            text: swalMessage.text,
            confirmButtonText: swalMessage.confirmButtonText
        });
    }
}