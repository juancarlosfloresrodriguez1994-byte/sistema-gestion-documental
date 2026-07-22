<div class="modal fade" id="modal_tipoUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>Agregar Tipo de Usuario</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7">
                <form id="kt_modal_update_role_form" class="form" action="#">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                        data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header"
                        data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span>Tipo de Usuario</span>
                            </label>

                            <input class="form-control form-control-solid" name="descripcion" id="descripcion" />
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
                            @foreach ($menuAll as $im => $menu)
                                <div class="col-md-4">
                                    <div class="card card-flush h-md-100">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>{{ $menu['descripcion'] }}</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-1">
                                            <div class="m-0">
                                                @foreach ($menu['submenu'] as $is => $submenu)
                                                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#kt_job_1_2{{ $im . '-' . $is }}">
                                                        <div
                                                            class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input name="idusuario"
                                                                class="form-check-input idresoluciones" data-id=""
                                                                type="checkbox" value="" />
                                                        </div>

                                                        <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">
                                                            {{ $submenu['descripcion'] }}</h4>

                                                    </div>
                                                    <div id="kt_job_1_2{{ $im . '-' . $is }}"
                                                        class="collapse fs-6 ms-1">
                                                        @foreach ($submenu['permisos'] as $permiso)
                                                            <div class="mb-4">
                                                                <div class="d-flex align-items-center ps-10 mb-n1">
                                                                    <div
                                                                        class="form-check form-check-sm form-check-custom form-check-solid">
                                                                        <input class="form-check-input"
                                                                            name="permisos[]" type="checkbox"
                                                                            value="{{ $permiso['name'] }}" />
                                                                    </div>

                                                                    <div class="text-gray-600 fw-semibold fs-6">
                                                                        {{ $permiso['descripcion'] }}
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                                <div class="separator separator-dashed"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-guardar" class="btn btn-primary">
                            <span class="indicator-label">Agregar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
