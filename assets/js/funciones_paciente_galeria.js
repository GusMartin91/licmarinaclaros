const contenedor_galeria = document.getElementById('contenedor_galeria')
const boton_subir_imagen = document.getElementById('boton_subir_imagen')
const formularioSubirImagen = document.getElementById('formularioSubirImagen');
const modalGaleria = document.getElementById('modalGaleria');
const input_titulo = document.getElementById('titulo_galeria');
const input_descripcion = document.getElementById('descripcion_galeria');
const input_fecha_imagen = document.getElementById('fecha_imagen_galeria');
const input_imagen = document.getElementById('imagen');
const galeria_imagenes = document.getElementById('galeria_imagenes-tab');
const galeria_imagenes_cerrar = document.getElementById('galeria_imagenes_cerrar');

function galeria_paciente() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_galeria.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response) {
                response.sort((a, b) => new Date(b.fecha_imagen) - new Date(a.fecha_imagen));
                contenedor_galeria.innerHTML = '';
                for (let i = 0; i < response.length; i++) {
                    let datosBoton = {
                        titulo: response[i].titulo || '',
                        id_galeria: response[i].id_galeria || '',
                        dni_paciente: response[i].dni_paciente || '',
                        descripcion: response[i].descripcion || '',
                        url_imagen: response[i].url_imagen || '',
                        fecha_imagen: response[i].fecha_imagen || '',
                    }
                    let fechaFormateada = moment(datosBoton.fecha_imagen).format("DD-MM-YYYY");
                    contenedor_galeria.innerHTML += `
                    <div class="card col-sm-12 col-md-5 mb-2">
                        <div class="row no-gutters">
                            <div class="col-sm-12 col-md-6 p-0">
                                <a href="../assets/file_server/${datosBoton.dni_paciente}/gallery/${datosBoton.url_imagen}" target="_blank">
                                    <img src="../assets/file_server/${datosBoton.dni_paciente}/gallery/${datosBoton.url_imagen}" class="card-img" alt="...">
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${fechaFormateada}</h5>
                                    <h5 class="card-title">${datosBoton.titulo}</h5>
                                    <p class="card-text">${datosBoton.descripcion}</p>
                                    <p class="card-text"><button title="Eliminar" class="btn btn-lg" data-bs-datos='${JSON.stringify(datosBoton)}' onclick="gestionarEliminar_galeria(event)"'><i class="fa-solid fa-trash" style="color:red;"></i></button></p>
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
    // Maneja el clic en el botón "Subir imagen"
    document.getElementById("btnSubirImagen").addEventListener("click", function () {
        // Obtén los datos del formulario
        let titulo = input_titulo.value;
        let descripcion = input_descripcion.value;
        let fecha_imagen = input_fecha_imagen.value;
        let imagen = input_imagen.files[0];
        let movimiento = "A";

        // Crea un objeto FormData para enviar los datos
        var formData = new FormData();
        formData.append("titulo", titulo);
        formData.append("descripcion", descripcion);
        formData.append("fecha_imagen", fecha_imagen);
        formData.append("imagen", imagen);
        formData.append("movimiento", movimiento);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Configura la solicitud
        xhr.open("POST", "./backend_galeria.php", true);

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
                        html: '<p><b>Mensaje:</b> ' + data.message + '</p>' + '<p><b>Ruta Archivo:</b> ' + data.ruta_imagen + '</p>',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            galeria_imagenes_cerrar.click()
                            galeria_imagenes.click()
                        }
                    });
                } else {
                    // Si hay un error, muestra un mensaje de error
                    Swal.fire({
                        icon: data.icon,
                        title: 'Error',
                        text: 'Error al subir la imagen: ' + data.error,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            galeria_imagenes_cerrar.click()
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
                        galeria_imagenes_cerrar.click()
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

modalGaleria.addEventListener('shown.bs.modal', function () {
    input_titulo.focus()
});
modalGaleria.addEventListener('hidden.bs.modal', function () {
    formularioSubirImagen.reset()
});

function gestionarEliminar_galeria(event) {
    let button = event.currentTarget;
    let datos = JSON.parse(button.getAttribute('data-bs-datos'));

    // Mostrar SweetAlert para confirmar la eliminación
    Swal.fire({
        title: '¡Atención!',
        text: '¿Desea eliminar definitivamente la imagen?',
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
            formData.append("fecha_imagen", datos.fecha_imagen);
            formData.append("url_imagen", datos.url_imagen);
            formData.append("id_galeria", datos.id_galeria);
            formData.append("movimiento", 'B');

            // Crea una nueva instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configura la solicitud
            xhr.open("POST", "./backend_galeria.php", true);

            // Define el manejo de la respuesta
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        Swal.fire({
                            icon: data.icon,
                            title: '<p><b>Título:</b> ' + data.titulo + '</p>',
                            html: '<p><b>Mensaje:</b> ' + data.message + '</p>' + '<p><b>Ruta del archivo:</b> ' + data.ruta_imagen + '</p>',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                galeria_imagenes.click()
                                galeria_imagenes_cerrar.click()
                            }
                        });
                    } else {
                        // Si hay un error, muestra un mensaje de error
                        Swal.fire({
                            icon: data.icon,
                            title: 'Error',
                            text: 'Error al subir la imagen: ' + data.error,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                galeria_imagenes_cerrar.click()
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
                            galeria_imagenes_cerrar.click()
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
