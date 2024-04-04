let contenedor_galeria = document.getElementById('contenedor_galeria')

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
                    response[i].id_consulta
                    contenedor_galeria.innerHTML += `
                    <div class="card col-md-12 col-xl-5 mb-2">
                        <div class="row no-gutters">
                            <div class="col-sm-12 col-md-6 p-0">
                                <a href="../assets/img/gallery/${response[i].url_imagen}" target="_blank">
                                    <img src="../assets/img/gallery/${response[i].url_imagen}" class="card-img" alt="...">
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="card-body text-center">
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