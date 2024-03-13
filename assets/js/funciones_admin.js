const form_paciente = document.getElementById('form_paciente');
const modal_paciente = document.getElementById('modal_paciente');
const input_nombre = document.getElementById('nombre');
const mensaje = document.getElementById('mensaje_confirmacion');
const input_password = document.getElementById('password');
const input_password2 = document.getElementById('password2');
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
                    nombre: response[i].nombre || '',
                    apellido: response[i].apellido || '',
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
                    '<td>' + '<i class="fa-solid fa-pen-to-square"></i><i class="fa-solid fa-trash"></i>'
                // `<button title="Editar" onclick="botonEditar()" class="btn btn-sm espacio_botones btn-warning" data-bs-toggle="modal" data-bs-target="#modal" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-pen-to-square"></i></button>` +
                // `<button title="Eliminar" class="btn btn-danger btn-sm espacio_botones" onclick="gestionarEliminar(event)" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-trash"></i></button>` +
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


modal_paciente.addEventListener('hidden.bs.modal', function () {
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