@extends('layouts.template')

@section('titlePage', 'Historial de Trámites Virtuales | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid p-0">

                    {{-- Contenedor Centralizado --}}
                    <div class="tv-content-container">

                        {{-- Header con Título y Botón Agregar Trámite Virtual --}}
                        <div class="tv-header-card">
                            <div class="tv-header-title-box">
                                <h1>Historial de Trámites Virtuales</h1>
                                <p>Consulte el estado, progreso y documentos de sus expedientes generados virtualmente.</p>
                            </div>
                            <div>
                                <a href="{{ route('tramite-virtual.create') }}" class="btn-tv-create">
                                    <i class="ki-outline ki-plus-circle fs-2"></i>
                                    <span>AGREGAR TRÁMITE VIRTUAL</span>
                                </a>
                            </div>
                        </div>

                        {{-- Barra de Estadísticas / Resumen de trámites realizados --}}
                        <div class="tv-stats-grid">
                            {{-- Total --}}
                            <div class="tv-stat-card">
                                <div class="tv-stat-icon-wrapper tv-stat-icon-total">
                                    <i class="ki-outline ki-document"></i>
                                </div>
                                <div>
                                    <div class="tv-stat-number">{{ $totalTramites }}</div>
                                    <div class="tv-stat-label">Total Realizados</div>
                                </div>
                            </div>

                            {{-- Pendientes --}}
                            <div class="tv-stat-card">
                                <div class="tv-stat-icon-wrapper tv-stat-icon-pending">
                                    <i class="ki-outline ki-time"></i>
                                </div>
                                <div>
                                    <div class="tv-stat-number">{{ $totalPendientes }}</div>
                                    <div class="tv-stat-label">Por Recibir / Pendientes</div>
                                </div>
                            </div>

                            {{-- En Proceso --}}
                            <div class="tv-stat-card">
                                <div class="tv-stat-icon-wrapper tv-stat-icon-process">
                                    <i class="ki-outline ki-route"></i>
                                </div>
                                <div>
                                    <div class="tv-stat-number">{{ $totalEnProceso }}</div>
                                    <div class="tv-stat-label">En Trámite / Proceso</div>
                                </div>
                            </div>

                            {{-- Atendidos --}}
                            <div class="tv-stat-card">
                                <div class="tv-stat-icon-wrapper tv-stat-icon-success">
                                    <i class="ki-outline ki-check-circle"></i>
                                </div>
                                <div>
                                    <div class="tv-stat-number">{{ $totalAtendidos }}</div>
                                    <div class="tv-stat-label">Atendidos / Concluidos</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla y Contenido Principal del Historial --}}
                        <div class="tv-main-card">

                            {{-- Filtros y Búsqueda --}}
                            <div class="tv-filter-bar">
                                <form action="{{ route('tramite-virtual.index') }}" method="GET" class="d-flex align-items-center flex-wrap gap-3 w-100">
                                    
                                    {{-- Buscador --}}
                                    <div class="tv-search-box flex-grow-1">
                                        <i class="ki-outline ki-magnifier tv-search-icon"></i>
                                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por N° expediente, asunto o texto..." value="{{ request('buscar') }}">
                                    </div>

                                    {{-- Filtro Estado --}}
                                    <div style="min-width: 180px;">
                                        <select name="estado" class="form-select" onchange="this.form.submit()">
                                            <option value="">Todos los Estados</option>
                                            <option value="POR_RECIBIR" {{ request('estado') == 'POR_RECIBIR' ? 'selected' : '' }}>Por Recibir</option>
                                            <option value="PROCESO" {{ request('estado') == 'PROCESO' ? 'selected' : '' }}>En Proceso</option>
                                            <option value="ATENDIDO" {{ request('estado') == 'ATENDIDO' ? 'selected' : '' }}>Atendido</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary fw-bold">
                                        <i class="ki-outline ki-filter fs-4 me-1"></i> Filtrar
                                    </button>

                                    @if(request('buscar') || request('estado'))
                                        <a href="{{ route('tramite-virtual.index') }}" class="btn btn-light fw-bold text-muted">
                                            Limpiar
                                        </a>
                                    @endif

                                </form>
                            </div>

                            {{-- Tabla de Expedientes con margen interno --}}
                            <div class="tv-table-wrapper table-responsive">
                                <table class="table tv-table">
                                    <thead>
                                        <tr>
                                            <th>N° Expediente</th>
                                            <th>Fecha y Hora</th>
                                            <th>Asunto / Solicitud</th>
                                            <th>Tipo Documento</th>
                                            <th>Área Actual</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tramites as $tramite)
                                            <tr>
                                                <td>
                                                    <span class="tv-exp-badge">
                                                        {{ $tramite->numero_expediente }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-gray-800">{{ $tramite->fecha_registro }}</div>
                                                    <div class="text-muted fs-8">{{ $tramite->hora_registro ?? 'N/D' }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-gray-800 text-truncate" style="max-width: 260px;" title="{{ $tramite->asunto }}">
                                                        {{ $tramite->asunto }}
                                                    </div>
                                                    <div class="text-muted fs-8 text-truncate" style="max-width: 260px;">
                                                        {{ $tramite->descripcion }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-primary fw-bold">
                                                        {{ $tramite->tipo_documento_nombre ?? 'FUT' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-700 fw-semibold">
                                                        {{ $tramite->area_actual_nombre ?? 'Mesa de Partes' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($tramite->estado === 'POR_RECIBIR' || $tramite->estado === 'PENDIENTE')
                                                        <span class="badge-status-por-recibir">
                                                            <i class="ki-outline ki-time fs-8 me-1 text-warning"></i>
                                                            POR RECIBIR
                                                        </span>
                                                    @elseif ($tramite->estado === 'ATENDIDO' || $tramite->estado === 'CONCLUIDO')
                                                        <span class="badge-status-atendido">
                                                            <i class="ki-outline ki-check-circle fs-8 me-1 text-success"></i>
                                                            ATENDIDO
                                                        </span>
                                                    @else
                                                        <span class="badge-status-en-proceso">
                                                            <i class="ki-outline ki-route fs-8 me-1 text-primary"></i>
                                                            {{ str_replace('_', ' ', $tramite->estado) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn-action-view" 
                                                            onclick="mostrarDetalleTramite('{{ $tramite->numero_expediente }}', '{{ addslashes($tramite->asunto) }}', '{{ $tramite->estado }}', '{{ $tramite->fecha_registro }}', '{{ $tramite->tipo_documento_nombre ?? 'FUT' }}', '{{ $tramite->area_actual_nombre ?? 'Mesa de Partes' }}')" 
                                                            title="Ver Detalle">
                                                        <i class="ki-outline ki-eye fs-4"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <div class="tv-empty-state">
                                                        <div class="tv-empty-icon">
                                                            <i class="ki-outline ki-folder-down"></i>
                                                        </div>
                                                        <h4 class="fw-bold text-gray-800 mb-2">No se encontraron trámites virtuales</h4>
                                                        <p class="text-muted fs-7 mb-4">
                                                            Aún no ha registrado ningún trámite virtual en el sistema. Puede iniciar uno nuevo fácilmente.
                                                        </p>
                                                        <a href="{{ route('tramite-virtual.create') }}" class="btn-tv-create">
                                                            <i class="ki-outline ki-plus fs-3"></i>
                                                            Registrar Mi Primer Trámite
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Paginación --}}
                            @if ($tramites->hasPages())
                                <div class="d-flex justify-content-between align-items-center p-5 border-top">
                                    <div class="text-muted fs-7">
                                        Mostrando {{ $tramites->firstItem() }} a {{ $tramites->lastItem() }} de {{ $tramites->total() }} trámites
                                    </div>
                                    <div>
                                        {{ $tramites->links() }}
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script para Notificaciones y Modal Moderno con SweetAlert2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        confirmButton: 'btn btn-primary fw-bold px-6'
                    },
                    buttonsStyling: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-danger fw-bold px-6'
                    },
                    buttonsStyling: false
                });
            @endif
        });

        function mostrarDetalleTramite(expediente, asunto, estado, fecha, tipoDoc, area) {
            let badgeClass = 'badge-light-warning';
            let iconHeader = 'ki-time text-warning';
            let estadoTexto = estado.replace('_', ' ');

            if (estado === 'ATENDIDO' || estado === 'CONCLUIDO') {
                badgeClass = 'badge-light-success';
                iconHeader = 'ki-check-circle text-success';
            } else if (estado === 'PROCESO') {
                badgeClass = 'badge-light-primary';
                iconHeader = 'ki-route text-primary';
            }

            Swal.fire({
                title: `<div class="d-flex align-items-center justify-content-center gap-2 text-gray-900 fs-3 fw-bold">
                            <i class="ki-outline ki-document text-primary fs-2hx"></i>
                            <span>Detalle de Trámite</span>
                        </div>`,
                html: `
                    <div class="text-start py-2">
                        <div class="p-4 rounded-3 bg-light border mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fs-8 fw-semibold">N° DE EXPEDIENTE</span>
                                <span class="badge badge-light-primary fw-bolder fs-7 px-3 py-1 font-monospace">${expediente}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-8 fw-semibold">ESTADO ACTUAL</span>
                                <span class="badge ${badgeClass} fw-bolder fs-8 px-3 py-1">${estadoTexto}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Asunto / Solicitud</label>
                            <div class="p-3 bg-white rounded border text-gray-800 fw-semibold fs-7">${asunto}</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Tipo de Documento</label>
                                <div class="text-gray-800 fw-bold fs-7">${tipoDoc}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Fecha de Registro</label>
                                <div class="text-gray-800 fw-bold fs-7">${fecha}</div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Área Actual / Destino</label>
                                <div class="text-gray-800 fw-semibold fs-7 d-flex align-items-center gap-1">
                                    <i class="ki-outline ki-bank text-primary fs-6"></i>
                                    ${area}
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                confirmButtonText: '<i class="ki-outline ki-check fs-4 me-1"></i> Cerrar',
                customClass: {
                    popup: 'rounded-4 shadow-lg p-5',
                    confirmButton: 'btn btn-primary fw-bold px-6 py-2'
                },
                buttonsStyling: false,
                width: '540px'
            });
        }
    </script>

@endsection
