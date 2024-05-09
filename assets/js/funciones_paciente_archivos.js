const contenedor_archivo = document.getElementById('contenedor_archivo')
const boton_subir_archivo = document.getElementById('boton_subir_archivo')
const formularioSubirArchivo = document.getElementById('formularioSubirArchivo');
const modalArchivo = document.getElementById('modalArchivo');
// // const input_titulo = document.getElementById('titulo');
// const input_descripcion = document.getElementById('descripcion');
// const input_fecha_archivo = document.getElementById('fecha_archivo');
// const input_archivo = document.getElementById('archivo');
// const archivo = document.getElementById('archivo-tab');
// const archivo_cerrar = document.getElementById('archivo_cerrar');

function archivos_paciente() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_archivos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response) {
                response.sort((a, b) => new Date(b.fecha_archivo) - new Date(a.fecha_archivo));
                contenedor_archivo.innerHTML = '';
                for (let i = 0; i < response.length; i++) {
                    var fechaFormateada = moment(response[i].fecha_archivo).format("DD-MM-YYYY");
                    let datosBoton = {
                        titulo: response[i].titulo || '',
                        id_archivo: response[i].id_archivo || '',
                        dni_paciente: response[i].dni_paciente || '',
                        descripcion: response[i].descripcion || '',
                        url_archivo: response[i].url_archivo || '',
                        fecha_archivo: response[i].fecha_archivo || '',
                    }
                    contenedor_archivo.innerHTML += `
                    <div class="card col-sm-12 col-md-5 mb-2">
                        <div class="row no-gutters">
                            <div class="col-sm-12 col-md-6 p-0">
                                <a href="../assets/file_server/${response[i].dni_paciente}/files/${response[i].url_archivo}" target="_blank">
                                    ${response[i].url_archivo}
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${fechaFormateada}</h5>
                                    <h5 class="card-title">${response[i].titulo}</h5>
                                    <p class="card-text">${response[i].descripcion}</p>
                                    <p class="card-text"><button title="Eliminar" class="btn btn-lg" data-bs-datos='${JSON.stringify(datosBoton)}' onclick="gestionarEliminar_archivo(event)"'><i class="fa-solid fa-trash" style="color:red;"></i></button></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                }
            }
        }
    };
    xhr.send(`dni=${dni_paciente}`);
}

document.addEventListener("DOMContentLoaded", function () {
    // Maneja el clic en el botón "Subir archivo"
    document.getElementById("btnSubirArchivo").addEventListener("click", function () {
        // Obtén los datos del formulario
        let titulo = input_titulo.value;
        let descripcion = input_descripcion.value;
        let fecha_archivo = input_fecha_archivo.value;
        let archivo = input_archivo.files[0];
        let movimiento = "A";

        // Crea un objeto FormData para enviar los datos
        var formData = new FormData();
        formData.append("titulo", titulo);
        formData.append("descripcion", descripcion);
        formData.append("fecha_archivo", fecha_archivo);
        formData.append("archivo", archivo);
        formData.append("movimiento", movimiento);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Configura la solicitud
        xhr.open("POST", "./backend_archivo.php", true);

        // Define el manejo de la respuesta
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor
                var data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // Si la subida es exitosa, muestra un Sweet Alert con los datos recibidos
                    Swal.fire({
                        icon: data.icon,
                        title: '<p><b>Título:</b> ' + data.titulo + '</p>',
                        html: '<p><b>Mensaje:</b> ' + data.message + '</p>' + '<p><b>Ruta Archivo:</b> ' + data.ruta_archivo + '</p>',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            archivo.click()
                            archivo_cerrar.click()
                        }
                    });
                } else {
                    // Si hay un error, muestra un mensaje de error
                    Swal.fire({
                        icon: data.icon,
                        title: 'Error',
                        text: 'Error al subir la archivo: ' + data.error,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            archivo_cerrar.click()
                        }
                    });
                }
            } else {
                // Maneja errores de la solicitud
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al enviar los datos al servidor.',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        archivo_cerrar.click()
                    }
                });
            }
        };

        // Define el manejo de errores de red
        xhr.onerror = function () {
            alert("Error de red al intentar enviar los datos al servidor.");
        };

        // Envía la solicitud con los datos del formulario
        xhr.send(formData);
    });
});

modalArchivo.addEventListener('shown.bs.modal', function () {
    input_titulo.focus()
});
modalArchivo.addEventListener('hidden.bs.modal', function () {
    formularioSubirArchivo.reset()
});

function gestionarEliminar_archivo(event) {
    let button = event.currentTarget;
    let datos = JSON.parse(button.getAttribute('data-bs-datos'));

    // Mostrar SweetAlert para confirmar la eliminación
    Swal.fire({
        title: '¡Atención!',
        text: '¿Desea eliminar definitivamente la archivo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma la eliminación, procede con la solicitud AJAX
            var formData = new FormData();
            formData.append("titulo", datos.titulo);
            formData.append("descripcion", datos.descripcion);
            formData.append("fecha_archivo", datos.fecha_archivo);
            formData.append("url_archivo", datos.url_archivo);
            formData.append("id_archivo", datos.id_archivo);
            formData.append("movimiento", 'B');

            // Crea una nueva instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configura la solicitud
            xhr.open("POST", "./backend_archivo.php", true);

            // Define el manejo de la respuesta
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        Swal.fire({
                            icon: data.icon,
                            title: '<p><b>Título:</b> ' + data.titulo + '</p>',
                            html: '<p><b>Mensaje:</b> ' + data.message + '</p>' + '<p><b>Ruta del archivo:</b> ' + data.ruta_archivo + '</p>',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                archivo.click()
                                archivo_cerrar.click()
                            }
                        });
                    } else {
                        // Si hay un error, muestra un mensaje de error
                        Swal.fire({
                            icon: data.icon,
                            title: 'Error',
                            text: 'Error al subir la archivo: ' + data.error,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                archivo_cerrar.click()
                            }
                        });
                    }
                } else {
                    // Maneja errores de la solicitud
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al enviar los datos al servidor.',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            archivo_cerrar.click()
                        }
                    });
                }
            };
            // Define el manejo de errores de red
            xhr.onerror = function () {
                alert("Error de red al intentar enviar los datos al servidor.");
            };

            // Envía la solicitud con los datos del formulario
            xhr.send(formData);
        }
    });
}
