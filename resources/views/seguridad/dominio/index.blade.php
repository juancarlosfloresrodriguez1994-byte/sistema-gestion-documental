@extends('layouts.template')

@section('titlePage', 'Gestión de Dominios | UGEL Alto Amazonas')

@section('content')
<div class="dom-container">

    <!-- Header Principal -->
    <div class="dom-page-header">
        <div class="dom-page-header__left">
            <div class="dom-page-badge">
                <i class="ki-outline ki-shield-tick fs-6 me-1"></i> Seguridad & Estructura Orgánica
            </div>
            <h1 class="dom-page-title">Gestión de Dominios Institucionales</h1>
            <p class="dom-page-subtitle">
                Administración y catálogo del organigrama oficial (Áreas, Equipos y Subequipos) de la UGEL Alto Amazonas.
            </p>
        </div>
        <div class="dom-page-header__right">
            <button type="button" class="btn btn-primary dom-btn-create" id="btnNuevoDominio" data-bs-toggle="modal" data-bs-target="#modalDominio">
                <i class="ki-outline ki-plus fs-2 me-1"></i> Nuevo Dominio
            </button>
        </div>
    </div>

    <!-- KPI Cards Institucionales -->
    <div class="dom-stats-grid">
        <div class="dom-stat-card dom-stat-card--total">
            <div class="dom-stat-card__icon">
                <i class="ki-outline ki-bank fs-2x"></i>
            </div>
            <div class="dom-stat-card__info">
                <span class="dom-stat-card__value" id="kpiTotalDominios">{{ $totalDominios }}</span>
                <span class="dom-stat-card__label">Dominios Activos</span>
            </div>
        </div>

        <div class="dom-stat-card dom-stat-card--areas">
            <div class="dom-stat-card__icon">
                <i class="ki-outline ki-abstract-26 fs-2x"></i>
            </div>
            <div class="dom-stat-card__info">
                <span class="dom-stat-card__value" id="kpiTotalAreas">{{ $totalAreas }}</span>
                <span class="dom-stat-card__label">Áreas (Nivel 1)</span>
            </div>
        </div>

        <div class="dom-stat-card dom-stat-card--equipos">
            <div class="dom-stat-card__icon">
                <i class="ki-outline ki-people fs-2x"></i>
            </div>
            <div class="dom-stat-card__info">
                <span class="dom-stat-card__value" id="kpiTotalEquipos">{{ $totalEquipos }}</span>
                <span class="dom-stat-card__label">Equipos (Nivel 2)</span>
            </div>
        </div>

        <div class="dom-stat-card dom-stat-card--subequipos">
            <div class="dom-stat-card__icon">
                <i class="ki-outline ki-element-11 fs-2x"></i>
            </div>
            <div class="dom-stat-card__info">
                <span class="dom-stat-card__value" id="kpiTotalSubequipos">{{ $totalSubequipos }}</span>
                <span class="dom-stat-card__label">Subequipos (Nivel 3)</span>
            </div>
        </div>

        <div class="dom-stat-card dom-stat-card--cas">
            <div class="dom-stat-card__icon">
                <i class="ki-outline ki-folder fs-2x"></i>
            </div>
            <div class="dom-stat-card__info">
                <span class="dom-stat-card__value" id="kpiTotalCas">{{ $totalCas }}</span>
                <span class="dom-stat-card__label">Convocatorias CAS</span>
            </div>
        </div>
    </div>

    <!-- Card Principal con Pestañas y Tabla -->
    <div class="card dom-card">
        <!-- Pestañas de Filtro por Nivel -->
        <div class="dom-tabs-wrapper">
            <button type="button" class="dom-tab-btn active" data-filtro="ALL">
                <i class="ki-outline ki-category fs-5"></i> Todos los Dominios
            </button>
            <button type="button" class="dom-tab-btn" data-filtro="AREA">
                <i class="ki-outline ki-abstract-26 fs-5"></i> Áreas (Nivel 1)
            </button>
            <button type="button" class="dom-tab-btn" data-filtro="EQUIPO">
                <i class="ki-outline ki-people fs-5"></i> Equipos (Nivel 2)
            </button>
            <button type="button" class="dom-tab-btn" data-filtro="SUBEQUIPO">
                <i class="ki-outline ki-element-11 fs-5"></i> Subequipos / Oficinas (Nivel 3)
            </button>
            <button type="button" class="dom-tab-btn" data-filtro="CONVOCATORIA">
                <i class="ki-outline ki-folder fs-5"></i> Convocatorias CAS
            </button>
        </div>

        <!-- Toolbar de Búsqueda y Actualizar -->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5 text-gray-500"></i>
                    <input type="text" id="buscarDominio" class="form-control form-control-solid w-250px w-md-350px ps-13"
                        placeholder="Buscar por dependencia, siglas, área..." />
                </div>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-light-primary btn-sm" id="btnRecargarTabla">
                    <i class="ki-outline ki-arrows-circle fs-3 me-1"></i> Actualizar
                </button>
            </div>
        </div>

        <!-- Cuerpo de Tabla -->
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="table_dominios">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-60px text-center"># ID</th>
                            <th class="min-w-120px">Nivel / Tipo</th>
                            <th class="min-w-260px">Dependencia Orgánica</th>
                            <th class="min-w-280px">Jerarquía / Dependencia Padre</th>
                            <th class="min-w-180px">Responsable / Titular</th>
                            <th class="min-w-100px text-center">Personal</th>
                            <th class="text-end min-w-120px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL PARA CREAR / EDITAR DOMINIO
     ============================================================ -->
<div class="modal fade" id="modalDominio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dom-modal-content">
            <form id="formDominio" autocomplete="off">
                <input type="hidden" id="dominio_id" name="id" value="">
                <input type="hidden" id="metodo_form" value="POST">

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-1" id="modalDominioTitulo">Registrar Nuevo Dominio</h2>
                        <p class="text-muted fs-7 mb-0">Complete la información para registrar la dependencia en el organigrama.</p>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-6 px-lg-10">

                    <!-- Selección de Nivel / Tipo de Dominio -->
                    <div class="fv-row mb-6">
                        <label class="required fs-6 fw-bold mb-2">Nivel en el Organigrama</label>
                        <select name="domi_tipo" id="domi_tipo" class="form-select form-select-solid" required>
                            <option value="">Seleccione el nivel...</option>
                            <option value="AREA">[ÁREA] Nivel 1 - Órgano o Dirección Matriz</option>
                            <option value="EQUIPO">[EQUIPO] Nivel 2 - Equipo de Trabajo (Dependiente de un Área)</option>
                            <option value="SUBEQUIPO">[SUBEQUIPO] Nivel 3 - Oficina o Unidad Operativa (Dependiente de un Equipo)</option>
                        </select>
                        <div class="invalid-feedback" id="error_domi_tipo"></div>
                    </div>

                    <!-- Cascada: Área Padre (Visible para EQUIPO y SUBEQUIPO) -->
                    <div class="fv-row mb-6 d-none" id="wrapperAreaPadre">
                        <label class="required fs-6 fw-bold mb-2">Área Matriz a la que pertenece</label>
                        <select name="area_padre_id" id="area_padre_id" class="form-select form-select-solid">
                            <option value="">Seleccione el área matriz...</option>
                            @foreach ($areas as $a)
                                <option value="{{ $a->id }}">{{ $a->domi_nombre }} @if($a->domi_siglas) ({{ $a->domi_siglas }}) @endif</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_area_padre_id"></div>
                    </div>

                    <!-- Cascada: Equipo Padre (Visible solo para SUBEQUIPO) -->
                    <div class="fv-row mb-6 d-none" id="wrapperEquipoPadre">
                        <label class="required fs-6 fw-bold mb-2">Equipo al que pertenece</label>
                        <select name="equipo_padre_id" id="equipo_padre_id" class="form-select form-select-solid">
                            <option value="">Primero seleccione el área...</option>
                        </select>
                        <div class="invalid-feedback" id="error_equipo_padre_id"></div>
                    </div>

                    <!-- Nombre de la Dependencia -->
                    <div class="fv-row mb-6">
                        <label class="required fs-6 fw-bold mb-2">Nombre de la Dependencia Orgánica</label>
                        <input type="text" name="domi_nombre" id="domi_nombre" class="form-control form-control-solid text-uppercase"
                            placeholder="Ej. RECURSOS HUMANOS, INFORMÁTICA, CONTABILIDAD..." required maxlength="255" />
                        <div class="invalid-feedback" id="error_domi_nombre"></div>
                    </div>

                    <div class="row g-6 mb-6">
                        <!-- Siglas -->
                        <div class="col-md-5 fv-row">
                            <label class="fs-6 fw-bold mb-2">Siglas / Abreviatura</label>
                            <input type="text" name="domi_siglas" id="domi_siglas" class="form-control form-control-solid text-uppercase"
                                placeholder="Ej. RRHH, INFO, DIR..." maxlength="50" />
                            <div class="invalid-feedback" id="error_domi_siglas"></div>
                        </div>

                        <!-- Responsable / Titular -->
                        <div class="col-md-7 fv-row">
                            <label class="fs-6 fw-bold mb-2">Responsable / Titular de la Oficina</label>
                            <input type="text" name="domi_responsable" id="domi_responsable" class="form-control form-control-solid"
                                placeholder="Nombre del director, jefe o encargado..." maxlength="255" />
                            <div class="invalid-feedback" id="error_domi_responsable"></div>
                        </div>
                    </div>

                    <!-- Descripción o Funciones -->
                    <div class="fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Descripción / Funciones de la Unidad</label>
                        <textarea name="domi_descripcion" id="domi_descripcion" class="form-control form-control-solid" rows="3"
                            placeholder="Breve detalle de las funciones o competencias institucionales..."></textarea>
                        <div class="invalid-feedback" id="error_domi_descripcion"></div>
                    </div>

                    <!-- Mensaje informativo sobre sincronización Enfoque A -->
                    <div class="alert alert-light-primary d-flex align-items-center p-4 rounded-3 mb-0">
                        <i class="ki-outline ki-information-4 fs-2x text-primary me-3"></i>
                        <div class="d-flex flex-column fs-7 text-gray-700">
                            <strong>Sincronización Relacional Automática</strong>
                            <span>Al guardar, el sistema sincronizará automáticamente la tabla física respectiva (<code class="text-primary">area</code>, <code class="text-primary">equipo</code> o <code class="text-primary">subequipo</code>) y mantendrá el ID universal en <code class="text-primary">dominio</code>.</span>
                        </div>
                    </div>

                </div>

                <div class="modal-footer flex-center border-0 pt-0 pb-8">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarDominio">
                        <span class="indicator-label">
                            <i class="ki-outline ki-check fs-2 me-1"></i> Guardar Dominio
                        </span>
                        <span class="indicator-progress">
                            Guardando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Configurar Token CSRF global
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let filtroTipoActual = 'ALL';

    // ─── 1. INICIALIZAR DATATABLE SERVER-SIDE ───
    const tabla = $('#table_dominios').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        pageLength: 10,
        ajax: {
            url: "{{ route('dominio.api') }}",
            type: "GET",
            data: function(d) {
                d.tipo_filtro = filtroTipoActual;
            }
        },
        columns: [
            {
                data: 'id',
                className: 'text-center fw-bold text-gray-800',
                render: function(data) {
                    return `<span class="badge badge-light fw-bold">#${data}</span>`;
                }
            },
            {
                data: 'domi_tipo',
                render: function(data) {
                    switch (data) {
                        case 'AREA':
                            return `<span class="dom-badge dom-badge--area"><i class="ki-outline ki-abstract-26 fs-8 me-1"></i> ÁREA</span>`;
                        case 'EQUIPO':
                            return `<span class="dom-badge dom-badge--equipo"><i class="ki-outline ki-people fs-8 me-1"></i> EQUIPO</span>`;
                        case 'SUBEQUIPO':
                            return `<span class="dom-badge dom-badge--subequipo"><i class="ki-outline ki-element-11 fs-8 me-1"></i> SUBEQUIPO</span>`;
                        case 'EXTERNO':
                            return `<span class="dom-badge dom-badge--externo"><i class="ki-outline ki-user fs-8 me-1"></i> EXTERNO</span>`;
                        case 'CONVOCATORIA':
                            return `<span class="dom-badge dom-badge--cas"><i class="ki-outline ki-folder fs-8 me-1"></i> CAS</span>`;
                        default:
                            return `<span class="badge badge-light-secondary">${data}</span>`;
                    }
                }
            },
            {
                data: null,
                render: function(row) {
                    const siglasHtml = row.domi_siglas
                        ? `<span class="dom-badge-siglas ms-2">${row.domi_siglas}</span>`
                        : '';
                    const descHtml = row.domi_descripcion
                        ? `<div class="text-muted fs-8 text-truncate mw-300px mt-1">${row.domi_descripcion}</div>`
                        : '';
                    return `
                        <div>
                            <span class="text-gray-900 fw-bold fs-6">${row.domi_nombre}</span>
                            ${siglasHtml}
                            ${descHtml}
                        </div>
                    `;
                }
            },
            {
                data: null,
                render: function(row) {
                    if (row.domi_tipo === 'AREA') {
                        return `<span class="text-primary fw-semibold fs-7"><i class="ki-outline ki-shield-tick me-1 text-primary"></i> Despacho Matriz / Nivel 1</span>`;
                    }
                    if (row.domi_tipo === 'EQUIPO') {
                        return `
                            <div class="dom-parent-path">
                                <span class="text-muted fs-8">ÁREA MATRIZ:</span><br>
                                <strong>${row.domi_idarea_str || 'Sin asignar'}</strong>
                            </div>
                        `;
                    }
                    if (row.domi_tipo === 'SUBEQUIPO') {
                        return `
                            <div class="dom-parent-path">
                                <span class="text-muted fs-8">EQUIPO:</span> <strong>${row.domi_idequipo_str || 'N/A'}</strong><br>
                                <span class="text-muted fs-8">ÁREA:</span> <span class="text-gray-600">${row.domi_idarea_str || 'N/A'}</span>
                            </div>
                        `;
                    }
                    if (row.domi_tipo === 'CONVOCATORIA') {
                        return `<span class="text-muted fs-8 fst-italic">Proceso de Selección CAS Histórico</span>`;
                    }
                    return `<span class="text-muted fs-8">Portal Ciudadano Externo</span>`;
                }
            },
            {
                data: 'domi_responsable',
                render: function(data) {
                    if (data && data.trim() !== '') {
                        return `
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-user fs-4 text-gray-500 me-2"></i>
                                <span class="text-gray-800 fw-semibold fs-7">${data}</span>
                            </div>
                        `;
                    }
                    return `<span class="badge badge-light-secondary fs-8">Sin asignar</span>`;
                }
            },
            {
                data: 'usuarios_count',
                className: 'text-center',
                render: function(data) {
                    const count = parseInt(data) || 0;
                    if (count > 0) {
                        return `<span class="badge badge-light-primary fw-bold fs-7 px-3 py-1">${count} usuarios</span>`;
                    }
                    return `<span class="text-muted fs-8">0</span>`;
                }
            },
            {
                data: null,
                className: 'text-end',
                orderable: false,
                render: function(row) {
                    const esProtegido = (row.id == 6 || row.id == 128);
                    const btnEliminar = esProtegido
                        ? `<button type="button" class="btn btn-icon btn-light-secondary btn-sm" disabled title="Dominio protegido por el sistema">
                               <i class="ki-outline ki-lock fs-4"></i>
                           </button>`
                        : `<button type="button" class="btn btn-icon btn-light-danger btn-sm btn-eliminar-dom" data-id="${row.id}" data-nombre="${row.domi_nombre}" title="Eliminar Dominio">
                               <i class="ki-outline ki-trash fs-4"></i>
                           </button>`;

                    return `
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-editar-dom" data-id="${row.id}" title="Editar Dominio">
                                <i class="ki-outline ki-pencil fs-4"></i>
                            </button>
                            ${btnEliminar}
                        </div>
                    `;
                }
            }
        ],
        language: {
            emptyTable: "No se encontraron dominios institucionales",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            lengthMenu: "Mostrar _MENU_",
            loadingRecords: "Cargando dominios...",
            processing: "Procesando catálogo...",
            search: "Buscar:",
            zeroRecords: "No se encontraron resultados coincidentes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    // ─── 2. BÚSQUEDA Y PESTAÑAS ───
    let timeoutBusqueda;
    $('#buscarDominio').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            tabla.search($('#buscarDominio').val()).draw();
        }, 300);
    });

    $('#btnRecargarTabla').on('click', function() {
        tabla.ajax.reload(null, false);
    });

    $('.dom-tab-btn').on('click', function() {
        $('.dom-tab-btn').removeClass('active');
        $(this).addClass('active');
        filtroTipoActual = $(this).data('filtro');
        tabla.ajax.reload();
    });

    // ─── 3. CASCADA DINÁMICA EN MODAL ───
    function actualizarCamposSegunTipo(tipo, areaPadreId, equipoPadreId) {
        limpiarErrores();
        const $wrapArea = $('#wrapperAreaPadre');
        const $wrapEquipo = $('#wrapperEquipoPadre');

        if (tipo === 'AREA') {
            $wrapArea.addClass('d-none');
            $wrapEquipo.addClass('d-none');
            $('#area_padre_id').val('').removeAttr('required');
            $('#equipo_padre_id').val('').removeAttr('required');
        } else if (tipo === 'EQUIPO') {
            $wrapArea.removeClass('d-none');
            $wrapEquipo.addClass('d-none');
            $('#area_padre_id').attr('required', 'required');
            $('#equipo_padre_id').val('').removeAttr('required');
            if (areaPadreId) {
                $('#area_padre_id').val(areaPadreId);
            }
        } else if (tipo === 'SUBEQUIPO') {
            $wrapArea.removeClass('d-none');
            $wrapEquipo.removeClass('d-none');
            $('#area_padre_id').attr('required', 'required');
            $('#equipo_padre_id').attr('required', 'required');

            if (areaPadreId) {
                $('#area_padre_id').val(areaPadreId);
                cargarEquiposDeArea(areaPadreId, equipoPadreId);
            }
        } else {
            $wrapArea.addClass('d-none');
            $wrapEquipo.addClass('d-none');
        }
    }

    $('#domi_tipo').on('change', function() {
        actualizarCamposSegunTipo($(this).val());
    });

    $('#area_padre_id').on('change', function() {
        const areaId = $(this).val();
        const tipo = $('#domi_tipo').val();
        if (tipo === 'SUBEQUIPO') {
            cargarEquiposDeArea(areaId, null);
        }
    });

    function cargarEquiposDeArea(areaId, equipoSeleccionadoId) {
        const $equipoSelect = $('#equipo_padre_id');
        $equipoSelect.html('<option value="">Cargando equipos...</option>');

        if (!areaId) {
            $equipoSelect.html('<option value="">Primero seleccione el área...</option>');
            return;
        }

        $.ajax({
            url: "{{ route('dominio.opciones-padre') }}",
            type: "GET",
            data: {
                nivel: 'EQUIPOS_DE_AREA',
                area_id: areaId
            },
            success: function(equipos) {
                let html = '<option value="">Seleccione el equipo matriz...</option>';
                equipos.forEach(function(eq) {
                    const sel = (equipoSeleccionadoId && eq.id == equipoSeleccionadoId) ? 'selected' : '';
                    html += `<option value="${eq.id}" ${sel}>${eq.domi_nombre} ${eq.domi_siglas ? '(' + eq.domi_siglas + ')' : ''}</option>`;
                });
                $equipoSelect.html(html);
            },
            error: function() {
                $equipoSelect.html('<option value="">Error al cargar equipos</option>');
            }
        });
    }

    // ─── HELPER MODAL BOOTSTRAP 5 ───
    function abrirModal() {
        const modalEl = document.getElementById('modalDominio');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $ !== 'undefined') {
            $('#modalDominio').modal('show');
        }
    }

    function cerrarModal() {
        const modalEl = document.getElementById('modalDominio');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        } else if (typeof $ !== 'undefined') {
            $('#modalDominio').modal('hide');
        }
    }

    // ─── 4. ABRIR MODAL CREAR NUEVO DOMINIO ───
    $('#btnNuevoDominio').on('click', function() {
        limpiarErrores();
        $('#formDominio')[0].reset();
        $('#dominio_id').val('');
        $('#metodo_form').val('POST');
        $('#domi_tipo').prop('disabled', false);
        $('#modalDominioTitulo').text('Registrar Nuevo Dominio');
        $('#wrapperAreaPadre').addClass('d-none');
        $('#wrapperEquipoPadre').addClass('d-none');
        abrirModal();
    });

    // ─── 5. ABRIR MODAL EDITAR DOMINIO ───
    $(document).on('click', '.btn-editar-dom', function() {
        const id = $(this).data('id');
        limpiarErrores();

        Swal.fire({
            title: 'Cargando datos...',
            text: 'Por favor espere un momento',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ url('dominio') }}/" + id,
            type: "GET",
            success: function(res) {
                Swal.close();
                const d = res.dominio;

                $('#dominio_id').val(d.id);
                $('#metodo_form').val('PUT');
                $('#modalDominioTitulo').text(`Editar Dominio: #${d.id} - ${d.domi_nombre}`);

                $('#domi_tipo').val(d.domi_tipo);
                // Si es ID 6 o 128, deshabilitar cambio de tipo
                if (d.id == 6 || d.id == 128) {
                    $('#domi_tipo').prop('disabled', true);
                } else {
                    $('#domi_tipo').prop('disabled', false);
                }

                $('#domi_nombre').val(d.domi_nombre);
                $('#domi_siglas').val(d.domi_siglas);
                $('#domi_responsable').val(d.domi_responsable);
                $('#domi_descripcion').val(d.domi_descripcion);

                actualizarCamposSegunTipo(d.domi_tipo, res.area_padre_id, res.equipo_padre_id);

                abrirModal();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo obtener la información del dominio.'
                });
            }
        });
    });

    // ─── 6. GUARDAR (CREAR O EDITAR) ───
    $('#formDominio').on('submit', function(e) {
        e.preventDefault();
        limpiarErrores();

        const id = $('#dominio_id').val();
        const esEditar = (id !== '');
        const metodo = esEditar ? 'PUT' : 'POST';
        const url = esEditar ? ("{{ url('dominio') }}/" + id) : "{{ route('dominio.store') }}";

        const $btn = $('#btnGuardarDominio');
        $btn.attr('data-kt-indicator', 'on').prop('disabled', true);

        // Habilitar temporalmente domi_tipo para serialize si estaba disabled
        $('#domi_tipo').prop('disabled', false);
        const data = $(this).serialize();
        if (esEditar && (id == 6 || id == 128)) {
            $('#domi_tipo').prop('disabled', true);
        }

        $.ajax({
            url: url,
            type: metodo,
            data: data,
            success: function(res) {
                $btn.removeAttr('data-kt-indicator').prop('disabled', false);

                if (res.success) {
                    cerrarModal();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Operación Exitosa!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                $btn.removeAttr('data-kt-indicator').prop('disabled', false);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const campo in errors) {
                        $(`#${campo}`).addClass('is-invalid');
                        $(`#error_${campo}`).text(errors[campo][0]).show();
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos requeridos',
                        text: xhr.responseJSON.message || 'Por favor revise los campos señalados.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de servidor',
                        text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Ocurrió un error inesperado.'
                    });
                }
            }
        });
    });

    // ─── 7. ELIMINAR DOMINIO ───
    $(document).on('click', '.btn-eliminar-dom', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');

        Swal.fire({
            title: '¿Está seguro de eliminar este dominio?',
            html: `Se eliminará la dependencia <strong>${nombre}</strong> (ID: #${id}) y se desvinculará de su tabla física.<br><br><span class="text-danger fs-7">Esta acción no se puede deshacer.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ki-outline ki-trash me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Validando reglas y dependencias orgánicas',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ url('dominio') }}/" + id,
                    type: "DELETE",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tabla.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No se puede eliminar',
                                text: res.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se puede eliminar',
                            text: (xhr.responseJSON && xhr.responseJSON.message)
                                ? xhr.responseJSON.message
                                : 'Error de servidor al intentar eliminar.'
                        });
                    }
                });
            }
        });
    });

    // ─── 8. LIMPIEZA DE ERRORES ───
    function limpiarErrores() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
    }
});
</script>
@endsection
