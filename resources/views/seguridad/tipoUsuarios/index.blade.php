@extends('layouts.template')

@section('titlePage', 'Tipo Usuario | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">

                    {{-- CARD FILTROS --}}
                    <div class="card mb-7">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">
                                    Tipos de Usuario
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#" class="btn btn-primary fw-bold" data-bs-toggle="modal"
                                        data-bs-target="#modal_tipoUsuario">
                                        <i class="ki-outline ki-plus fs-2"></i>
                                        Agregar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                {{-- BUSCADOR --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Buscar
                                    </label>
                                    <div class="position-relative">
                                        <i
                                            class="ki-outline ki-magnifier fs-3 position-absolute ms-5 top-50 translate-middle-y"></i>
                                        <input type="text" class="form-control form-control-solid ps-13"
                                            id="documentoTipousuario" name="documentoTipousuario"
                                            placeholder="Buscar Tipo Usuario" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TABLA --}}
                    <div class="card">
                        <div class="card-body py-4">
                            <table class="table table-row-bordered gy-4 gs-9" id="table_tipoUsuario">
                                <thead
                                    class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75"
                                    style="background-color: #0073B7 !important; color: #fff !important;">
                                    <tr>
                                        <td class="min-w-50px">#</td>
                                        <td class="min-w-250px">Descripción</td>
                                        <td class="min-w-150px">Acciones</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('seguridad.tipoUsuarios.create')

@endsection
