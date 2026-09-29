@extends('layouts.template')

@section('titlePage', 'Mesa partes | Ugel Alto Amazonas')

@section('content')





    <!-- Content -->
    <div class="te-content">

        <!-- Page Header con título y botón Redactar -->
        <div class="te-page-header-bar te-animate-in">
            <div class="te-page-header-bar__left">
                <h1 class="te-page-title">Bandeja Documentaria</h1>
                <p class="te-page-subtitle">Gestión, recepción y seguimiento de expedientes</p>
            </div>
            <div class="te-page-header-bar__right">
                <a href="#" class="btn btn-primary fw-bold" data-bs-toggle="modal"
                    data-bs-target="#recdartar_tramiteExterno">
                    <i class="ki-outline ki-plus fs-2"></i>

                    REDACTAR
                </a>
            </div>
        </div>

        <!-- Stats Bar -->
        <section class="te-stats" aria-label="Resumen estadístico">
            <div class="te-stat te-animate-in te-animate-delay-1">
                <div class="te-stat__icon te-stat__icon--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </div>
                <div class="te-stat__info">
                    <div class="te-stat__value te-count-animate">148</div>
                    <div class="te-stat__label">Total Documentos</div>
                </div>
            </div>

            <div class="te-stat te-animate-in te-animate-delay-2">
                <div class="te-stat__icon te-stat__icon--warning">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div class="te-stat__info">
                    <div class="te-stat__value te-count-animate">23</div>
                    <div class="te-stat__label">Pendientes</div>
                </div>
            </div>

            <div class="te-stat te-animate-in te-animate-delay-3">
                <div class="te-stat__icon te-stat__icon--success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
                <div class="te-stat__info">
                    <div class="te-stat__value te-count-animate">112</div>
                    <div class="te-stat__label">Atendidos</div>
                </div>
            </div>

            <div class="te-stat te-animate-in te-animate-delay-4">
                <div class="te-stat__icon te-stat__icon--danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                </div>
                <div class="te-stat__info">
                    <div class="te-stat__value te-count-animate">13</div>
                    <div class="te-stat__label">Rechazados</div>
                </div>
            </div>
        </section>

        <!-- ═══ CONTENEDOR PRINCIPAL TIPO CORREO ═══ -->
        <div class="te-inbox-card te-animate-in te-animate-delay-2">

            <div class="te-inbox-layout">

                <!-- ═══ PANEL IZQUIERDO: Filtros + Navegación ═══ -->
                <div class="te-inbox-sidebar">

                    <!-- Usuario -->
                    <div class="te-inbox-user">
                        <div class="te-inbox-user__avatar">DF</div>
                        <div class="te-inbox-user__info">
                            <div class="te-inbox-user__name">Danny Flores</div>
                            <div class="te-inbox-user__role">Recursos Humanos</div>
                        </div>
                    </div>

                    <!-- Menú de Bandejas -->
                    <div class="te-inbox-nav">
                        <div class="te-inbox-nav__title">Bandejas</div>

                        <a href="javascript:void(0)" class="te-inbox-nav__item active" data-tipo="por_recibir">
                            <span class="menu-icon">
                                <i class="ki-outline ki-directbox-default fs-2"></i>
                            </span>
                            <span class="te-inbox-nav__label">Por recibir</span>
                            <span class="te-inbox-nav__badge te-inbox-nav__badge--warning">5</span>
                        </a>

                        <a href="javascript:void(0)" class="te-inbox-nav__item" data-tipo="recibidos">
                            <span class="menu-icon">
                                <i class="ki-outline ki-sms fs-2"></i>
                            </span>
                            <span class="te-inbox-nav__label">Recibidos</span>
                        </a>

                        <a href="javascript:void(0)" class="te-inbox-nav__item" data-tipo="en_atencion">
                            <svg class="te-inbox-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                            <span class="te-inbox-nav__label">En atención</span>
                        </a>

                        <a href="javascript:void(0)" class="te-inbox-nav__item" data-tipo="derivados">
                            <svg class="te-inbox-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            <span class="te-inbox-nav__label">Derivados</span>
                        </a>

                        <a href="javascript:void(0)" class="te-inbox-nav__item" data-tipo="atendidos">
                            <svg class="te-inbox-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            <span class="te-inbox-nav__label">Atendidos</span>
                        </a>
                    </div>

                    <!-- Separador -->
                    <div class="te-inbox-sidebar__divider"></div>

                    <!-- Filtros -->
                    <div class="te-inbox-filters">
                        <div class="te-inbox-filters__header">
                            <span class="te-inbox-filters__title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" width="14" height="14">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                </svg>
                                Filtros
                            </span>

                        </div>

                        <!-- Año -->
                        <div class="te-filter-group">
                            <label class="te-filter-label" for="teFilterYear">Año</label>
                            <select class="te-select te-filter-auto" id="teFilterYear">
                                <option value="" selected>Todos los años</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>

                        <div class="te-filter-divider"></div>

                        <!-- Mes -->
                        <div class="te-filter-group">
                            <label class="te-filter-label" for="teFilterMonth">Mes</label>
                            <select class="te-select te-filter-auto" id="teFilterMonth">
                                <option value="" selected>Todos los meses</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>

                        <div class="te-filter-divider"></div>







                    </div>
                </div>

                <!-- ═══ PANEL DERECHO: Contenido de la Bandeja ═══ -->
                <div class="te-inbox-content">

                    <div class="te-inbox-content__header">
                        <div class="te-inbox-content__header-left">
                            <h3 class="te-inbox-content__title" id="tituloBandeja">Por recibir</h3>
                            <span class="te-inbox-content__subtitle" id="descripcionBandeja">Documentos pendientes
                                de recepción</span>
                        </div>
                        <div class="te-inbox-content__header-right">
                            <div class="te-filter-group">
                                <label class="te-filter-label" for="teFilterYear"></label>
                                <select class="te-select te-filter-auto" id="teFilterYear">
                                    <option value="" selected>Todos los años</option>
                                    <option value="2026">2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="te-filter-group">
                            <label class="te-filter-label" for="teFilterMonth"></label>
                            <select class="te-select te-filter-auto" id="teFilterMonth">
                                <option value="" selected>Todos los meses</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                            <div class="te-search te-search--compact">
                                <svg class="te-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                                <input type="search" class="te-search__input" id="teSearchInput"
                                    placeholder="Buscar expediente..." autocomplete="off">
                                <div class="te-search__kbd"></div>
                            </div>
                            <button class="te-btn te-btn--outline te-btn--sm" id="teExportBtn" title="Exportar datos">
                                <svg class="te-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                Exportar
                            </button>
                        </div>
                    </div>


                    <!-- LOADING -->

                    <div id="loadingBandeja" class="text-center py-20 d-none">

                        <span class="spinner-border text-primary"></span>

                        <div class="text-muted mt-3">
                            Cargando documentos...
                        </div>

                    </div>


                    <!-- DOCUMENTOS -->

                    <div id="contenedorDocumentos" class="px-7">
                    </div>


                    <!-- PAGINACIÓN -->

                    <div class="d-flex flex-stack flex-wrap px-7 py-6 border-top">

                        <span class="text-muted fw-semibold fs-7" id="infoPaginacion">
                        </span>

                        <ul class="pagination" id="paginacionDocumentos">
                        </ul>

                    </div>

                </div>

            </div>

        </div>
    </div>

    </div>


    @include('mesaPartes.redactar')


@endsection
