@extends('layouts.template')

@section('titlePage', 'Dominio de Usuario | UGEL Alto Amazonas')

@section('content')
<div class="du-container">

    <!-- Header Principal -->
    <div class="du-page-header">
        <div class="du-page-header__left">
            <div class="du-page-badge">
                <i class="ki-outline ki-shield-tick fs-6 me-1"></i> Seguridad & Organización
            </div>
            <h1 class="du-page-title">Dominio de Usuarios</h1>
            <p class="du-page-subtitle">
                Asignación y vinculación institucional entre el personal y su dependencia orgánica (Área, Equipo o Subequipo).
            </p>
        </div>
        <div class="du-page-header__right">
            <button type="button" class="btn btn-primary du-btn-create" data-bs-toggle="modal" data-bs-target="#modalAsignarDominio">
                <i class="ki-outline ki-plus fs-2 me-1"></i> Asignar Dominio
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="du-stats-grid">
        <div class="du-stat-card du-stat-card--total">
            <div class="du-stat-card__icon">
                <i class="ki-outline ki-profile-user fs-2x"></i>
            </div>
            <div class="du-stat-card__info">
                <span class="du-stat-card__value">{{ $totalUsuarios }}</span>
                <span class="du-stat-card__label">Usuarios Activos</span>
            </div>
        </div>

        <div class="du-stat-card du-stat-card--assigned">
            <div class="du-stat-card__icon">
                <i class="ki-outline ki-verify fs-2x"></i>
            </div>
            <div class="du-stat-card__info">
                <span class="du-stat-card__value">{{ $conDominio }}</span>
                <span class="du-stat-card__label">Con Dominio Asignado</span>
            </div>
        </div>

        <div class="du-stat-card du-stat-card--pending">
            <div class="du-stat-card__icon">
                <i class="ki-outline ki-information fs-2x"></i>
            </div>
            <div class="du-stat-card__info">
                <span class="du-stat-card__value">{{ $sinDominio }}</span>
                <span class="du-stat-card__label">Sin Dominio (Pendientes)</span>
            </div>
        </div>

        <div class="du-stat-card du-stat-card--domains">
            <div class="du-stat-card__icon">
                <i class="ki-outline ki-bank fs-2x"></i>
            </div>
            <div class="du-stat-card__info">
                <span class="du-stat-card__value">{{ $totalDominios }}</span>
                <span class="du-stat-card__label">Dominios Registrados</span>
            </div>
        </div>
    </div>

    <!-- Tabla Card Principal -->
    <div class="card du-card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5 text-gray-500"></i>
                    <input type="text" id="buscarDominioUsuario" class="form-control form-control-solid w-250px w-md-350px ps-13"
                        placeholder="Buscar por nombre, DNI, dominio..." />
                </div>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-light-primary btn-sm" id="btnRecargarTabla">
                    <i class="ki-outline ki-arrows-circle fs-3 me-1"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="table_dominioUsuario">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-50px text-center">#</th>
                            <th class="min-w-200px">Funcionario / Usuario</th>
                            <th class="min-w-120px">Perfil / Rol</th>
                            <th class="min-w-250px">Dominio Institucional</th>
                            <th class="min-w-150px">Cargo / Función</th>
                            <th class="min-w-100px text-center">Estado</th>
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

<!-- Modal Asignar / Editar Dominio -->
<div class="modal fade" id="modalAsignarDominio" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content du-modal-content">
            <form id="formAsignarDominio">
                @csrf
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <div>
                        <h2 class="fw-bold text-gray-900 mb-1" id="modalTituloDominio">Asignar Dominio Institucional</h2>
                        <p class="text-muted fs-7 mb-0">Seleccione el usuario y asigne su dependencia orgánica oficial.</p>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-6 px-lg-10">

                    <!-- Selector de Usuario -->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Usuario / Funcionario</label>
                        <select name="usuario_id" id="du_usuario_id" class="form-select du-select-usuario" data-control="select2" data-placeholder="Seleccionar usuario" data-dropdown-parent="#modalAsignarDominio">
                            <option value="">Seleccione un usuario</option>
                            @foreach ($usuarios as $u)
                                <option value="{{ $u->id }}" data-dni="{{ $u->dni }}" data-dominio="{{ $u->dominio_id }}" data-cargo="{{ $u->cargo }}">
                                    {{ $u->nombre_completo }} (DNI: {{ $u->dni ?? 'S/D' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_usuario_id"></div>
                    </div>

                    <!-- Selector de Dominio Inteligente -->
                    <div class="fv-row mb-7">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="required fs-6 fw-semibold mb-0">Dominio Institucional (Área / Equipo / Subequipo)</label>
                            <span class="badge badge-light-primary fs-8">Selección única jerárquica</span>
                        </div>
                        <select name="dominio_id" id="du_dominio_id" class="form-select du-select-dominio" data-placeholder="Escriba para buscar área, equipo o subequipo..." data-dropdown-parent="#modalAsignarDominio">
                            <option value=""></option>
                        </select>
                        <div class="form-text text-muted fs-8 mt-1">
                            Puede escribir el nombre del área, equipo o subequipo. La jerarquía se vincula automáticamente en un solo paso.
                        </div>
                        <div class="invalid-feedback" id="error_dominio_id"></div>
                    </div>

                    <!-- Cargo / Función Opcional -->
                    <div class="fv-row mb-5">
                        <label class="fs-6 fw-semibold mb-2">Cargo / Función (Opcional)</label>
                        <input type="text" name="cargo" id="du_cargo" class="form-control"
                            placeholder="Ej: DIRECTOR(A), ESPECIALISTA EN PLANIFICACIÓN, TÉCNICO DE MESA DE PARTES..." />
                        <div class="form-text text-muted fs-8 mt-1">
                            Especifique la denominación o cargo que desempeña en dicha dependencia.
                        </div>
                        <div class="invalid-feedback" id="error_cargo"></div>
                    </div>

                    <!-- Mensaje informativo -->
                    <div class="du-info-alert">
                        <i class="ki-outline ki-shield-search fs-2 text-primary me-3"></i>
                        <div class="fs-7 text-gray-700">
                            <strong>Impacto en el flujo documental:</strong> Al asignar este dominio, los trámites derivados o enviados a esta dependencia serán recibidos en la bandeja de este usuario.
                        </div>
                    </div>

                </div>

                <div class="modal-footer flex-center border-0 pt-0 pb-8">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarDominio">
                        <i class="ki-outline ki-disk fs-2 me-1"></i>
                        <span class="indicator-label">Guardar Asignación</span>
                        <span class="indicator-progress d-none">
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
document.addEventListener("DOMContentLoaded", function () {
    const tableId = "#table_dominioUsuario";
    if (!document.querySelector(tableId)) return;

    // Inicializar Select2 en modal
    $("#du_usuario_id").select2({
        dropdownParent: $("#modalAsignarDominio"),
        width: "100%",
        placeholder: "Seleccione un usuario",
        allowClear: true
    });

    // Select2 AJAX para Dominio Inteligente
    $("#du_dominio_id").select2({
        dropdownParent: $("#modalAsignarDominio"),
        width: "100%",
        placeholder: "Buscar dependencia (Área, Equipo, Subequipo)...",
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('dominio-usuario.filtro') }}",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                            tipo: item.tipo,
                            nombre: item.nombre
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Auto-llenar campos al cambiar de usuario
    $("#du_usuario_id").on("change", function () {
        const selected = $(this).find(":selected");
        const cargo = selected.data("cargo");
        const dominioId = selected.data("dominio");

        if (cargo) {
            $("#du_cargo").val(cargo);
        } else {
            $("#du_cargo").val("");
        }

        if (dominioId) {
            // Cargar datos actuales si existen
            $.ajax({
                url: "{{ route('dominio-usuario.filtro') }}",
                data: { term: "" },
                success: function(items) {
                    const match = items.find(i => i.id == dominioId);
                    if (match) {
                        const option = new Option(match.text, match.id, true, true);
                        $("#du_dominio_id").append(option).trigger("change");
                    }
                }
            });
        } else {
            $("#du_dominio_id").val(null).trigger("change");
        }
    });

    // Inicializar DataTable
    const dt = $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('dominio-usuario.api') }}",
            type: "GET"
        },
        columns: [
            {
                data: null,
                className: "text-center fw-semibold text-muted",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: null,
                render: function (data) {
                    const iniciales = (data.nombres ? data.nombres[0] : "") + (data.apellidos ? data.apellidos[0] : "");
                    const email = data.correo ? data.correo : "Sin correo";
                    const dni = data.dni ? `DNI: ${data.dni}` : "Sin DNI";

                    return `
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle me-3">
                                <span class="symbol-label bg-light-primary text-primary fw-bold fs-7">${iniciales}</span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-900 fw-bold fs-7 text-hover-primary">${data.nombre_completo}</span>
                                <span class="text-muted fs-8">${dni} &bull; ${email}</span>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: "tipo_usuario",
                render: function (data) {
                    return `<span class="badge badge-light fw-bold fs-8">${data ?? 'USUARIO'}</span>`;
                }
            },
            {
                data: null,
                render: function (data) {
                    if (!data.dominio_nombre) {
                        return `<span class="badge badge-light-danger fw-bold fs-8"><i class="ki-outline ki-cross-circle fs-8 me-1 text-danger"></i> Sin Dominio</span>`;
                    }

                    let badgeColor = "badge-light-primary";
                    let icon = "ki-bank";
                    if (data.domi_tipo === "EQUIPO") {
                        badgeColor = "badge-light-success";
                        icon = "ki-people";
                    } else if (data.domi_tipo === "SUBEQUIPO") {
                        badgeColor = "badge-light-warning";
                        icon = "ki-folder";
                    }

                    let jerarquia = "";
                    if (data.domi_idarea_str && data.domi_idarea_str !== "- : -") {
                        jerarquia = `<span class="text-muted fs-9 d-block mt-1"><i class="ki-outline ki-right fs-9 text-muted me-1"></i>Área: ${data.domi_idarea_str}</span>`;
                    }

                    return `
                        <div>
                            <span class="badge ${badgeColor} fw-bold fs-8 mb-1">
                                <i class="ki-outline ${icon} fs-8 me-1"></i>${data.domi_tipo ?? 'DOMINIO'}
                            </span>
                            <div class="fw-bold text-gray-800 fs-7">${data.dominio_nombre}</div>
                            ${jerarquia}
                        </div>
                    `;
                }
            },
            {
                data: "cargo",
                render: function (data) {
                    if (!data) return `<span class="text-muted fs-8">-</span>`;
                    return `<span class="badge badge-light-info fw-bold fs-8">${data}</span>`;
                }
            },
            {
                data: "estado",
                className: "text-center",
                render: function (data) {
                    if (data == 1) {
                        return `<span class="badge badge-light-success fs-8 fw-bold">Activo</span>`;
                    }
                    return `<span class="badge badge-light-danger fs-8 fw-bold">Inactivo</span>`;
                }
            },
            {
                data: null,
                className: "text-end",
                render: function (data) {
                    const tieneDominio = !!data.dominio_id;
                    const btnDesvincular = tieneDominio ? `
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm btn-desvincular" data-id="${data.usuario_id}" data-nombre="${data.nombre_completo}" title="Desvincular Dominio">
                            <i class="ki-outline ki-trash fs-3"></i>
                        </button>
                    ` : "";

                    return `
                        <div class="d-inline-flex gap-1">
                            <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm btn-editar"
                                data-id="${data.usuario_id}"
                                data-dominio="${data.dominio_id ?? ''}"
                                data-dominionombre="${data.dominio_nombre ?? ''}"
                                data-dominiotipo="${data.domi_tipo ?? ''}"
                                data-cargo="${data.cargo ?? ''}"
                                title="Asignar / Cambiar Dominio">
                                <i class="ki-outline ki-pencil fs-3"></i>
                            </button>
                            ${btnDesvincular}
                        </div>
                    `;
                }
            }
        ],
        language: {
            zeroRecords: "No se encontraron usuarios",
            info: "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
            infoEmpty: "Mostrando 0 a 0 de 0 usuarios",
            infoFiltered: "(filtrado de _MAX_ usuarios totales)",
            processing: "Cargando datos...",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    // Buscador interactivo
    $("#buscarDominioUsuario").on("keyup", function () {
        dt.search(this.value).draw();
    });

    // Botón Recargar
    $("#btnRecargarTabla").on("click", function () {
        dt.ajax.reload(null, false);
    });

    // Click en Editar / Asignar desde fila
    $(document).on("click", ".btn-editar", function () {
        const id = $(this).data("id");
        const dominioId = $(this).data("dominio");
        const dominioNombre = $(this).data("dominionombre");
        const dominioTipo = $(this).data("dominiotipo");
        const cargo = $(this).data("cargo");

        $("#modalTituloDominio").text("Editar Asignación de Dominio");
        $("#du_usuario_id").val(id).trigger("change");
        $("#du_cargo").val(cargo);

        if (dominioId && dominioNombre) {
            const label = `[${dominioTipo}] ${dominioNombre}`;
            const option = new Option(label, dominioId, true, true);
            $("#du_dominio_id").empty().append(option).trigger("change");
        } else {
            $("#du_dominio_id").val(null).trigger("change");
        }

        $("#modalAsignarDominio").modal("show");
    });

    // Resetear modal al abrir con botón crear
    $(".du-btn-create").on("click", function () {
        $("#modalTituloDominio").text("Asignar Dominio Institucional");
        $("#formAsignarDominio")[0].reset();
        $("#du_usuario_id").val(null).trigger("change");
        $("#du_dominio_id").val(null).trigger("change");
        limpiarErroresModal();
    });

    function limpiarErroresModal() {
        $(".invalid-feedback").text("").hide();
        $(".form-control, .form-select").removeClass("is-invalid");
    }

    // Submit del Formulario
    $("#formAsignarDominio").on("submit", function (e) {
        e.preventDefault();
        limpiarErroresModal();

        const btn = $("#btnGuardarDominio");
        btn.find(".indicator-label").addClass("d-none");
        btn.find(".indicator-progress").removeClass("d-none");
        btn.prop("disabled", true);

        const formData = {
            usuario_id: $("#du_usuario_id").val(),
            dominio_id: $("#du_dominio_id").val(),
            cargo: $("#du_cargo").val(),
            _token: $('meta[name="csrf-token"]').attr("content")
        };

        axios.post("{{ route('dominio-usuario.asignar') }}", formData)
            .then(function (res) {
                $("#modalAsignarDominio").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: res.data.message,
                    buttonsStyling: false,
                    confirmButtonText: "Aceptar",
                    customClass: { confirmButton: "btn btn-primary" }
                });
                dt.ajax.reload(null, false);
            })
            .catch(function (err) {
                if (err.response && err.response.status === 422) {
                    const errors = err.response.data.errors;
                    Object.keys(errors).forEach(function (field) {
                        const feedback = $(`#error_${field}`);
                        if (feedback.length) {
                            feedback.text(errors[field][0]).show();
                            $(`[name="${field}"]`).addClass("is-invalid");
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: err.response?.data?.message || "Ocurrió un error al procesar la solicitud.",
                        buttonsStyling: false,
                        confirmButtonText: "Entendido",
                        customClass: { confirmButton: "btn btn-primary" }
                    });
                }
            })
            .finally(function () {
                btn.find(".indicator-label").removeClass("d-none");
                btn.find(".indicator-progress").addClass("d-none");
                btn.prop("disabled", false);
            });
    });

    // Desvincular Dominio
    $(document).on("click", ".btn-desvincular", function () {
        const id = $(this).data("id");
        const nombre = $(this).data("nombre");

        Swal.fire({
            title: "¿Desvincular Dominio?",
            text: `Se retirará el dominio institucional del usuario: ${nombre}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, desvincular",
            cancelButtonText: "Cancelar",
            buttonsStyling: false,
            customClass: {
                confirmButton: "btn btn-danger me-3",
                cancelButton: "btn btn-light"
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                axios.delete(`/dominio-usuario/desvincular/${id}`)
                    .then(function (res) {
                        Swal.fire({
                            icon: "success",
                            title: "Desvinculado",
                            text: res.data.message,
                            buttonsStyling: false,
                            confirmButtonText: "Aceptar",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                        dt.ajax.reload(null, false);
                    })
                    .catch(function (err) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: err.response?.data?.message || "No se pudo desvincular.",
                            buttonsStyling: false,
                            confirmButtonText: "Entendido",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    });
            }
        });
    });
});
</script>
@endsection
