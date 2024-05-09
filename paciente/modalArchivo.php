<div class="modal fade" id="modalArchivo" tabindex="-1" aria-labelledby="modalArchivoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modal_header_archivo">
                <h5 class="modal-title" id="modalArchivoLabel"><b>Subir Archivo</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formularioSubirArchivo">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="titulo_archivo" class="form-label"><b>Título:</b></label>
                                <input type="text" class="form-control" id="titulo_archivo" name="titulo_archivo" placeholder="Ingrese el título" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="fecha_archivo" class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control" id="fecha_archivo" name="fecha_archivo" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_archivo" class="form-label"><b>Descripción:</b></label>
                        <textarea class="form-control" id="descripcion_archivo" name="descripcion_archivo" rows="3" placeholder="Ingrese una descripción" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label"><b>Seleccionar archivo:</b></label>
                        <input class="form-control" type="file" id="archivo" name="archivo" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSubirArchivo">Subir archivo</button>
                <button type="button" class="btn btn-secondary" id="archivo_cerrar" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>