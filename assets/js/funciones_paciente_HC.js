const consulta_paciente = document.getElementById('consulta_paciente');
let historial_consulta = document.getElementById('historial_consulta-tab');
function tabla_HC_paciente() {
    $('#tabla_HC_paciente').DataTable().destroy()
    $(document).ready(function () {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./consulta_HC.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response) {
                    $('#tabla_HC_paciente tbody').empty();
                    for (let i = 0; i < response.length; i++) {
                        let datosBoton = {
                            id_consulta: response[i].id_consulta || '',
                            dni_paciente: response[i].dni_paciente || '',
                            nombre: response[i].nombre || '',
                            apellido: response[i].apellido || '',
                            peso: response[i].peso || '',
                            fecha_consulta: response[i].fecha_consulta || '',
                            observaciones_nutri: response[i].observaciones_nutri || '',
                            observaciones_paciente: response[i].observaciones_paciente || '',
                            movimiento: response[i].movimiento || '',
                        }
                        let fila = '<tr>' +
                            '<td>' + datosBoton.fecha_consulta + '</td>' +
                            '<td>' + datosBoton.peso + '</td>' +
                            '<td>' + datosBoton.observaciones_nutri + '</td>' +
                            '<td>' + datosBoton.observaciones_paciente +
                            ` <button title="Editar" onclick="botonEditar_observaciones()" class="btn btn-sm btn-warning me-1" data-bs-datos='${JSON.stringify(datosBoton)}'><i class="fa-solid fa-pen-to-square"></i></button>` +
                            '</td>' +
                            '</tr>';
                        $('#tabla_HC_paciente tbody').append(fila);
                    }
                    $('#tabla_HC_paciente').DataTable({
                        order: [[0, 'desc']],
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
                                "sortable": false,
                                "targets": [2,3]
                            }, {
                                targets: [0],
                                render: DataTable.render.datetime('DD/MM/YYYY'),
                            }, {
                                width: "10px",
                                targets: [1]
                            }, {
                                width: "35%",
                                targets: [2,3]
                            }
                        ],
                    });
                }
            }
        };
        xhr.send(`dni=${dni_paciente}`);
    });
}

function botonEditar_observaciones() {
}
