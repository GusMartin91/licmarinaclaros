<div class="modal fade" id="modal_foto" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Recortar Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="hidden" id="dni_paciente_foto">
                            <input type="hidden" id="id_historial_foto">
                            <img id="img-original" class="img-fluid">
                        </div>
                        <div class="col-md-4">
                            <div id="div-preview" class="preview img-fluid"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-crop">Recortar</button>
            </div>
        </div>
    </div>
</div>

<script type="module">
    const $file = document.getElementById("file-upload")
    const $image = document.getElementById("img-original")

    const $modal = document.getElementById("modal_foto")
    const $modalr = document.getElementById("modalConfirma")
    const objmodalr = new bootstrap.Modal($modalr)
    const objmodal = new bootstrap.Modal($modal, {
        keyboard: false
    })

    $file.addEventListener("change", function(e) {
        const load_image = function(url) {
            $image.src = url
            objmodal.show()
        }

        const files = e.target.files

        if (files && files.length > 0) {
            const objfile = files[0]
            if (URL) {
                const url = URL.createObjectURL(objfile)
                load_image(url)
            } else if (FileReader) {
                const reader = new FileReader()
                reader.onload = function(e) {
                    load_image(reader.result)
                }
                reader.readAsDataURL(objfile)
            }
        }
    })

    const $btncrop = document.getElementById("btn-crop")
    $btncrop.addEventListener("click", function() {
        var dni_paciente_foto = document.getElementById("dni_paciente_foto").value;
        var id_historial_foto = document.getElementById("id_historial_foto").value;
        const canvas = cropper.getCroppedCanvas()

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append(' image', blob, 'image.jpg');
            formData.append('dni_paciente_foto', dni_paciente_foto);
            formData.append('id_historial_foto', id_historial_foto);
            const url = "./guarda_imagen.php";
            fetch(url, {
                    method: "POST",
                    body: formData
                }).then(response => response.json())
                .then(function(result) {
                    $file.value = ""
                    objmodal.hide()
                    objmodalr.show()
                    var boton = document.getElementById("botonCerrante");
                    setTimeout(function() {
                        boton.click();
                    }, 1500);
                    document.getElementById("pacientes-tab").click();
                })
        });
    });

    let cropper = null

    $modal.addEventListener("shown.bs.modal", function() {

        const originalImageWidth = $image.naturalWidth;
        const originalImageHeight = $image.naturalHeight;

        const containerWidth = $image.parentElement.offsetWidth;
        const minCropBox = containerWidth / (originalImageWidth / 500)

        cropper = new Cropper($image, {
            preview: document.getElementById("div-preview"),
            viewMode: 2,
            aspectRatio: 1,
            minCropBoxWidth: minCropBox,
            minCropBoxHeight: minCropBox,
            zoomable: false,
        });
        if (originalImageHeight < 600 || originalImageWidth < 600) {

            objmodal.hide()
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La imagen debe ser mayor a 600x600 píxeles',
            });
        }
        if (originalImageHeight > 1800 || originalImageWidth > 1800) {
            objmodal.hide();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La imagen no puede superar los 1800x1800 píxeles',
            });
            cropper.destroy();
        }
    })

    $modal.addEventListener("hidden.bs.modal", function() {
        cropper.destroy()
        cropper = null
    })
</script>
</body>

</html>