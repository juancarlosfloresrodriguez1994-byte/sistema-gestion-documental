@extends('layouts.template')

@section('titlePage', 'Registrar Trámite Virtual | Ugel Alto Amazonas')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid p-0">

                    <div class="tv-create-wrapper">

                        {{-- Botón para regresar al historial --}}
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('tramite-virtual.index') }}" class="btn btn-sm btn-light-primary fw-bold">
                                <i class="ki-outline ki-arrow-left fs-4 me-1"></i>
                                Volver al Historial
                            </a>
                            <span class="text-muted fs-7">
                                Paso obligatorio: Complete los campos para emitir su expediente virtual
                            </span>
                        </div>

                        {{-- Mensajes de Error o Alerta --}}
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-6 rounded-3">
                                <i class="ki-outline ki-information-5 fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="mb-1 text-danger">Por favor corrija los siguientes errores:</h5>
                                    <ul class="mb-0 ps-4 fs-7">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="tv-card-container">

                            {{-- Banner Hero --}}
                            <div class="tv-hero-banner">
                                <div class="tv-hero-header-top">
                                    <div class="tv-hero-institution">
                                        <img src="{{ url('storage/images/LOGO_UGELAA_N.png') }}" alt="UGELAA" class="tv-hero-logo" onerror="this.style.display='none'">
                                        <div class="tv-hero-inst-text">
                                            Unidad de Gestión Educativa Local<br>
                                            <strong>Alto Amazonas - Yurimaguas</strong>
                                        </div>
                                    </div>
                                    <div class="tv-hero-badge">
                                        <i class="ki-outline ki-shield-tick fs-6"></i>
                                        FORMULARIO ÚNICO DE TRÁMITE (FUT)
                                    </div>
                                </div>
                                <h1 class="tv-hero-title">MESA DE PARTES VIRTUAL</h1>
                                <p class="tv-hero-subtitle">
                                    Ingrese la información correspondiente para generar y derivar su trámite digital institucionalmente.
                                </p>
                            </div>

                            {{-- Formulario --}}
                            <form id="form_tramite_virtual" action="{{ route('tramite-virtual.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="tv-form-body">

                                    {{-- I.- SOLICITUD O RESUMEN DE SU PEDIDO --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">I</div>
                                            <h2 class="tv-section-title-text">
                                                SOLICITUD O RESUMEN DE SU PEDIDO <span class="required-star">(*)</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <textarea class="form-control form-control-solid fs-6 border" name="solicitud_resumen" id="solicitud_resumen" rows="3" placeholder="Sintetice de manera clara el asunto o pedido de su trámite..." required>{{ old('solicitud_resumen') }}</textarea>
                                            <div class="form-text text-muted fs-8 mt-2">
                                                Ejemplo: Solicito constancia de haberes y tiempo de servicios.
                                            </div>
                                        </div>
                                    </div>

                                    {{-- II.- DEPENDENCIA O AUTORIDAD A QUIEN SE DIRIGE --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">II</div>
                                            <h2 class="tv-section-title-text">
                                                DEPENDENCIA O AUTORIDAD A QUIEN SE DIRIGE
                                            </h2>
                                        </div>
                                        <div class="p-4 rounded-3 bg-light border d-flex align-items-center gap-3">
                                            <div class="w-40px h-40px rounded-circle bg-white border d-flex align-items-center justify-content-center text-primary fs-3 shadow-sm">
                                                <i class="ki-outline ki-profile-circle"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-gray-900 fs-5">DIRECTOR UGELAA</div>
                                                <div class="text-muted fs-8">Dirección de la Unidad de Gestión Educativa Local Alto Amazonas</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- III.- DATOS DEL SOLICITANTE --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">III</div>
                                            <h2 class="tv-section-title-text">
                                                DATOS DEL SOLICITANTE <span class="required-star">(*)</span>
                                            </h2>
                                        </div>

                                        <div class="row g-4 mb-4">
                                            {{-- Tipo de Persona --}}
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-gray-700">Tipo de Persona <span class="text-danger">*</span></label>
                                                <select class="form-select border" id="tipo_persona" name="tipo_persona" onchange="toggleTipoPersona()">
                                                    <option value="NATURAL" {{ old('tipo_persona') == 'NATURAL' ? 'selected' : '' }}>PERSONA NATURAL</option>
                                                    <option value="JURIDICA" {{ old('tipo_persona') == 'JURIDICA' ? 'selected' : '' }}>PERSONA JURÍDICA</option>
                                                </select>
                                            </div>

                                            {{-- Nro. Documento --}}
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="form-label fw-bold text-gray-700 mb-0">Nro. Documento de Identidad <span class="text-danger">*</span></label>
                                                    @if($usuario && !empty($usuario->dni))
                                                        <span class="tv-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado en el sistema</span>
                                                    @endif
                                                </div>
                                                <input type="text" class="form-control border @if($usuario && !empty($usuario->dni)) tv-input-locked @endif" 
                                                       id="nro_documento" name="nro_documento" 
                                                       value="{{ old('nro_documento', $usuario->dni ?? '') }}" 
                                                       placeholder="Número de DNI / Documento" 
                                                       @if($usuario && !empty($usuario->dni)) readonly @endif required>
                                            </div>
                                        </div>

                                        {{-- CAMPOS PERSONA NATURAL --}}
                                        <div id="seccion_persona_natural">
                                            <div class="row g-4">
                                                {{-- Apellido Paterno --}}
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label fw-bold text-gray-700 mb-0">Apellido Paterno <span class="text-danger">*</span></label>
                                                        @if(!empty($apellidoPaterno))
                                                            <span class="tv-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                                        @endif
                                                    </div>
                                                    <input type="text" class="form-control border @if(!empty($apellidoPaterno)) tv-input-locked @endif" 
                                                           id="apellido_paterno" name="apellido_paterno" 
                                                           value="{{ old('apellido_paterno', $apellidoPaterno) }}" 
                                                           placeholder="Apellido Paterno" 
                                                           @if(!empty($apellidoPaterno)) readonly @endif>
                                                </div>

                                                {{-- Apellido Materno --}}
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label fw-bold text-gray-700 mb-0">Apellido Materno <span class="text-danger">*</span></label>
                                                        @if(!empty($apellidoMaterno))
                                                            <span class="tv-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                                        @endif
                                                    </div>
                                                    <input type="text" class="form-control border @if(!empty($apellidoMaterno)) tv-input-locked @endif" 
                                                           id="apellido_materno" name="apellido_materno" 
                                                           value="{{ old('apellido_materno', $apellidoMaterno) }}" 
                                                           placeholder="Apellido Materno" 
                                                           @if(!empty($apellidoMaterno)) readonly @endif>
                                                </div>

                                                {{-- Nombres --}}
                                                <div class="col-md-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label fw-bold text-gray-700 mb-0">Nombres <span class="text-danger">*</span></label>
                                                        @if($usuario && !empty($usuario->nombres))
                                                            <span class="tv-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado en el sistema</span>
                                                        @endif
                                                    </div>
                                                    <input type="text" class="form-control border @if($usuario && !empty($usuario->nombres)) tv-input-locked @endif" 
                                                           id="nombres" name="nombres" 
                                                           value="{{ old('nombres', $usuario->nombres ?? '') }}" 
                                                           placeholder="Nombres completos" 
                                                           @if($usuario && !empty($usuario->nombres)) readonly @endif>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- CAMPOS PERSONA JURÍDICA --}}
                                        <div id="seccion_persona_juridica" style="display: none;" class="mt-4">
                                            <div class="p-4 rounded-3 border bg-light">
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-700">Razón Social <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control border" id="razon_social" name="razon_social" 
                                                               value="{{ old('razon_social') }}" placeholder="Nombre o Razón Social de la Entidad">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-700">RUC <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control border" id="ruc" name="ruc" 
                                                               value="{{ old('ruc') }}" placeholder="Número de RUC (11 dígitos)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- IV.- DIRECCIÓN Y DATOS DE CONTACTO --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">IV</div>
                                            <h2 class="tv-section-title-text">
                                                DIRECCIÓN Y DATOS DE CONTACTO <span class="required-star">(*)</span>
                                            </h2>
                                        </div>

                                        <div class="row g-4 mb-4">
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold text-gray-700">Dirección Domiciliaria <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border" id="direccion" name="direccion" 
                                                       value="{{ old('direccion') }}" placeholder="Av. / Jr. / Calle y Nro." required>
                                            </div>
                                        </div>

                                        <div class="row g-4 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-gray-700">Referencia de Ubicación</label>
                                                <input type="text" class="form-control border" id="referencia" name="referencia" 
                                                       value="{{ old('referencia') }}" placeholder="Cerca al parque, frente a institución...">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-gray-700">Distrito <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border" id="distrito" name="distrito" 
                                                       value="{{ old('distrito', 'Yurimaguas') }}" placeholder="Distrito (ej. Yurimaguas)" required>
                                            </div>
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-gray-700">Teléfono / Celular <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control border" id="telefono" name="telefono" 
                                                       value="{{ old('telefono') }}" placeholder="Número de teléfono móvil o fijo" required>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="form-label fw-bold text-gray-700 mb-0">Correo Electrónico <span class="text-danger">*</span></label>
                                                    @if($usuario && !empty($usuario->correo))
                                                        <span class="tv-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado en el sistema</span>
                                                    @endif
                                                </div>
                                                <input type="email" class="form-control border @if($usuario && !empty($usuario->correo)) tv-input-locked @endif" 
                                                       id="correo" name="correo" 
                                                       value="{{ old('correo', $usuario->correo ?? '') }}" 
                                                       placeholder="ejemplo@correo.com" 
                                                       @if($usuario && !empty($usuario->correo)) readonly @endif required>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- V.- FUNDAMENTOS DEL PEDIDO --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">V</div>
                                            <h2 class="tv-section-title-text">
                                                FUNDAMENTOS DEL PEDIDO <span class="required-star">(*)</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <textarea class="form-control form-control-solid fs-6 border" name="fundamentos_pedido" id="fundamentos_pedido" rows="4" placeholder="Fundamente y sustente las razones o motivos de su requerimiento..." required>{{ old('fundamentos_pedido') }}</textarea>
                                        </div>
                                    </div>

                                    {{-- VI.- DOCUMENTOS QUE SE ADJUNTAN --}}
                                    <div class="tv-section">
                                        <div class="tv-section-header">
                                            <div class="tv-section-number">VI</div>
                                            <h2 class="tv-section-title-text">
                                                DOCUMENTOS QUE SE ADJUNTAN (OPCIONAL)
                                            </h2>
                                        </div>
                                        <div class="tv-dropzone" onclick="document.getElementById('archivos_adjuntos').click()">
                                            <div class="tv-dropzone-icon">
                                                <i class="ki-outline ki-file-up"></i>
                                            </div>
                                            <div class="fw-bold text-gray-800 fs-5 mb-1">Haga clic para examinar o arrastre sus documentos aquí</div>
                                            <div class="text-muted fs-7">Formatos permitidos: PDF, DOCX, JPG, PNG, ZIP (Tamaño máximo: 20MB)</div>
                                            <input type="file" id="archivos_adjuntos" name="archivos[]" multiple style="display: none;" onchange="actualizarArchivosSeleccionados(this)">
                                        </div>
                                        <div id="lista_archivos" class="mt-3"></div>
                                    </div>

                                </div>

                                {{-- Barra de Acciones Final --}}
                                <div class="tv-action-bar">
                                    <div class="text-danger fw-semibold fs-7 d-flex align-items-center gap-1">
                                        <i class="ki-outline ki-information-2 text-danger fs-5"></i>
                                        (*) Debe completar todos los campos obligatorios para registrar su trámite.
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('tramite-virtual.index') }}" class="btn-fut-cancel">
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn-fut-submit" id="btn_submit_fut">
                                            <i class="ki-outline ki-send fs-3"></i>
                                            FINALIZAR Y REGISTRAR TRÁMITE
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTipoPersona() {
            const tipo = document.getElementById('tipo_persona').value;
            const secJuridica = document.getElementById('seccion_persona_juridica');

            if (tipo === 'JURIDICA') {
                secJuridica.style.display = 'block';
            } else {
                secJuridica.style.display = 'none';
            }
        }

        // Ejecutar en inicio por si viene de un old value
        document.addEventListener('DOMContentLoaded', function() {
            toggleTipoPersona();
        });

        function actualizarArchivosSeleccionados(input) {
            const container = document.getElementById('lista_archivos');
            container.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                const listGroup = document.createElement('div');
                listGroup.className = 'list-group shadow-xs';
                
                Array.from(input.files).forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3 fs-7 bg-light border';
                    item.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-outline ki-file fs-4 text-primary"></i>
                            <span class="fw-semibold text-gray-800">${file.name}</span>
                            <span class="badge badge-light-secondary">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        </div>
                        <span class="badge badge-light-success fw-bold">Adjunto</span>
                    `;
                    listGroup.appendChild(item);
                });
                
                container.appendChild(listGroup);
            }
        }
    </script>

@endsection
