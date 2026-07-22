<div class="modal fade" id="recdartar_tramiteExterno" tabindex-="1" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_add_event_form">
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
                                    <input class="form-check-input h-20px w-20px tipo_tramiteRadio" type="radio" name="tipo_tramite"
                                        value="SIN_TUPA" checked />
                                    <span class="form-check-label fw-semibold" style="color: #000">
                                        SIN TUPA
                                    </span>
                                </label>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-20px tipo_tramiteRadio" type="radio" name="tipo_tramite"
                                        value="TUPA" />
                                    <span class="form-check-label fw-semibold" style="color: #000">
                                        TUPA (Texto Unico de Procedimientos Administrativos)
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-lg-2 g-10">
                        <div class="col">
                            <label class="fs-6 fw-semibold form-label">Solicitante</label>
                            <div class="input-group">
                                <input type="text" class="form-control" />
                                <button type="button" class="btn btn-icon btn-primary">
                                    <i class="ki-duotone ki-plus-square fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </button>
                            </div>
                        </div>
                        <div class="col" data-kt-calendar="datepicker">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">Procedimiento</label>
                                <select id="kt_ecommerce_select2_country" class="form-select " name="country"
                                    data-kt-ecommerce-settings-type="select2_flags" data-placeholder="Select a country">
                                    <option value="=">Seleccionar</option>
                                    <option value="AF" data-kt-select2-country="assets/media/flags/afghanistan.svg">
                                        Afghanistan</option>
                                    <option value="AX"
                                        data-kt-select2-country="assets/media/flags/aland-islands.svg">Aland Islands
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-lg-3 g-10">
                        <div class="col">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">Documento</label>
                                <select id="kt_ecommerce_select2_country" class="form-select " name="country"
                                    data-kt-ecommerce-settings-type="select2_flags" data-placeholder="Select a country">
                                    <option value="=">Seleccionar</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col" data-kt-calendar="datepicker">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">N° Documento</label>
                                <input class="form-control" name="calendar_event_start_time"
                                    id="kt_calendar_datepicker_start_time" />
                            </div>
                        </div>
                        <div class="col" data-kt-calendar="datepicker">
                            <div class="fv-row mb-9">
                                <label class="fs-6 fw-semibold mb-2">N° Folio</label>
                                <input class="form-control" name="calendar_event_start_time"
                                    id="kt_calendar_datepicker_start_time" />
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mb-9 destino_input">
                        <label class="fs-6 fw-semibold mb-2">Destino - Seleccione el nombre del area (A), equipo (E),
                            subequipo (SE)</label>
                        <input type="text" class="form-control" placeholder="Buscar"
                            name="calendar_event_description" />
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Asunto</label>
                        <input type="text" class="form-control" placeholder="" name="calendar_event_location" />
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Descripcion</label>
                        <input type="text" class="form-control" placeholder="" name="calendar_event_location" />
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-semibold mb-2">Adjuntar archivos</label>
                        <input type="file" class="form-control" placeholder="" name="calendar_event_location" />
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-danger me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-guardar" class="btn btn-primary">
                        <span class="indicator-label">Guardar</span>
                    </button>
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
