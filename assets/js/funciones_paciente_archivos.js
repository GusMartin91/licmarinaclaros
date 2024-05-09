const contenedor_archivos = document.getElementById('contenedor_archivos')
const boton_subir_archivo = document.getElementById('boton_subir_archivo')
const formularioSubirArchivo = document.getElementById('formularioSubirArchivo');
const modalArchivo = document.getElementById('modalArchivo');
const input_titulo_archivo = document.getElementById('titulo_archivo');
const input_descripcion_archivo = document.getElementById('descripcion_archivo');
const input_fecha_archivo = document.getElementById('fecha_archivo');
const input_archivo = document.getElementById('archivo');
const archivos = document.getElementById('archivos-tab');
const archivo_cerrar = document.getElementById('archivo_cerrar');

function archivos_paciente() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_archivos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response) {
                response.sort((a, b) => new Date(b.fecha_archivo) - new Date(a.fecha_archivo));
                contenedor_archivos.innerHTML = '';
                for (let i = 0; i < response.length; i++) {
                    let datosBoton = {
                        titulo: response[i].titulo || '',
                        id_archivo: response[i].id_archivo || '',
                        dni_paciente: response[i].dni_paciente || '',
                        descripcion: response[i].descripcion || '',
                        url_archivo: response[i].url_archivo || '',
                        fecha_archivo: response[i].fecha_archivo || '',
                    }
                    let fechaFormateada = moment(datosBoton.fecha_archivo).format("DD-MM-YYYY");
                    let extension = datosBoton.url_archivo.split('.').pop().toLowerCase();
                    // Definir el icono según la extensión del archivo
                    let icono = '';
                    switch (extension) {
                        case 'pdf':
                            icono = '<i class="fa-solid fa-file-pdf fa-2xl mt-4 mb-4" style="color:red;"></i>';
                            break;
                        case 'doc':
                        case 'docx':
                            icono = '<i class="fa-solid fa-file-word fa-2xl mt-4 mb-4" style="color:blue;"></i>';
                            break;
                        case 'xls':
                        case 'xlsx':
                            icono = '<i class="fa-solid fa-file-excel fa-2xl mt-4 mb-4" style="color:green;"></i>';
                            break;
                        case 'txt':
                            icono = '<i class="fa-solid fa-file-alt fa-2xl mt-4 mb-4" style="color:black;"></i>';
                            break;
                        case 'ppt':
                        case 'pptx':
                            icono = '<i class="fa-solid fa-file-powerpoint fa-2xl mt-4 mb-4" style="color:orange;"></i>';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            icono = '<i class="fa-solid fa-file-image fa-2xl mt-4 mb-4" style="color:purple;"></i>';
                            break;
                        case 'zip':
                        case 'rar':
                            icono = '<i class="fa-solid fa-file-archive fa-2xl mt-4 mb-4" style="color:brown;"></i>';
                            break;
                        default:
                            icono = '<i class="fa-solid fa-file fa-2xl mt-4 mb-4" style="color:gray;"></i>'; // Icono genérico para otras extensiones
                    }
                    contenedor_archivos.innerHTML += `
                    <div class="card col-sm-12 col-md-3 mb-2 p-4">
                        <div class="row no-gutters">
                            <div class="col-sm-12 col-md-6 p-0 card-body text-center">
                                <h5 class="card-title">${fechaFormateada}</h5>
                                <h5 class="card-title">${datosBoton.url_archivo}</h5>
                                <a href="../assets/file_server/${datosBoton.dni_paciente}/files/${datosBoton.url_archivo}" target="_blank">
                                    ${icono}
                                </a>
                                <h5 class="card-title">${datosBoton.titulo}</h5>
                                <p class="card-text">${datosBoton.descripcion}</p>
                                <p class="card-text"><button title="Eliminar" class="btn btn-lg" data-bs-datos='${JSON.stringify(datosBoton)}' onclick="gestionarEliminar_archivo(event)"'><i class="fa-solid fa-trash" style="color:red;"></i></button></p>
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
        let titulo = input_titulo_archivo.value;
        let descripcion = input_descripcion_archivo.value;
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
                            archivo_cerrar.click()
                            archivos.click()
                        }
                    });
                } else {
                    // Si hay un error, muestra un mensaje de error
                    Swal.fire({
                        icon: data.icon,
                        title: 'Error',
                        text: 'Error al subir el archivo: ' + data.error,
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
    input_titulo_archivo.focus()
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
        text: '¿Desea eliminar definitivamente el archivo?',
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
                                archivo_cerrar.click()
                                archivos.click()
                            }
                        });
                    } else {
                        // Si hay un error, muestra un mensaje de error
                        Swal.fire({
                            icon: data.icon,
                            title: 'Error',
                            text: 'Error al subir el archivo: ' + data.error,
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
