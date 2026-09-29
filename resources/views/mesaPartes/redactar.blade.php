<div class="modal fade" id="recdartar_tramiteExterno" tabindex-="1" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form class="form_tramiteExterno" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold" data-kt-calendar="title">Redactar Expediente</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" id="kt_modal_add_event_close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-9">
                        <div class="d-flex flex-stack">
                            <div class="d-flex align-items-center">
                                <label class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-20px tipo_tramiteRadio" type="radio"
                                        name="tipo_tramite" value="SIN_TUPA" checked />
                                    <span class="form-check-label fw-semibold" style="color: #000">
                                        SIN TUPA
                                    </span>
                                </label>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-20px tipo_tramiteRadio" type="radio"
                                        name="tipo_tramite" value="TUPA" />
                                    <span class="form-check-label fw-semibold" style="color: #000">
                                        TUPA (Texto Unico de Procedimientos Administrativos)
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-lg-2 g-10">

                        <div class="col">
                            <div class="fv-row mb-9">

                                <label class="fs-6 fw-semibold mb-2">
                                    Solicitante
                                </label>

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">
                                        <select id="solicitante_id" class="form-select solicitanteCreate validate_modal"
                                            name="solicitante_id" data-placeholder="Buscar solicitante"
                                            data-allow-clear="true">
                                            <option value=""></option>
                                        </select>
                                    </div>

                                    <button type="button"
                                        class="btn btn-icon btn-primary flex-shrink-0 btnNuevoSolicitante"
                                        title="Nuevo solicitante">
                                        <i class="ki-duotone ki-plus-square fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </button>

                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col procedimiento_input hidden">
                            <div class="fv-row mb-9">

                                <label class="fs-6 fw-semibold mb-2">
                                    Procedimiento
                                </label>

                                <select id="procedimiento_id" class="form-select procedimientoSelect"
                                    name="procedimiento_id" data-control="select2"
                                    data-placeholder="Seleccionar procedimiento" data-allow-clear="true">
                                    <option value=""></option>

                                    @foreach ($procedimientos as $procedimiento)
                                        <option value="{{ $procedimiento->id }}">
                                            {{ $procedimiento->prod_descripcion }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>
                        </div>

                    </div>
                    <div class="row row-cols-lg-3 g-10">
                        <div class="col">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">Tipo Documento</label>
                                <select id="tipoDocumento_id" class="form-select validate_modal" name="tipoDocumento_id"
                                    data-control="select2" data-placeholder="Seleccionar">
                                    <option value="">Seleccionar</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col" data-kt-calendar="datepicker">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">N° Documento</label>
                                <input class="form-control validate_modal" name="numero_documento"
                                    id="numero_documento" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col" data-kt-calendar="datepicker">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">N° Folio</label>
                                <input class="form-control validate_modal" name="numero_folio" id="numero_folio" />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">
                            Destino - Seleccione el nombre del area (A), equipo (E), subequipo (SE)
                        </label>
                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1">
                                <select id="destino_id" class="form-select destinoCreate validate_modal"
                                    name="destino_id" data-placeholder="Seleccionar" data-allow-clear="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Asunto</label>
                        <input type="text" class="form-control validate_modal" placeholder="" name="asunto" />
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Descripcion</label>
                        <input type="text" class="form-control validate_modal" placeholder=""
                            name="descripcion" />
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Adjuntar archivos</label>
                        <input type="file" class="form-control" placeholder="" name="calendar_event_location" />
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-danger me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-disk  fs-2"></i>
                        <span class="indicator-label">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
