const form_paciente = document.getElementById('form_paciente');
const modal_paciente = document.getElementById('modal_paciente');
const modal_pacienteLabel = document.getElementById('modal_pacienteLabel');
const modal_ficha_paciente = document.getElementById('modal_ficha_paciente');
const modal_ficha_pacienteLabel = document.getElementById('modal_ficha_pacienteLabel');
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
function tabla_pacientes() {
    $('#tabla_pacientes').DataTable().destroy()
    $(document).ready(function () {
        $.ajax({
            url: "./consulta_pacientes.php",
            dataType: "json",
            success: function (response) {
                $('#tabla_pacientes tbody').empty();
                for (let i = 0; i < response.length; i++) {
                    let datosBoton = {
                        foto_perfil: response[i].foto_perfil || '',
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
                        direccion: response[i].direccion || '',
                        fecha_nacimiento: response[i].fecha_nacimiento || '',
                        fecha_ultima_consulta: response[i].fecha_ultima_consulta || '',
                        fecha_proxima_consulta: response[i].fecha_proxima_consulta || '',
                        edad: response[i].edad || '',
                        historial_medico: response[i].historial_medico || '',
                        alergias: response[i].alergias || '',
                        intolerancia: response[i].intolerancia || '',
                        medicamentos: response[i].medicamentos || '',
                        vacunas: response[i].vacunas || '',
                        enfermedades: response[i].enfermedades || '',
                        pruebas_diagnosticas: response[i].pruebas_diagnosticas || '',
                        comidas_x_dia: response[i].comidas_x_dia || '',
                        horario_comidas: response[i].horario_comidas || '',
                        plato_preferido: response[i].plato_preferido || '',
                        bebida_preferida: response[i].bebida_preferida || '',
                        debilidad: response[i].debilidad || '',
                        cocina: response[i].cocina || '',
                        rol: response[i].rol || '',
                        observaciones: response[i].observaciones || '',
                        movimiento: response[i].movimiento || '',
                    }
                    let fotoPerfilSrc = '';
                    if (datosBoton.foto_perfil !== '') {
                        $.ajax({
                            url: './check_image.php',
                            type: 'POST',
                            data: { image_path: datosBoton.foto_perfil },
                            async: false,
                            success: function (response) {
                                if (response.exists) {
                                    fotoPerfilSrc = '../assets/' + datosBoton.foto_perfil;
                                } else {
                                    fotoPerfilSrc = '../assets/img/profiles/default_profile.png';
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                                fotoPerfilSrc = '../assets/img/profiles/default_profile.png';
                            }
                        });
                    } else {
                        fotoPerfilSrc = '../assets/img/profiles/default_profile.png';
                    }
                    let fila = '<tr>' +
                        '<td><img src="' + fotoPerfilSrc + '" alt="Foto de perfil de ' + datosBoton.apellido + ', ' + datosBoton.nombre + '" class="imagen-perfil"></td>' +
                        '<td>' + datosBoton.apellido + '</td>' +
                        '<td>' + datosBoton.nombre + '</td>' +
                        '<td>' + datosBoton.desc_genero + '</td>' +
                        '<td>' + datosBoton.edad + '</td>' +
                        '<td>' + datosBoton.fecha_ultima_consulta + '</td>' +
                        '<td>' + datosBoton.fecha_proxima_consulta + '</td>' +
                        '<td>' +
                        `<button title="Ficha" onclick="botonFicha()" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#modal_ficha_paciente" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-user"></i></button>` +
                        `<button title="Editar" onclick="botonEditar()" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modal_paciente" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-pen-to-square"></i></button>` +
                        `<button title="Eliminar" class="btn btn-danger btn-sm" onclick="gestionarEliminar(event)" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-trash"></i></button>` +
                        '</td>' +
                        '</tr>';

                    $('#tabla_pacientes tbody').append(fila);
                }
                $('#tabla_pacientes').DataTable({
                    order: [[1, 'asc']],
                    language: {
                        url: "../assets/dataTables/Spanish.json"
                    },
                    responsive: true,
                    colReorder: true,
                    dom: '<"container-flex"<"row justify-content-between align-items-center"<"col-auto"l><"col-auto"B><"col-auto"f>><"col-12"t><"row justify-content-between align-items-center"<"col-auto"i><"col-auto mt-2"p>>r>',
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
                            "targets": [7]
                        }, {
                            "sortable": false,
                            "targets": [7]
                        }, {
                            targets: [(5), (6)],
                            render: DataTable.render.datetime('DD/MM/YYYY'),
                        },
                        {
                            width: "11%",
                            targets: (7)
                        }],
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
}
tabla_pacientes()
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
        modal_paciente.querySelector('.modal-body #movimiento').value = 'U'
        submit_paciente.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Modificar'
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
        modal_paciente.querySelector('.modal-body #fecha_ultima_consulta').value = datos.fecha_ultima_consulta
        modal_paciente.querySelector('.modal-body #fecha_proxima_consulta').value = datos.fecha_proxima_consulta
        modal_paciente.querySelector('.modal-body #telefono').value = datos.telefono
        modal_paciente.querySelector('.modal-body #email').value = datos.email
        modal_paciente.querySelector('.modal-body #altura').value = datos.altura
        modal_paciente.querySelector('.modal-body #peso').value = datos.peso
        modal_paciente.querySelector('.modal-body #observaciones').value = datos.observaciones
        modal_paciente.querySelector('.modal-body #nombre_actual').value = datos.nombre
        modal_paciente.querySelector('.modal-body #apellido_actual').value = datos.apellido
        modal_paciente.querySelector('.modal-body #id_genero_actual').value = datos.id_genero
        modal_paciente.querySelector('.modal-body #fecha_nacimiento_actual').value = datos.fecha_nacimiento
        modal_paciente.querySelector('.modal-body #fecha_ultima_consulta_actual').value = datos.fecha_ultima_consulta
        modal_paciente.querySelector('.modal-body #fecha_proxima_consulta_actual').value = datos.fecha_proxima_consulta
        modal_paciente.querySelector('.modal-body #telefono_actual').value = datos.telefono
        modal_paciente.querySelector('.modal-body #email_actual').value = datos.email
        modal_paciente.querySelector('.modal-body #altura_actual').value = datos.altura
        modal_paciente.querySelector('.modal-body #peso_actual').value = datos.peso
        modal_paciente.querySelector('.modal-body #observaciones_actual').value = datos.observaciones
        modal_paciente.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_paciente.addEventListener('shown.bs.modal', onModalShown);
}

function botonFicha() {
    function onModalShown(event) {
        let button = event.relatedTarget
        let datos = JSON.parse(button.getAttribute('data-bs-datos'));
        const fechaNacimiento = datos.fecha_nacimiento;
        const fechaFormateada = fechaNacimiento.split('-').reverse().join('-');

        modal_ficha_pacienteLabel.innerHTML = `Ficha personal de <strong>${datos.apellido}, ${datos.nombre}</strong>`
        document.getElementById('foto-paciente').src = "../assets/" + datos.foto_perfil;
        modal_ficha_paciente.querySelector('#fotoPaciente_admin img').alt = `Foto de ${datos.apellido}, ${datos.nombre}`;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(1) strong').textContent = datos.apellido + ", " + datos.nombre;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(2) strong').textContent = fechaFormateada;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(3) strong').textContent = datos.edad;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(4) strong').textContent = datos.desc_genero;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(5) strong').textContent = datos.dni;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(6) strong').textContent = datos.telefono;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(7) strong').textContent = datos.email;
        modal_ficha_paciente.querySelector('.list-group-item:nth-child(8) strong').textContent = datos.direccion;
        modal_ficha_paciente.querySelector('#historial_medico strong').textContent = datos.historial_medico;
        modal_ficha_paciente.querySelector('#alergias strong').textContent = datos.alergias;
        modal_ficha_paciente.querySelector('#intolerancia strong').textContent = datos.intolerancia;
        modal_ficha_paciente.querySelector('#medicamentos strong').textContent = datos.medicamentos;
        modal_ficha_paciente.querySelector('#vacunas strong').textContent = datos.vacunas;
        modal_ficha_paciente.querySelector('#enfermedades strong').textContent = datos.enfermedades;
        modal_ficha_paciente.querySelector('#pruebas_diagnosticas strong').textContent = datos.pruebas_diagnosticas;
        modal_ficha_paciente.querySelector('#plato_preferido strong').textContent = datos.plato_preferido;
        modal_ficha_paciente.querySelector('#bebida_preferida strong').textContent = datos.bebida_preferida;
        modal_ficha_paciente.querySelector('#debilidad strong').textContent = datos.debilidad;
        modal_ficha_paciente.querySelector('#cocina strong').textContent = datos.cocina;
        modal_ficha_paciente.querySelector('#comidas_x_dia strong').textContent = datos.comidas_x_dia;
        modal_ficha_paciente.querySelector('#horario_comidas strong').textContent = datos.horario_comidas;
        modal_ficha_paciente.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_ficha_paciente.addEventListener('shown.bs.modal', onModalShown);
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