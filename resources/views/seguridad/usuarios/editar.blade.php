<div class="modal fade" id="modal_usuariosEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded">
            <div class="modal-header justify-content-end border-0 pb-0">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form method="POST" id="form_usuarioEditar" class="urlUsuarios">
                @csrf
                @method('PUT')
                <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Editar Usuarios</h1>
                    </div>
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_new_address_header"
                        data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px">

                        <div class="row mb-5">
                            <div class="col-md-4 fv-row">
                                <label class="fs-6 fw-semibold form-label">Documento</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-solid validate_modal"
                                        id="documentoUsuarioBuscar" name="documento" />
                                    <button type="button" class="btn btn-icon btn-primary kt_btn_1"
                                        id="consultaUsuario">
                                        <span class="indicator-label"><i
                                                class="ki-outline ki-magnifier fs-2"></i></span>
                                        <span class="indicator-progress">
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Nombres</label>
                                <input type="text" class="form-control form-control-solid validate_modal"
                                    id="nombresUsuario" name="nombres" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid validate_modal"
                                    id="apellidosUsuario" name="apellidos" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-4 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Nickname</label>
                                <input type="text" class="form-control form-control-solid validate_modal"
                                    id="nickname" name="nickname" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Contraseña</label>
                                <input type="password" class="form-control form-control-solid validate_modal"
                                    id="password" name="password" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class=" fs-5 fw-semibold form-label">Tipo Usuario</label>
                                <select name="tipoUsuario_id" id="tipoUsuario_id" data-control="select2"
                                    data-dropdown-parent="#modal_usuariosEditar" data-placeholder="Tipo Usuario"
                                    class="form-select form-select-solid validate_modal">
                                    <option value="">Tipo Usuario</option>

                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>


                    </div>
                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-guardar" class="btn btn-primary">
                            <span class="indicator-label">Agregar</span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </div>
            </form>
        </div>
    </div>
</div>
