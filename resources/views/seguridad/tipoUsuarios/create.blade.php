<div class="modal fade" id="modal_tipoUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header border-0 pb-0 pt-8 px-8">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px me-4">
                        <div class="symbol-label bg-light-primary">
                            <i class="ki-outline ki-people fs-2x text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold text-gray-900 mb-0">Agregar Tipo de Usuario</h2>
                        <span class="text-muted fs-7">Configura los accesos y permisos del nuevo rol</span>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-8 pt-6 pb-4">
                <form id="form_tipoUsuarioGuardar" class="form" action="#">

                    {{-- Input nombre --}}
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold form-label mb-2 required">
                            Nombre del Tipo de Usuario
                        </label>
                        <input class="form-control form-control-solid validate_modal"
                            name="descripcion"
                            id="descripcion_tipoUsuario"
                            placeholder="Ej: ADMINISTRADOR, DOCENTE, SECRETARIA..." />
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Separador --}}
                    <div class="d-flex align-items-center mb-6">
                        <div class="flex-grow-1 border-bottom border-gray-300"></div>
                        <span class="mx-4 text-muted fw-semibold fs-7 text-uppercase">Asignar Accesos y Permisos</span>
                        <div class="flex-grow-1 border-bottom border-gray-300"></div>
                    </div>

                    {{-- Grid de módulos --}}
                    <div class="mh-400px scroll-y pe-3">
                        <div class="row g-5">
                            @foreach ($menuAll as $im => $menu)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card card-bordered border-gray-300 shadow-sm h-100 card-modulo-acceso">

                                        {{-- Módulo header --}}
                                        <div class="card-header min-h-55px px-5 py-0 border-bottom border-gray-200"
                                            style="background: linear-gradient(135deg, #f8f9fd 0%, #eef2f9 100%);">
                                            <div class="card-title">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-30px me-3">
                                                        <div class="symbol-label bg-primary bg-opacity-10">
                                                            <i class="ki-outline ki-category fs-5 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <span class="fw-bold fs-6 text-gray-800">
                                                        {{ $menu['descripcion'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Módulo body --}}
                                        <div class="card-body px-5 py-4">
                                            @foreach ($menu['submenu'] as $is => $submenu)
                                                <div class="border border-gray-200 border-dashed rounded-2 mb-3 overflow-hidden">

                                                    {{-- Submenu item --}}
                                                    <div class="d-flex align-items-center justify-content-between px-4 py-3 bg-gray-50 cursor-pointer"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#kt_crear_tipo_{{ $im . '-' . $is }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input name="accesos[]"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="{{ $submenu['id'] }}" />
                                                            </div>
                                                            <span class="fw-semibold text-gray-700 fs-7">
                                                                {{ $submenu['descripcion'] }}
                                                            </span>
                                                        </div>
                                                        @if (count($submenu['permisos']) > 0)
                                                            <i class="ki-outline ki-down fs-5 text-gray-500"></i>
                                                        @endif
                                                    </div>

                                                    {{-- Permisos --}}
                                                    @if (count($submenu['permisos']) > 0)
                                                        <div id="kt_crear_tipo_{{ $im . '-' . $is }}" class="collapse">
                                                            <div class="separator separator-dashed"></div>
                                                            <div class="px-4 py-3">
                                                                @foreach ($submenu['permisos'] as $permiso)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                            <input class="form-check-input"
                                                                                name="permisos[]" type="checkbox"
                                                                                value="{{ $permiso['name'] }}" />
                                                                        </div>
                                                                        <span class="text-gray-600 fs-7">
                                                                            {{ $permiso['descripcion'] }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="d-flex justify-content-end gap-3 pt-8 border-top border-gray-200 mt-6">
                        <button type="reset" class="btn btn-light btn-sm px-6" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-4 me-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardarTipoUsuario" class="btn btn-primary btn-sm px-6">
                            <i class="ki-outline ki-check fs-4 me-1"></i>
                            <span class="indicator-label">Guardar</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
