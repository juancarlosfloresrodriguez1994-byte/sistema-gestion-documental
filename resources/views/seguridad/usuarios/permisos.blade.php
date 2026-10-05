@extends('layouts.template')

@section('titlePage', 'Asignar Permisos | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_content" class="app-content flex-column-fluid">

                <div id="kt_app_content_container" class="app-container container-fluid">

                    <form method="POST" action="{{ route('usuarios.asignar-permisos', $elemento) }}">

                        @csrf
                        @method('PUT')

                        <!--begin::Card-->
                        <div class="card">

                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">

                                <div class="card-title">

                                    <div class="d-flex align-items-center">

                                        <div class="symbol symbol-50px me-5">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="ki-outline ki-shield-tick fs-2x text-primary"></i>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column">

                                            <h2 class="mb-1">
                                                Asignar accesos y permisos
                                            </h2>

                                            <span class="text-muted fw-semibold fs-6">
                                                Usuario:
                                                <span class="text-gray-800 fw-bold">
                                                    {{ $usuario->nombres }}
                                                    {{ $usuario->apellidos }}
                                                </span>
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!--end::Card header-->


                            <!--begin::Card body-->
                            <div class="card-body py-8">

                                <div class="row g-6">

                                    @foreach ($menuAll as $im => $menu)
                                        <div class="col-12 col-md-6 col-xl-4">

                                            <!--begin::Module card-->
                                            <div class="card card-bordered h-100 shadow-sm">

                                                <!--begin::Header-->
                                                <div class="card-header min-h-70px">

                                                    <div class="card-title">

                                                        <div class="d-flex align-items-center">

                                                            <div class="symbol symbol-40px me-4">

                                                                <div class="symbol-label bg-light-primary">

                                                                    <i class="ki-outline ki-category fs-2 text-primary"></i>

                                                                </div>

                                                            </div>

                                                            <div>
                                                                <span class="fw-bold fs-5 text-gray-800">
                                                                    {{ $menu['descripcion'] }}
                                                                </span>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                                <!--end::Header-->


                                                <!--begin::Body-->
                                                <div class="card-body pt-5">

                                                    @foreach ($menu['submenu'] as $is => $submenu)
                                                        @php
                                                            $collapseId = 'submenu_' . $im . '_' . $is;

                                                            if ($submenu['id'] == 0) {
                                                                $submenuChecked = $menuAdicional;
                                                            } else {
                                                                $submenuChecked = revisarMenu(
                                                                    $submenu['id'],
                                                                    $elemento,
                                                                    'usuario',
                                                                );
                                                            }
                                                        @endphp


                                                        <!--begin::Submenu-->
                                                        <div class="border border-gray-300 border-dashed rounded mb-4">

                                                            <!--begin::Submenu header-->
                                                            <div
                                                                class="d-flex align-items-center justify-content-between px-4 py-4">

                                                                <div class="d-flex align-items-center">

                                                                    <div
                                                                        class="form-check form-check-custom form-check-solid me-4">

                                                                        <input class="form-check-input submenu-check"
                                                                            type="checkbox" value="{{ $submenu['id'] }}"
                                                                            name="accesos[]"
                                                                            id="submenu_check_{{ $im }}_{{ $is }}"
                                                                            data-permission-container="#{{ $collapseId }}"
                                                                            {{ $submenuChecked ? 'checked' : '' }}>

                                                                    </div>

                                                                    <label
                                                                        class="fw-semibold fs-6 text-gray-800 cursor-pointer"
                                                                        for="submenu_check_{{ $im }}_{{ $is }}">

                                                                        {{ $submenu['descripcion'] }}

                                                                    </label>

                                                                </div>


                                                                @if (count($submenu['permisos']) > 0)
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-icon btn-light-primary"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#{{ $collapseId }}"
                                                                        aria-expanded="{{ $submenuChecked ? 'true' : 'false' }}">

                                                                        <i class="ki-outline ki-down fs-2"></i>

                                                                    </button>
                                                                @endif

                                                            </div>
                                                            <!--end::Submenu header-->


                                                            @if (count($submenu['permisos']) > 0)
                                                                <!--begin::Permissions-->
                                                                <div id="{{ $collapseId }}"
                                                                    class="collapse {{ $submenuChecked ? 'show' : '' }}">

                                                                    <div class="separator separator-dashed">
                                                                    </div>

                                                                    <div class="px-5 py-4 bg-light rounded-bottom">

                                                                        <div
                                                                            class="text-muted fw-bold fs-7 text-uppercase mb-4">

                                                                            Permisos

                                                                        </div>


                                                                        @foreach ($submenu['permisos'] as $permiso)
                                                                            @php
                                                                                $permisoChecked = revisarPermiso(
                                                                                    $permiso['name'],
                                                                                    $elemento,
                                                                                    'usuario',
                                                                                );
                                                                            @endphp


                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mb-4">

                                                                                <div class="d-flex align-items-center">

                                                                                    <div
                                                                                        class="form-check form-check-custom form-check-solid me-3">

                                                                                        <input
                                                                                            class="form-check-input permiso-check"
                                                                                            type="checkbox"
                                                                                            value="{{ $permiso['name'] }}"
                                                                                            name="permisos[]"
                                                                                            id="permiso_{{ $im }}_{{ $is }}_{{ $loop->index }}"
                                                                                            {{ $permisoChecked ? 'checked' : '' }}>

                                                                                    </div>

                                                                                    <label
                                                                                        class="text-gray-700 fw-semibold cursor-pointer"
                                                                                        for="permiso_{{ $im }}_{{ $is }}_{{ $loop->index }}">

                                                                                        {{ $permiso['descripcion'] }}

                                                                                    </label>

                                                                                </div>

                                                                            </div>
                                                                        @endforeach

                                                                    </div>

                                                                </div>
                                                                <!--end::Permissions-->
                                                            @endif

                                                        </div>
                                                        <!--end::Submenu-->
                                                    @endforeach

                                                </div>
                                                <!--end::Body-->

                                            </div>
                                            <!--end::Module card-->

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                            <!--end::Card body-->


                            <!--begin::Card footer-->
                            <div class="card-footer">

                                <div class="d-flex justify-content-end align-items-center">

                                    <a href="{{ route('usuarios.index') }}" class="btn btn-light me-3">

                                        <i class="ki-outline ki-cross fs-2"></i>

                                        Cancelar

                                    </a>


                                    <button type="submit" class="btn btn-primary" id="btnGuardarPermisos">

                                        <span class="indicator-label">

                                            <i class="ki-outline ki-check fs-2"></i>

                                            Guardar permisos

                                        </span>

                                        <span class="indicator-progress">

                                            Guardando...

                                            <span class="spinner-border spinner-border-sm align-middle ms-2">
                                            </span>

                                        </span>

                                    </button>

                                </div>

                            </div>
                            <!--end::Card footer-->

                        </div>
                        <!--end::Card-->

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
