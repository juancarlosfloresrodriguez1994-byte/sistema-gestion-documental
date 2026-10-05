@extends('layouts.template')

@section('titlePage', 'Tipo Usuario | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card">
                        <form method="POST" action="{{ route('tipo-usuario.update', $elemento->id) }}"
                            id="form_tipoUsuarioEditar">
                            @csrf
                            @method('PUT')

                            {{-- HEADER --}}
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="ki-outline ki-people fs-2x text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h2 class="mb-1">
                                                Editar Tipo de Usuario
                                            </h2>
                                            <span class="text-muted fw-semibold fs-6">
                                                Configurar accesos y permisos para:
                                                <span class="text-gray-800 fw-bold">
                                                    {{ $elemento->descripcion }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- BODY --}}
                            <div class="card-body py-8">

                                {{-- NOMBRE --}}
                                <div class="row mb-8">
                                    <div class="col-md-6">
                                        <label class="fs-5 fw-semibold form-label">Descripción</label>
                                        <input type="text"
                                            class="form-control form-control-solid validate_modal"
                                            id="descripcion" name="descripcion"
                                            value="{{ $elemento->descripcion }}" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- ACCESOS Y PERMISOS --}}
                                <div class="row g-6">
                                    @foreach ($menuAll as $im => $menu)
                                        <div class="col-12 col-md-6 col-xl-4">

                                            <div class="card card-bordered h-100 shadow-sm">

                                                <div class="card-header min-h-70px">
                                                    <div class="card-title">
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-40px me-4">
                                                                <div class="symbol-label bg-light-warning">
                                                                    <i
                                                                        class="ki-outline ki-category fs-2 text-warning"></i>
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

                                                <div class="card-body pt-5">
                                                    @foreach ($menu['submenu'] as $is => $submenu)
                                                        @php
                                                            $collapseId = 'edit_submenu_' . $im . '_' . $is;
                                                            $submenuChecked = revisarMenu(
                                                                $submenu['id'],
                                                                $elemento->id,
                                                                'tipo',
                                                            );
                                                        @endphp

                                                        <div class="border border-gray-300 border-dashed rounded mb-4">

                                                            <div
                                                                class="d-flex align-items-center justify-content-between px-4 py-4">
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="form-check form-check-custom form-check-solid me-4">
                                                                        <input class="form-check-input submenu-check"
                                                                            type="checkbox"
                                                                            value="{{ $submenu['id'] }}"
                                                                            name="accesos[]"
                                                                            id="edit_submenu_check_{{ $im }}_{{ $is }}"
                                                                            data-permission-container="#{{ $collapseId }}"
                                                                            {{ $submenuChecked ? 'checked' : '' }}>
                                                                    </div>
                                                                    <label
                                                                        class="fw-semibold fs-6 text-gray-800 cursor-pointer"
                                                                        for="edit_submenu_check_{{ $im }}_{{ $is }}">
                                                                        {{ $submenu['descripcion'] }}
                                                                    </label>
                                                                </div>

                                                                @if (count($submenu['permisos']) > 0)
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-icon btn-light-warning"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#{{ $collapseId }}"
                                                                        aria-expanded="{{ $submenuChecked ? 'true' : 'false' }}">
                                                                        <i class="ki-outline ki-down fs-2"></i>
                                                                    </button>
                                                                @endif
                                                            </div>

                                                            @if (count($submenu['permisos']) > 0)
                                                                <div id="{{ $collapseId }}"
                                                                    class="collapse {{ $submenuChecked ? 'show' : '' }}">
                                                                    <div class="separator separator-dashed"></div>
                                                                    <div class="px-5 py-4 bg-light rounded-bottom">
                                                                        <div
                                                                            class="text-muted fw-bold fs-7 text-uppercase mb-4">
                                                                            Permisos
                                                                        </div>

                                                                        @foreach ($submenu['permisos'] as $permiso)
                                                                            @php
                                                                                $permisoChecked = revisarPermiso(
                                                                                    $permiso['name'],
                                                                                    $elemento->id,
                                                                                    'tipo',
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
                                                                                            id="edit_permiso_{{ $im }}_{{ $is }}_{{ $loop->index }}"
                                                                                            {{ $permisoChecked ? 'checked' : '' }}>
                                                                                    </div>
                                                                                    <label
                                                                                        class="text-gray-700 fw-semibold cursor-pointer"
                                                                                        for="edit_permiso_{{ $im }}_{{ $is }}_{{ $loop->index }}">
                                                                                        {{ $permiso['descripcion'] }}
                                                                                    </label>
                                                                                </div>
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
                            <div class="card-footer">
                                <div class="d-flex justify-content-end align-items-center">
                                    <a href="{{ route('tipo-usuario.index') }}" class="btn btn-light me-3">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                        Cancelar
                                    </a>

                                    <button type="submit" class="btn btn-primary" id="btnGuardarTipoUsuario">
                                        <span class="indicator-label">
                                            <i class="ki-outline ki-check fs-2"></i>
                                            Actualizar
                                        </span>
                                        <span class="indicator-progress">
                                            Guardando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2">
                                            </span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
