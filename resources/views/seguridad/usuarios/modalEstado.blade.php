<div class="modal fade" id="modal_estadoUsuarios" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form method="POST" id="from_estadoUsuario" class="urlestado">
                @csrf
                <div class="modal-body scroll-y pt-0 pb-15">
                    <div class="mw-lg-600px mx-auto">
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Estado</label>
                                <select name="estado" id="estado"
                                    data-dropdown-parent="#modal_solicitanteEditar" data-placeholder="Estado"
                                    class="form-select form-select-solid validate_modal">
                                    <option value="">Estado</option>
                                    <option value="1">ACTIVAR</option>
                                    <option value="0">DESACTIVAR</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button  type="submit" class="btn btn-primary me-3">
                            Aceptar
                        </button>
                        <button type="reset" class="btn btn-danger " data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
