@extends('layouts.template')

@section('titlePage', 'Trámite Virtual | Ugel Alto Amazonas')

@section('content')

    <style>
        .fut-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #eef2f7;
            padding: 32px;
        }

        .fut-logo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .fut-logo-header img {
            max-height: 60px;
            width: auto;
        }

        .fut-header-banner {
            background: linear-gradient(135deg, #0099db 0%, #0077b6 100%);
            color: #ffffff;
            padding: 16px 24px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 28px;
            box-shadow: 0 4px 14px rgba(0, 153, 219, 0.25);
        }

        .fut-header-banner h2 {
            color: #ffffff !important;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.5rem;
            margin: 0;
            text-transform: uppercase;
        }

        .fut-section-title {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 18px;
            border-left: 4px solid #0099db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .fut-section-title span.required-star {
            color: #ef4444;
            font-weight: bold;
        }

        .fut-field-locked {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #475569 !important;
            font-weight: 600;
            cursor: not-allowed;
        }

        .fut-badge-locked {
            font-size: 0.7rem;
            background-color: #e2e8f0;
            color: #475569;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .fut-file-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            background-color: #f8fafc;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .fut-file-dropzone:hover {
            border-color: #0099db;
            background-color: #f0f9ff;
        }

        .btn-fut-primary {
            background: #0099db;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 153, 219, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-fut-primary:hover {
            background: #0084be;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 153, 219, 0.4);
        }

        .btn-fut-secondary {
            background: #cbd5e1;
            color: #334155;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-fut-secondary:hover {
            background: #94a3b8;
            color: #0f172a;
        }
    </style>

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-lg">

                    <div class="fut-card">

                        <!-- Encabezado con Logo Institucional -->
                        <div class="fut-logo-header text-center mb-4">
                            <img src="{{ url('storage/images/LOGO_UGELAA.png') }}" alt="UGEL Alto Amazonas" class="img-fluid" style="max-height: 70px;" onerror="this.style.display='none'">
                        </div>

                        <!-- Banner Principal: MESA DE PARTES VIRTUAL -->
                        <div class="fut-header-banner">
                            <h2>MESA DE PARTES VIRTUAL</h2>
                        </div>

                        <!-- Formulario de Trámite Virtual -->
                        <form id="form_tramite_virtual" action="#" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- I.- SOLICITUD O RESUMEN DE SU PEDIDO -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    I.- SOLICITUD O RESUMEN DE SU PEDIDO <span class="required-star">(*)</span>
                                </div>
                                <div>
                                    <textarea class="form-control form-control-solid fs-6" name="solicitud_resumen" id="solicitud_resumen" rows="3" placeholder="Escriba aquí un resumen conciso de su solicitud o pedido..." required></textarea>
                                </div>
                            </div>

                            <!-- II.- DEPENDENCIA O AUTORIDAD A QUIEN SE DIRIGE -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    II.- DEPENDENCIA O AUTORIDAD A QUIEN SE DIRIGE
                                </div>
                                <div class="p-4 rounded bg-light border border-secondary border-opacity-25 fw-bold text-gray-800 fs-5 d-flex align-items-center gap-3">
                                    <i class="ki-outline ki-profile-circle fs-2 text-primary"></i>
                                    <span>DIRECTOR UGELAA</span>
                                </div>
                            </div>

                            <!-- III.- DATOS DEL SOLICITANTE -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    III.- DATOS DEL SOLICITANTE <span class="required-star">(*)</span>
                                </div>

                                <div class="row g-4 mb-4">
                                    <!-- Tipo de Persona -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-gray-700">Tipo de Persona <span class="text-danger">*</span></label>
                                        <select class="form-select" id="tipo_persona" name="tipo_persona" onchange="toggleTipoPersona()">
                                            <option value="NATURAL" selected>NATURAL</option>
                                            <option value="JURIDICA">JURÍDICA</option>
                                        </select>
                                    </div>

                                    <!-- Nro. Documento -->
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-semibold text-gray-700 mb-0">Nro. Documento <span class="text-danger">*</span></label>
                                            @if($usuario && !empty($usuario->dni))
                                                <span class="fut-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                            @endif
                                        </div>
                                        <input type="text" class="form-control @if($usuario && !empty($usuario->dni)) fut-field-locked @endif" 
                                               id="nro_documento" name="nro_documento" 
                                               value="{{ $usuario->dni ?? '' }}" 
                                               placeholder="Nro. Documento / DNI" 
                                               @if($usuario && !empty($usuario->dni)) readonly @endif required>
                                    </div>
                                </div>

                                <!-- CAMPOS PERSONA NATURAL -->
                                <div id="seccion_persona_natural">
                                    <div class="row g-4">
                                        <!-- Apellido Paterno -->
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="form-label fw-semibold text-gray-700 mb-0">Apellido Paterno <span class="text-danger">*</span></label>
                                                @if(!empty($apellidoPaterno))
                                                    <span class="fut-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                                @endif
                                            </div>
                                            <input type="text" class="form-control @if(!empty($apellidoPaterno)) fut-field-locked @endif" 
                                                   id="apellido_paterno" name="apellido_paterno" 
                                                   value="{{ $apellidoPaterno }}" 
                                                   placeholder="Apellido Paterno" 
                                                   @if(!empty($apellidoPaterno)) readonly @endif>
                                        </div>

                                        <!-- Apellido Materno -->
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="form-label fw-semibold text-gray-700 mb-0">Apellido Materno <span class="text-danger">*</span></label>
                                                @if(!empty($apellidoMaterno))
                                                    <span class="fut-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                                @endif
                                            </div>
                                            <input type="text" class="form-control @if(!empty($apellidoMaterno)) fut-field-locked @endif" 
                                                   id="apellido_materno" name="apellido_materno" 
                                                   value="{{ $apellidoMaterno }}" 
                                                   placeholder="Apellido Materno" 
                                                   @if(!empty($apellidoMaterno)) readonly @endif>
                                        </div>

                                        <!-- Nombres -->
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="form-label fw-semibold text-gray-700 mb-0">Nombres <span class="text-danger">*</span></label>
                                                @if($usuario && !empty($usuario->nombres))
                                                    <span class="fut-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                                @endif
                                            </div>
                                            <input type="text" class="form-control @if($usuario && !empty($usuario->nombres)) fut-field-locked @endif" 
                                                   id="nombres" name="nombres" 
                                                   value="{{ $usuario->nombres ?? '' }}" 
                                                   placeholder="Nombres completos" 
                                                   @if($usuario && !empty($usuario->nombres)) readonly @endif>
                                        </div>
                                    </div>
                                </div>

                                <!-- CAMPOS PERSONA JURÍDICA -->
                                <div id="seccion_persona_juridica" style="display: none;" class="mt-4">
                                    <div class="p-4 rounded border border-secondary border-opacity-25 bg-light-subtle">
                                        <h6 class="fw-bold text-gray-800 mb-3">PERSONA JURÍDICA</h6>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-gray-700">Razón Social <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón Social de la Empresa o Institución">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-gray-700">RUC <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="ruc" name="ruc" placeholder="Número de RUC (11 dígitos)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- IV.- DIRECCIÓN -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    IV.- DIRECCIÓN
                                </div>

                                <div class="row g-4 mb-4">
                                    <!-- Dirección -->
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold text-gray-700">Dirección <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección domiciliaria completa" required>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <!-- Referencia -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-gray-700">Referencia</label>
                                        <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Referencia de ubicación (Opcional)">
                                    </div>

                                    <!-- Distrito -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-gray-700">Distrito <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="distrito" name="distrito" placeholder="Distrito (ej: Yurimaguas)" required>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Teléfono -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-gray-700">Teléfono / Celular <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Número de contacto" required>
                                    </div>

                                    <!-- Correo -->
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-semibold text-gray-700 mb-0">Correo Electrónico <span class="text-danger">*</span></label>
                                            @if($usuario && !empty($usuario->correo))
                                                <span class="fut-badge-locked"><i class="ki-outline ki-lock fs-8"></i> Registrado</span>
                                            @endif
                                        </div>
                                        <input type="email" class="form-control @if($usuario && !empty($usuario->correo)) fut-field-locked @endif" 
                                               id="correo" name="correo" 
                                               value="{{ $usuario->correo ?? '' }}" 
                                               placeholder="Correo electrónico de contacto" 
                                               @if($usuario && !empty($usuario->correo)) readonly @endif required>
                                    </div>
                                </div>
                            </div>

                            <!-- V.- FUNDAMENTOS DEL PEDIDO -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    V.- FUNDAMENTOS DEL PEDIDO <span class="required-star">(*)</span>
                                </div>
                                <div>
                                    <textarea class="form-control form-control-solid fs-6" name="fundamentos_pedido" id="fundamentos_pedido" rows="4" placeholder="Sustente o argumente detalladamente los motivos de su pedido..." required></textarea>
                                </div>
                            </div>

                            <!-- VI.- DOCUMENTOS QUE SE ADJUNTAN -->
                            <div class="mb-8">
                                <div class="fut-section-title">
                                    VI.- DOCUMENTOS QUE SE ADJUNTAN
                                </div>
                                <div class="fut-file-dropzone" onclick="document.getElementById('archivos_adjuntos').click()">
                                    <i class="ki-outline ki-file-up fs-3x text-primary mb-2"></i>
                                    <div class="fw-bold text-gray-800 fs-6">Haga clic aquí para elegir archivos o arrástrelos</div>
                                    <div class="text-gray-500 fs-7 mt-1">Formatos soportados: PDF, DOCX, JPG, PNG, ZIP (Máx. 20MB)</div>
                                    <input type="file" id="archivos_adjuntos" name="archivos[]" multiple style="display: none;" onchange="actualizarArchivosSeleccionados(this)">
                                </div>
                                <div id="lista_archivos" class="mt-3"></div>
                            </div>

                            <!-- Botones de Acción y Nota -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-4 pt-4 border-top">
                                <div class="text-danger fw-semibold fs-7 text-center text-sm-start">
                                    (*) Debe completar los campos obligatorios para continuar...
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="button" class="btn-fut-primary" onclick="alert('Prototipo de Trámite Virtual registrado correctamente.')">
                                        <i class="ki-outline ki-disk fs-2"></i>
                                        REGISTRAR FUT
                                    </button>
                                    <button type="button" class="btn-fut-secondary" onclick="window.location.reload()">
                                        <i class="ki-outline ki-eye fs-2"></i>
                                        CONSULTAR FUT
                                    </button>
                                </div>
                            </div>

                        </form>

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

        function actualizarArchivosSeleccionados(input) {
            const container = document.getElementById('lista_archivos');
            container.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                const listGroup = document.createElement('div');
                listGroup.className = 'list-group';
                
                Array.from(input.files).forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3 fs-7 bg-light';
                    item.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-outline ki-file fs-4 text-primary"></i>
                            <span class="fw-semibold text-gray-800">${file.name}</span>
                            <span class="text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        </div>
                    `;
                    listGroup.appendChild(item);
                });
                
                container.appendChild(listGroup);
            }
        }
    </script>

@endsection

