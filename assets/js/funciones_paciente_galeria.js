const contenedor_galeria = document.getElementById('contenedor_galeria')
const boton_subir_imagen = document.getElementById('boton_subir_imagen')
const formularioSubirImagen = document.getElementById('formularioSubirImagen');
const modalGaleria = document.getElementById('modalGaleria');
const input_titulo = document.getElementById('titulo');
const input_descripcion = document.getElementById('descripcion');
const input_fecha = document.getElementById('fecha');
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
                contenedor_galeria.innerHTML = ''
                for (let i = 0; i < response.length; i++) {
                    var fechaFormateada = moment(response[i].fecha_imagen).format("DD-MM-YYYY");
                    contenedor_galeria.innerHTML += `
                    <div class="card col-sm-12 col-md-5 mb-2">
                        <div class="row no-gutters">
                            <div class="col-sm-12 col-md-6 p-0">
                                <a href="../assets/img/gallery/${response[i].url_imagen}" target="_blank">
                                    <img src="../assets/img/gallery/${response[i].url_imagen}" class="card-img" alt="...">
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${fechaFormateada}</h5>
                                    <h5 class="card-title">${response[i].titulo}</h5>
                                    <p class="card-text">${response[i].descripcion}</p>
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
        let fecha = input_fecha.value;
        let imagen = input_imagen.files[0];

        // Crea un objeto FormData para enviar los datos
        var formData = new FormData();
        formData.append("titulo", titulo);
        formData.append("descripcion", descripcion);
        formData.append("fecha", fecha);
        formData.append("imagen", imagen);

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
                        icon: 'success',
                        title: '¡Imagen subida exitosamente!',
                        html: '<p><b>Título:</b> ' + data.titulo + '</p>' +
                            '<p><b>Descripción:</b> ' + data.descripcion + '</p>',
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
                        icon: 'error',
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
