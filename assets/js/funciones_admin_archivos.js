const contenedor_archivos = document.getElementById('contenedor_archivos')
const input_dni_paciente_archivos = document.getElementById('dni_paciente_archivos')
function buscar_archivos() {
    let dni_paciente = input_dni_paciente_archivos.value
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_archivos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response && response.length > 0) {
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
                        </div>
                    </div>
                    `
                }
            } else {
                contenedor_archivos.innerHTML = '';
                contenedor_archivos.innerHTML = '<p>No se encontraron resultados.</p>';
                Swal.fire({
                    icon: 'error',
                    title: 'No se encontraron resultados',
                    text: 'Inténtalo de nuevo',
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    };
    xhr.send(`dni=${dni_paciente}`);
}

function archivos_admin() {
    contenedor_archivos.innerHTML = '';
    input_dni_paciente_archivos.value = '';
    input_dni_paciente_archivos.focus()
}
input_dni_paciente_archivos.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        buscar_archivos();
    }
});