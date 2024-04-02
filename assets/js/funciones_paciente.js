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
                const fechaNacimiento = paciente.fecha_nacimiento;
                const fechaFormateada = fechaNacimiento.split('-').reverse().join('-');
                document.getElementById('titulo_ficha').innerHTML = `Ficha personal de <strong>${paciente.apellido}, ${paciente.nombre}</strong>`
                document.getElementById('foto-paciente').src = "../assets/" + paciente.foto_perfil;
                pacientes_tab_pane.querySelector('#fotoPaciente img').alt = `Foto de ${paciente.apellido}, ${paciente.nombre}`;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(1) strong').textContent = paciente.apellido + ", " + paciente.nombre;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(2) strong').textContent = fechaFormateada;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(3) strong').textContent = paciente.edad;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(4) strong').textContent = paciente.desc_genero;
                pacientes_tab_pane.querySelector('.list-group-item:nth-child(5) strong').textContent = paciente.dni;
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