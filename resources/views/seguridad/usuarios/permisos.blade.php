@extends('layouts.template')

@section('titlePage', 'Asignar Permisos | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card">
                        <form method="POST" action="{{ route('usuarios.asignar-permisos', $elemento) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
                                <div class="mb-13 text-center">

                                </div>
                                <div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true"
                                    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_modal_new_address_header"
                                    data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px">

                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class=" fs-5 fw-semibold form-label">Asignar Accesos y Permisos  - {{ $usuario->nombres}} {{ $usuario->apellidos}}</label>
                                        </div>
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
                                                                        @if ($submenu['id'] == 0)
                                                                         <input type="checkbox" value="{{ $submenu['id'] }}" name="accesos[]" class="form-check-input" {{ $menuAdicional ? 'checked' : '' }} />
                                                                        @else
                                                                        <input type="checkbox" value="{{ $submenu['id'] }}" name="accesos[]" class="form-check-input" {{ revisarMenu($submenu['id'], $elemento, 'usuario') ? 'checked' : '' }} />
                                                                        @endif
                                                                    </div>

                                                                    <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">
                                                                        {{ $submenu['descripcion'] }}</h4>

                                                                </div>
                                                                <div id="kt_job_1_2{{ $im . '-' . $is }}"
                                                                    class="collapse fs-6 ms-1">
                                                                    @foreach ($submenu['permisos'] as $permiso)
                                                                        <div class="mb-4">
                                                                            <div
                                                                                class="d-flex align-items-center ps-10 mb-n1">
                                                                                <div
                                                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                    <input type="checkbox" value="{{ $permiso['name'] }}" name="permisos[]" class="form-check-input" {{ revisarPermiso($permiso['name'], $elemento, 'usuario') ? 'checked' : '' }} />
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
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-danger me-3 " >Cancelar</a>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Asignar</span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
