@extends('layouts.template')

@section('titlePage', 'Usuarios | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card">
                        <div class="card-header align-items-center py-5 gap-5">
                            <div class="d-flex">
                                <a class="btn btn-sm btn-icon btn-primary btn-active-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#modal_usuarios" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Agregar">
                                    <i class="ki-outline ki-plus fs-2 m-0"></i>
                                </a>
                                <a class="btn btn-sm btn-icon btn-primary btn-active-primary me-2 btn_editarUsuarios"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                    <i class="ki-outline ki-pencil fs-2 m-0"></i>
                                </a>
                                <a class="btn btn-sm btn-icon btn-primary btn-active-danger me-2 btn_estadoUsuarios" 
                                data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Cambiar de estado">
                                    <i class="ki-outline ki-abstract-26 fs-2 m-0"></i>
                                </a>
                                <a class="btn btn-sm btn-icon btn-primary btn-active-primary me-2 btn_permisos" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Permisos">
                                    <i class="ki-outline ki-information fs-2 m-0"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                    <input type="text" class="form-control form-control-solid w-450px ps-13"
                                        id="documentoUsuario" name="documentoUsuario" placeholder="Buscar Nombres y Dni" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <table class="table table-row-bordered  gy-4 gs-9" id="table_usuarios">
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

    @include('seguridad.usuarios.modalcreate')
    @include('seguridad.usuarios.editar')
    @include('seguridad.usuarios.modalEstado')

@endsection
