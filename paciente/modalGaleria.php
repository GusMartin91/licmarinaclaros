<div class="modal fade" id="modalGaleria" tabindex="-1" aria-labelledby="modalGaleriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modal_header_galeria">
                <h5 class="modal-title" id="modalGaleriaLabel"><b>Subir Imagen</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formularioSubirImagen">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="titulo_galeria" class="form-label"><b>Título:</b></label>
                                <input type="text" class="form-control" id="titulo_galeria" name="titulo_galeria" placeholder="Ingrese el título" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="fecha_imagen_galeria" class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control" id="fecha_imagen_galeria" name="fecha_imagen_galeria" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_galeria" class="form-label"><b>Descripción:</b></label>
                        <textarea class="form-control" id="descripcion_galeria" name="descripcion_galeria" rows="3" placeholder="Ingrese una descripción" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label"><b>Seleccionar imagen:</b></label>
                        <input class="form-control" type="file" id="imagen" name="imagen" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSubirImagen">Subir imagen</button>
                <button type="button" class="btn btn-secondary" id="galeria_imagenes_cerrar" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>