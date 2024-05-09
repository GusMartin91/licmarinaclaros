const form_HC = document.getElementById('form_HC');
const modal_HC = document.getElementById('modal_HC');
const modal_HCLabel = document.getElementById('modal_HCLabel');
const submit_HC = document.getElementById('submit_HC');
const input_dni_HC = document.getElementById('dni_HC');
const consulta_paciente = document.getElementById('consulta_paciente');
let historial_consulta = document.getElementById('historial_consulta-tab');
function tabla_HC() {
    $('#tabla_HC').DataTable().destroy()
    $(document).ready(function () {
        $.ajax({
            url: "./consulta_HC.php",
            dataType: "json",
            success: function (response) {
                $('#tabla_HC tbody').empty();
                for (let i = 0; i < response.length; i++) {
                    let datosBoton = {
                        id_consulta: response[i].id_consulta || '',
                        dni_paciente: response[i].dni_paciente || '',
                        nombre: response[i].nombre || '',
                        apellido: response[i].apellido || '',
                        foto_perfil: response[i].foto_perfil || '',
                        peso: response[i].peso || '',
                        fecha_consulta: response[i].fecha_consulta || '',
                        observaciones_nutri: response[i].observaciones_nutri || '',
                        movimiento: response[i].movimiento || '',
                    }
                    let fila = '<tr>' +
                        '<td>' + datosBoton.id_consulta + '</td>' +
                        '<td>' + datosBoton.fecha_consulta + '</td>' +
                        (datosBoton.foto_perfil !== 'default_profile.png' ?
                            '<td><img src="../assets/file_server/' + datosBoton.dni_paciente + '/profile/' + datosBoton.foto_perfil + '" alt="Foto de perfil de ' + datosBoton.apellido + ', ' + datosBoton.nombre + '" class="imagen-perfil2"> ' + datosBoton.dni_paciente + '</td>' :
                            '<td>' + datosBoton.dni_paciente + '</td>') + '<td>' + datosBoton.apellido + ', ' + datosBoton.nombre + '</td>' +
                        '<td>' +
                        `<button title="Editar" onclick="botonEditar_HC()" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modal_HC" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-pen-to-square"></i></button>` +
                        `<button title="Eliminar" class="btn btn-danger btn-sm" onclick="gestionarEliminar_HC(event)" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-trash"></i></button>` +
                        '</td>' +
                        '</tr>';

                    $('#tabla_HC tbody').append(fila);
                }
                $('#tabla_HC').DataTable({
                    order: [[1, 'desc']],
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
                        title: 'LISTADO DE CONSULTAS',
                        filename: 'Listado de Consultas',
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
                        filename: 'Listado de Consultas del ' + dateTime,
                        title: 'Listado de Consultas',
                        messageTop: 'Listado de Consultas',
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
                            "targets": [4]
                        }, {
                            "sortable": false,
                            "targets": [4]
                        }, {
                            targets: [1],
                            render: DataTable.render.datetime('DD/MM/YYYY'),
                        }, {
                            width: "8%",
                            targets: [4]
                        }, {
                            width: "50px",
                            targets: [0]
                        }, {
                            width: "70px",
                            targets: [1]
                        }
                    ],
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
}

function botonNuevo_HC() {
    function onModalShown(event) {
        modal_HCLabel.innerText = "Ingresar nueva consulta"
        submit_HC.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Ingresar'
        modal_HC.querySelector('.modal-body #movimiento_HC').value = 'A'
        modal_HC.querySelector('.modal-body #dni_HC').disabled = false
        modal_HC.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_HC.addEventListener('shown.bs.modal', onModalShown);
}
function botonEditar_HC() {
    function onModalShown(event) {
        modal_HCLabel.innerText = "Modificar consulta del paciente"
        submit_HC.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Modificar'
        modal_HC.querySelector('.modal-body #movimiento_HC').value = 'U'
        let button = event.relatedTarget
        let datos = JSON.parse(button.getAttribute('data-bs-datos'));
        modal_HC.querySelector('.modal-body #id_consulta').value = datos.id_consulta
        modal_HC.querySelector('.modal-body #peso_HC').value = datos.peso
        modal_HC.querySelector('.modal-body #dni_HC').value = datos.dni_paciente
        modal_HC.querySelector('.modal-body #dni_HC').disabled = true
        modal_HC.querySelector('.modal-body #fecha_consulta_HC').value = datos.fecha_consulta
        modal_HC.querySelector('.modal-body #observaciones_HC').value = datos.observaciones_nutri
        modal_HC.querySelector('.modal-body #fecha_consulta_HC_actual').value = datos.fecha_consulta
        modal_HC.querySelector('.modal-body #peso_HC_actual').value = datos.peso
        modal_HC.querySelector('.modal-body #observaciones_HC_actual').value = datos.observaciones_nutri
        modal_HC.removeEventListener('shown.bs.modal', onModalShown);
    }
    modal_HC.addEventListener('shown.bs.modal', onModalShown);
}

function gestionarEliminar_HC(event) {
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
            let form = $('<form action=\'backend_HC.php\' method=\'post\'></form>');
            form.append('<input type=\'hidden\' name=\'id_consulta\' value=\'' + datos.id_consulta + '\'>');
            form.append('<input type=\'hidden\' name=\'movimiento\' value=\'' + datos.movimiento + '\'>'); for (let dato in datos) {
                form.append(`<input type='hidden' name='${dato}_actual' value='${datos[dato]}'>`);
            }
            $('body').append(form);
            form.submit();
        }
    });
}

modal_HC.addEventListener('hidden.bs.modal', function () {
    form_HC.reset()
    consulta_paciente.innerHTML = ``
});
modal_HC.addEventListener('shown.bs.modal', function () {
    input_dni_HC.focus()
});
function verificarDNI_HC() {
    let dni_HC = input_dni_HC.value;
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./backend_verificar_dni_HC.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.dniExiste) {
                consulta_paciente.innerHTML = `Nueva Consulta de: </br><b>${respuesta.nombre} ${respuesta.apellido}</b>`;
                consulta_paciente.style.color = 'green';
            } else {
                consulta_paciente.innerHTML = `DNI no registrado como paciente`;
                consulta_paciente.style.color = 'red';
            }
        } else {
            console.error("Error al verificar el DNI: " + xhr.statusText);
        }
    };
    xhr.send(`dni=${dni_HC}`);
}