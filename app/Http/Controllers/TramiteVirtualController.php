<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expediente;
use App\Models\ExpedienteMovimiento;
use App\Models\Solicitante;
use App\Models\TipoDocumento;
use App\Models\Area;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TramiteVirtualController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('can:tramite-virtual.index')->only('index');
        $this->middleware('auth');
    }

    /**
     * Historial de trámites virtuales y métricas del usuario autenticado.
     */
    public function index(Request $request)
    {
        $usuario = auth()->user();

        // Obtener ID de solicitante asociado si existe con el DNI del usuario
        $solicitante = Solicitante::where('soli_documento', $usuario->dni)->first();
        $solicitanteId = $solicitante ? $solicitante->id : null;

        // Consulta de expedientes/trámites virtuales del usuario
        $query = Expediente::leftJoin('tipo_documento', 'tipo_documento.id', '=', 'tramite_externo.tipoDocumento_id')
            ->leftJoin('dominio', 'dominio.id', '=', 'tramite_externo.dominio_destino_id')
            ->select(
                'tramite_externo.*',
                'tipo_documento.descripcion as tipo_documento_nombre',
                'dominio.domi_nombre as area_actual_nombre'
            )
            ->where(function ($q) use ($usuario, $solicitanteId) {
                $q->where('tramite_externo.usuario_registro_id', $usuario->id);
                if ($solicitanteId) {
                    $q->orWhere('tramite_externo.solicitante_id', $solicitanteId);
                }
                if (!empty($usuario->dni)) {
                    $q->orWhere('tramite_externo.documento_solicitante', $usuario->dni);
                }
            });

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('tramite_externo.estado', $request->estado);
        }

        // Filtro de búsqueda por número de expediente o asunto
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('tramite_externo.numero_expediente', 'like', "%{$buscar}%")
                    ->orWhere('tramite_externo.asunto', 'like', "%{$buscar}%")
                    ->orWhere('tramite_externo.descripcion', 'like', "%{$buscar}%");
            });
        }

        // Estadísticas generales del usuario
        $baseStatsQuery = Expediente::where(function ($q) use ($usuario, $solicitanteId) {
            $q->where('usuario_registro_id', $usuario->id);
            if ($solicitanteId) {
                $q->orWhere('solicitante_id', $solicitanteId);
            }
            if (!empty($usuario->dni)) {
                $q->orWhere('documento_solicitante', $usuario->dni);
            }
        });

        $totalTramites = (clone $baseStatsQuery)->count();
        $totalPendientes = (clone $baseStatsQuery)->whereIn('estado', ['POR_RECIBIR', 'PENDIENTE'])->count();
        $totalAtendidos = (clone $baseStatsQuery)->whereIn('estado', ['ATENDIDO', 'CONCLUIDO', 'FINALIZADO'])->count();
        $totalEnProceso = (clone $baseStatsQuery)->whereIn('estado', ['PROCESO', 'DERIVADO'])->count();

        $tramites = $query->orderByDesc('tramite_externo.id')->paginate(10);

        $opciones = [
            'titlePage'       => 'Historial de Trámites Virtuales',
            'usuario'         => $usuario,
            'tramites'        => $tramites,
            'totalTramites'   => $totalTramites,
            'totalPendientes' => $totalPendientes,
            'totalAtendidos'  => $totalAtendidos,
            'totalEnProceso'  => $totalEnProceso,
        ];

        return view('tramiteVirtual.index', $opciones);
    }

    /**
     * Muestra el formulario para crear un nuevo Trámite Virtual (FUT).
     */
    public function create()
    {
        $usuario = auth()->user();

        // Separar apellidos si están presentes
        $apellidoPaterno = '';
        $apellidoMaterno = '';
        if ($usuario && !empty($usuario->apellidos)) {
            $parts = explode(' ', trim($usuario->apellidos), 2);
            $apellidoPaterno = $parts[0] ?? '';
            $apellidoMaterno = $parts[1] ?? '';
        }

        $opciones = [
            'titlePage'       => 'Registrar Trámite Virtual (FUT)',
            'usuario'         => $usuario,
            'apellidoPaterno' => $apellidoPaterno,
            'apellidoMaterno' => $apellidoMaterno,
        ];

        return view('tramiteVirtual.create', $opciones);
    }

    /**
     * Almacena el Trámite Virtual en la base de datos y redirige al historial.
     */
    public function store(Request $request)
    {
        $usuario = auth()->user();

        $validator = Validator::make($request->all(), [
            'solicitud_resumen'   => 'required|string|max:500',
            'tipo_persona'        => 'required|in:NATURAL,JURIDICA',
            'nro_documento'       => 'required|string|max:20',
            'direccion'           => 'required|string|max:255',
            'distrito'            => 'required|string|max:100',
            'telefono'            => 'required|string|max:20',
            'correo'              => 'required|email|max:150',
            'fundamentos_pedido'  => 'required|string',
            'archivos.*'          => 'nullable|file|max:20480',
        ], [
            'solicitud_resumen.required'  => 'El campo Resumen o Solicitud de su pedido es obligatorio.',
            'direccion.required'          => 'La dirección domiciliaria es obligatoria.',
            'distrito.required'           => 'El distrito es obligatorio.',
            'telefono.required'           => 'El teléfono o celular es obligatorio.',
            'correo.required'             => 'El correo electrónico es obligatorio.',
            'fundamentos_pedido.required' => 'Los fundamentos del pedido son obligatorios.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $documento = $request->nro_documento ?: $usuario->dni;
            $nombres = $request->tipo_persona === 'NATURAL' ? ($request->nombres ?: $usuario->nombres) : $request->razon_social;
            $apellidoPaterno = $request->apellido_paterno ?: '';
            $apellidoMaterno = $request->apellido_materno ?: '';
            $nombreCompleto = $request->tipo_persona === 'NATURAL'
                ? trim("{$apellidoPaterno} {$apellidoMaterno}, {$nombres}")
                : $request->razon_social;

            // 1. Buscar o registrar al Solicitante
            $solicitante = Solicitante::where('soli_documento', $documento)->first();

            if (!$solicitante) {
                $solicitante = Solicitante::create([
                    'soli_nombre'       => $nombres,
                    'soli_apellido'     => trim("{$apellidoPaterno} {$apellidoMaterno}"),
                    'soli_documento'    => $documento,
                    'soli_razonsocial'  => $request->tipo_persona === 'JURIDICA' ? $request->razon_social : null,
                    'soli_direccion'    => $request->direccion,
                    'soli_telefono'     => $request->telefono,
                    'soli_correo'       => $request->correo,
                    'soli_tipo'         => $request->tipo_persona,
                    'nombre_completo'   => $nombreCompleto ?: $nombres,
                ]);
            } else {
                // Actualizar datos de contacto si cambiaron
                $solicitante->update([
                    'soli_direccion' => $request->direccion,
                    'soli_telefono'  => $request->telefono,
                    'soli_correo'    => $request->correo,
                ]);
            }

            // 2. Generar correlativo del expediente
            $anio = now()->year;
            $numero_mes = now()->month;
            $mes = now()->locale('es')->monthName;

            $ultimo = Expediente::where('anio', $anio)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $correlativo = $ultimo
                ? ((int) substr($ultimo->numero_expediente, -6)) + 1
                : 1;

            $numeroExpediente = 'EXP-' . $anio . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT);

            // 3. Procesar adjuntos (si existen)
            $adjuntoPath = null;
            if ($request->hasFile('archivos')) {
                $archivos = $request->file('archivos');
                $rutas = [];
                foreach ($archivos as $arc) {
                    $nombreArchivo = time() . '_' . $arc->getClientOriginalName();
                    $path = $arc->storeAs('tramites_virtuales', $nombreArchivo, 'public');
                    $rutas[] = $path;
                }
                $adjuntoPath = implode(',', $rutas);
            }

            // Tipo de documento FUT
            $tipoDoc = TipoDocumento::where('descripcion', 'LIKE', '%FUT%')->first();
            $tipoDocId = $tipoDoc ? $tipoDoc->id : 1;

            // Área destino inicial (Mesa de partes o Administración)
            $areaInicial = Area::first();
            $areaId = $areaInicial ? $areaInicial->id : 1;

            // Usuario destino inicial en esa área
            $usuarioDestino = Usuario::where('area_id', $areaId)->where('estado', 1)->first() ?: $usuario;

            // 4. Crear expediente
            $expediente = Expediente::create([
                'numero_expediente'    => $numeroExpediente,
                'asunto'               => $request->solicitud_resumen,
                'descripcion'          => $request->fundamentos_pedido,
                'fecha_registro'       => now()->format('Y-m-d'),
                'hora_registro'        => now()->format('H:i:s'),
                'numero_documento'     => 'FUT-' . str_pad($correlativo, 4, '0', STR_PAD_LEFT),
                'numero_folio'         => 1,
                'solicitante_id'       => $solicitante->id,
                'soli_tipo'            => $request->tipo_persona,
                'nombre_solicitante'   => $nombreCompleto,
                'documento_solicitante'=> $documento,
                'tipoDocumento_id'     => $tipoDocId,
                'nombre_documento'     => 'FUT',
                'tipo_tramite'         => 'VIRTUAL',
                'anio'                 => $anio,
                'estado'               => 'POR_RECIBIR',
                'mes'                  => $mes,
                'dominio_destino_id'   => 6,
                'usuario_registro_id'  => $usuario->id,
                'documento_adjunto'    => $adjuntoPath,
            ]);

            // 5. Registrar movimiento inicial
            ExpedienteMovimiento::create([
                'expediente_id'        => $expediente->id,
                'dominio_origen_id'    => null,
                'usuario_origen_id'    => $usuario->id,
                'dominio_destino_id'   => 6,
                'usuario_destino_id'   => $usuarioDestino ? $usuarioDestino->id : null,
                'tipo_movimiento'      => 'REGISTRO_VIRTUAL',
                'estado'               => 'POR_RECIBIR',
                'fecha_movimiento'     => now()->format('Y-m-d H:i:s'),
                'usuario_accion_id'    => $usuario->id,
            ]);

            DB::commit();

            return redirect()->route('tramite-virtual.index')
                ->with('success', "¡Trámite virtual registrado exitosamente con N° de Expediente: {$numeroExpediente}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ocurrió un error al procesar el trámite virtual: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tramite = Expediente::findOrFail($id);
        return redirect()->route('tramite-virtual.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('tramite-virtual.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('tramite-virtual.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('tramite-virtual.index');
    }
}

