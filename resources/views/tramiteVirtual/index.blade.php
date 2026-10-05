@extends('layouts.template')

@section('titlePage', 'Trámite Virtual | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">

                    <div class="card mb-7">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">
                                    Trámite Virtual
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#" class="btn btn-primary fw-bold">
                                        <i class="ki-outline ki-plus fs-2"></i>
                                        Nuevo Trámite
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!-- Contenido del Trámite Virtual -->
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                <i class="ki-outline ki-information fs-2tx text-primary me-4"></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">Módulo en Desarrollo</h4>
                                        <div class="fs-6 text-gray-700">El apartado de trámite virtual se encuentra en proceso de implementación. Por ahora solo se tiene la vista y acceso inicial configurado.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
