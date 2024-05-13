const contenedor_galeria = document.getElementById('contenedor_galeria')
const input_dni_paciente_galeria = document.getElementById('dni_paciente_galeria')
function buscar_galeria() {
    let dni_paciente = input_dni_paciente_galeria.value
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./consulta_galeria.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response && response.length > 0) {
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
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                }
            } else {
                contenedor_galeria.innerHTML = '';
                contenedor_galeria.innerHTML = '<p>No se encontraron resultados.</p>';
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

function galeria_admin() {
    contenedor_galeria.innerHTML = '';
    input_dni_paciente_galeria.value = '';
    input_dni_paciente_galeria.focus()
}
input_dni_paciente_galeria.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        buscar_galeria();
    }
});