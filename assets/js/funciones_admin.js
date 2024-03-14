const form_paciente = document.getElementById('form_paciente');
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
$(document).ready(function () {
    $.ajax({
        url: "./consulta_pacientes.php",
        dataType: "json",
        success: function (response) {
            $('#tabla_pacientes tbody').empty();
            for (let i = 0; i < response.length; i++) {
                let datosBoton = {
                    id_paciente: response[i].id_paciente || '',
                    dni: response[i].dni || '',
                    pseguridad: response[i].pseguridad || '',
                    nombre: response[i].nombre || '',
                    apellido: response[i].apellido || '',
                    id_genero: response[i].id_genero || '',
                    desc_genero: response[i].desc_genero || '',
                    altura: response[i].altura || '',
                    peso: response[i].peso || '',
                    telefono: response[i].telefono || '',
                    email: response[i].email || '',
                    fecha_nacimiento: response[i].fecha_nacimiento || '',
                    edad: response[i].edad || '',
                    rol: response[i].rol || '',
                    fecha_ultima_consulta: response[i].fecha_ultima_consulta || '',
                    fecha_proxima_consulta: response[i].fecha_proxima_consulta || '',
                    observaciones: response[i].observaciones || '',
                    movimiento: response[i].movimiento || '',
                }
                let fila = '<tr>' +
                    '<td>' + datosBoton.apellido + '</td>' +
                    '<td>' + datosBoton.nombre + '</td>' +
                    '<td>' + datosBoton.desc_genero + '</td>' +
                    '<td>' + datosBoton.edad + '</td>' +
                    '<td>' + datosBoton.fecha_ultima_consulta + '</td>' +
                    '<td>' + datosBoton.fecha_proxima_consulta + '</td>' +
                    '<td>' +
                    `<button title="Editar" onclick="botonEditar()" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modal_paciente" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-pen-to-square"></i></button>` +
                    `<button title="Eliminar" class="btn btn-danger btn-sm" onclick="gestionarEliminar(event)" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-trash"></i></button>` +
                    '</td>' +
                    '</tr>';

                $('#tabla_pacientes tbody').append(fila);
            }
            $('#tabla_pacientes').DataTable({
                order: [[0, 'asc']],
                language: {
                    url: "../assets/dataTables/Spanish.json"
                },
                responsive: true,
                colReorder: true,
                dom: '<"container-flex bg-pink"<"row justify-content-between align-items-center"<"col-auto"l><"col-auto"B><"col-auto"f>><"col-12"t><"row justify-content-between align-items-center"<"col-auto"i><"col-auto mt-2"p>>r>',
                buttons: [{
                    extend: 'copy',
                    title: 'Copiar los registros al Portapapeles',
                    exportOptions: {
                        columns: ":visible:not(.noExportar)"
                    },
                    text: 'Copiar'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'LISTADO DE PACIENTES',
                    filename: 'Listado de Pacientes',
                    messageBottom: 'La tabla fue exportada el ' + dateTime + ' por el usuario ' +
                        usuario,
                    exportOptions: {
                        columns: ":visible:not(.noExportar)"
                    },
                    pageStyle: {
                        sheetPr: {
                            pageSetUpPr: {
                                fitToPage: 1
                            }
                        },
                        printOptions: {
                            horizontalCentered: true,
                            verticalCentered: true
                        },
                        pageSetup: {
                            orientation: 'landscape',
                            paperSize: 9,
                            fitToWidth: "1",
                            fitToHeight: "0"
                        },
                        pageMargins: {
                            left: "0.2",
                            right: "0.2",
                            top: "0.4",
                            bottom: "0.4",
                            header: "0",
                            footer: "0",
                        },
                        repeatHeading: true,
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    filename: 'Listado de Pacientes del ' + dateTime,
                    title: 'Listado de Pacientes',
                    messageTop: 'Listado de Pacientes',
                    messageBottom: 'La tabla fue exportada el ' + dateTime + ' por el usuario ' +
                        usuario,
                    exportOptions: {
                        columns: ":visible:not(.noExportar)"
                    },
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (doc) {
                        doc.styles.title = {
                            fontSize: '18',
                            alignment: 'center'
                        }
                        doc.styles.messageBottom = {
                            fontSize: '34',
                            alignment: 'center'
                        }
                        doc.styles.tableHeader = {
                            fillColor: '#525659',
                            color: '#FFF',
                            fontSize: '10',
                            alignment: 'center',
                            bold: true
                        }
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ":visible:not(.noExportar)"
                    },
                    text: 'Imprimir',
                    messageBottom: 'La tabla fue exportada el ' + dateTime + ' por el usuario ' +
                        usuario,
                }
                ],
                columnDefs: [
                    {
                        className: "noExportar",
                        "targets": [6]
                    }, {
                        "sortable": false,
                        "targets": [6]
                    }, {
                        targets: (4, 5),
                        render: DataTable.render.datetime('DD/MM/YYYY'),
                    },
                    {
                        width: "10px",
                        targets: (3, 6)
                    }],
            });
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});

function botonNuevo() {
    function onModalShown(event) {
        modal_pacienteLabel.innerText = "Ingresar nuevo paciente"
        submit_paciente.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Ingresar'
        modal_paciente.querySelector('.modal-body #movimiento').value = 'A'
        modal_paciente.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_paciente.addEventListener('shown.bs.modal', onModalShown);
}
function botonEditar() {
    function onModalShown(event) {
        modal_pacienteLabel.innerText = "Modificar registro del paciente"
        submit_paciente.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Modificar'
        modal_paciente.querySelector('.modal-body #movimiento').value = 'U'
        input_password.disabled = true
        input_password2.disabled = true
        input_pseguridad.disabled = true
        input_rseguridad.disabled = true
        input_dni.disabled = true
        let button = event.relatedTarget
        let datos = JSON.parse(button.getAttribute('data-bs-datos'));
        modal_paciente.querySelector('.modal-body #id_paciente').value = datos.id_paciente
        modal_paciente.querySelector('.modal-body #dni').value = datos.dni
        modal_paciente.querySelector('.modal-body #nombre').value = datos.nombre
        modal_paciente.querySelector('.modal-body #apellido').value = datos.apellido
        modal_paciente.querySelector('.modal-body #id_genero').value = datos.id_genero
        modal_paciente.querySelector('.modal-body #fecha_nacimiento').value = datos.fecha_nacimiento
        modal_paciente.querySelector('.modal-body #telefono').value = datos.telefono
        modal_paciente.querySelector('.modal-body #email').value = datos.email
        modal_paciente.querySelector('.modal-body #altura').value = datos.altura
        modal_paciente.querySelector('.modal-body #peso').value = datos.peso
        modal_paciente.querySelector('.modal-body #observaciones').value = datos.observaciones
        modal_paciente.querySelector('.modal-body #nombre_actual').value = datos.nombre
        modal_paciente.querySelector('.modal-body #apellido_actual').value = datos.apellido
        modal_paciente.querySelector('.modal-body #id_genero_actual').value = datos.id_genero
        modal_paciente.querySelector('.modal-body #fecha_nacimiento_actual').value = datos.fecha_nacimiento
        modal_paciente.querySelector('.modal-body #telefono_actual').value = datos.telefono
        modal_paciente.querySelector('.modal-body #email_actual').value = datos.email
        modal_paciente.querySelector('.modal-body #altura_actual').value = datos.altura
        modal_paciente.querySelector('.modal-body #peso_actual').value = datos.peso
        modal_paciente.querySelector('.modal-body #observaciones_actual').value = datos.observaciones
        modal_paciente.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_paciente.addEventListener('shown.bs.modal', onModalShown);
}

function gestionarEliminar(event) {
    let button = event.currentTarget;
    let datos = JSON.parse(button.getAttribute('data-bs-datos'));
    datos.movimiento = "D"

    Swal.fire({
        title: '¡Atención!',
        text: '¿Desea eliminar definitivamente el registro?',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $('<form action=\'backend_pacientes.php\' method=\'post\'></form>');
            form.append('<input type=\'hidden\' name=\'id_paciente\' value=\'' + datos.id_paciente + '\'>');
            form.append('<input type=\'hidden\' name=\'movimiento\' value=\'' + datos.movimiento + '\'>');
            form.append('<input type=\'hidden\' name=\'dni\' value=\'' + datos.dni + '\'>');
            form.append('<input type=\'hidden\' name=\'pseguridad\' value=\'' + datos.pseguridad + '\'>');
            for (let dato in datos) {
                form.append(`<input type='hidden' name='${dato}_actual' value='${datos[dato]}'>`);
            }
            $('body').append(form);
            form.submit();
        }
    });
}

modal_paciente.addEventListener('hidden.bs.modal', function () {
    input_password.disabled = false
    input_password2.disabled = false
    input_pseguridad.disabled = false
    input_rseguridad.disabled = false
    input_dni.disabled = false
    form_paciente.reset()
});
modal_paciente.addEventListener('shown.bs.modal', function () {
    input_nombre.focus()
});

function verificarEmail() {
    let email = document.getElementById("email").value;
    if (!/\S+@\S+\.\S+/.test(email)) {
        mostrarAlerta("El email no tiene un formato válido.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./backend_verificar_datos.php", true);
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
    xhr.open("POST", "./backend_verificar_datos.php", true);
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