@extends('layouts.template')

@section('titlePage', 'Mesa partes | Ugel Alto Amazonas')

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
                                    Filtros de Búsqueda
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#" class="btn btn-primary fw-bold" data-bs-toggle="modal"
                                        data-bs-target="#recdartar_tramiteExterno">
                                        <i class="ki-outline ki-plus fs-2"></i>

                                        REDACTAR
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                {{-- AÑO --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">
                                        Año
                                    </label>
                                    <select class="form-select form-select-solid" id="anioPago">
                                        <option value="">
                                            Seleccionar
                                        </option>
                                        <option value="2026">
                                            2026
                                        </option>
                                    </select>
                                </div>

                                {{-- MES --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">
                                        Mes
                                    </label>
                                    <select class="form-select form-select-solid" id="mesPago">
                                        <option value="">
                                            Seleccionar
                                        </option>
                                        <option value="5">
                                            Mayo
                                        </option>
                                    </select>
                                </div>
                                {{-- BUSCADOR --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Buscar
                                    </label>
                                    <div class="position-relative">
                                        <i
                                            class="ki-outline ki-magnifier fs-3 position-absolute ms-5 top-50 translate-middle-y"></i>
                                        <input type="text" class="form-control form-control-solid ps-13"
                                            id="documentoUsuario" name="documentoUsuario"
                                            placeholder="Buscar Nombres y DNI" />
                                    </div>
                                </div>

                                {{-- <div class="col-md-4 d-flex align-items-end gap-3">
                                    <button class="btn btn-light-primary">
                                        <i class="ki-outline ki-exit-up fs-2"></i>
                                        Exportar
                                    </button>
                                    <button class="btn btn-success">
                                        <i class="ki-outline ki-check fs-2"></i>
                                        Procesar
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card">

                        <div class="card-body py-4">
                            <table class="table table-row-bordered  gy-4 gs-9" id="table_tramiteExterno">
                                <thead
                                    class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75"
                                    style="background-color: #0073B7 !important;
                                     color: #fff !important;">
                                    <tr>
                                        <td></td>
                                        <td class="min-w-150px">#</td>
                                        <td class="min-w-250px">Documento</td>
                                        <td class="min-w-150px">Nombre Completo</td>
                                        <td class="min-w-150px">Tipo Usuario</td>
                                        <td class="min-w-150px">Estado</td>
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

    @include('mesaPartes.redactar')


@endsection
