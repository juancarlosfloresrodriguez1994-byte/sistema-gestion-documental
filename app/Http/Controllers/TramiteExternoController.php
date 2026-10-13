<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use App\Models\Area;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Expediente;
use App\Models\ExpedienteMovimiento;
use App\Models\SubEquipo;
use App\Models\Dominio;
use App\Models\Procedimientos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramiteExternoController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('can:tramite-externo.index')->only('index');
        $this->middleware('auth');
    }

    public function index()
    {
        $tipos = TipoDocumento::orderBy('descripcion')->get();
        $area = Area::orderBy('nombre_area')->get();
        $procedimientos = Procedimientos::orderBy('prod_descripcion')->get();
        $direccion = Dominio::find(6);

        $opciones = [
            'tipos'          => $tipos,
            'area'           => $area,
            'procedimientos' => $procedimientos,
            'direccion'      => $direccion,
        ];

        return view('mesaPartes.index', $opciones);
    }

    public function filterEquipo(Request $request)
    {
        $equipo = Equipo::where('are_id', $request->area_id)->get();
        $usuario = Usuario::where('area_id', $request->area_id)->get();

        return response()->json([
            'equipo'  => $equipo,
            'usuario' => $usuario,
        ]);
    }

    public function filtersubEquipo(Request $request)
    {
        $SubEquipo = SubEquipo::where('equipo_id', $request->equipo_id)->get();

        return response()->json([
            'SubEquipo' => $SubEquipo
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Todo trámite externo va dirigido exclusivamente a la máxima jerarquía:
     * La Dirección (Dominio ID: 6).
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $rules = [
            'solicitante_id'   => ['required'],
            'tipoDocumento_id' => ['required'],
            'numero_documento' => ['required'],
            'numero_folio'     => ['required'],
            'descripcion'      => ['required'],
            'asunto'           => ['required'],
        ];

        $messages = [
            'solicitante_id.required'   => 'El campo <strong class="text-uppercase">Solicitante</strong> es obligatorio.',
            'tipoDocumento_id.required' => 'El campo <strong class="text-uppercase">Documento</strong> es obligatorio.',
            'numero_documento.required' => 'El campo <strong class="text-uppercase">N° Documento</strong> es obligatorio.',
            'numero_folio.required'     => 'El campo <strong class="text-uppercase">N° Folio</strong> es obligatorio.',
            'descripcion.required'      => 'El campo <strong class="text-uppercase">Descripción</strong> es obligatorio.',
            'asunto.required'           => 'El campo <strong class="text-uppercase">Asunto</strong> es obligatorio.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores'   => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $anio = now()->year;
            $numero_mes = now()->month;
            $mes = now()->locale('es')->monthName;

            /*
            |--------------------------------------------------------------------------
            | Generar número de expediente
            |--------------------------------------------------------------------------
            */
            $ultimo = Expediente::where('anio', $anio)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $correlativo = $ultimo
                ? ((int) substr($ultimo->numero_expediente, -6)) + 1
                : 1;

            $numeroExpediente = 'EXP-' . $anio . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT);

            $usuario = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | Destino institucional: LA DIRECCIÓN (Dominio ID 6)
            |--------------------------------------------------------------------------
            | Según indicación, todo trámite externo va dirigido a La Dirección.
            | Se busca al usuario responsable que tenga dominio_id = 6.
            | Si aún no se asignó usuario a Dirección, se utiliza fallback preventivo.
            */
            $dominioDestinoId = 6;

            $usuarioDestino = Usuario::where('dominio_id', $dominioDestinoId)
                ->where('estado', 1)
                ->first();

            if (!$usuarioDestino) {
                // Fallback preventivo (SuperAdmin o usuario autenticado) mientras se configura Dominio Usuario
                $usuarioDestino = Usuario::where('tipoUsuario_id', 1)->where('estado', 1)->first() ?? $usuario;
            }

            $usuarioDestinoId = $usuarioDestino ? $usuarioDestino->id : null;

            // Dominio de origen: el dominio asignado al usuario de mesa de partes o 80 (Mesa de Partes)
            $dominioOrigenId = $usuario->dominio_id ?? 80;

            // Manejo de archivo adjunto si se envía
            $documentoAdjunto = null;
            if ($request->hasFile('calendar_event_location')) {
                $file = $request->file('calendar_event_location');
                $nombreArchivo = time() . '_' . $file->getClientOriginalName();
                $documentoAdjunto = $file->storeAs('expedientes', $nombreArchivo, 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | Crear Expediente (tramite_externo)
            |--------------------------------------------------------------------------
            | Se eliminan los campos redundantes granulares (area_actual_id, equipo_actual_id, etc.)
            | y se unifica en dominio_destino_id = 6.
            */
            $expediente = Expediente::create([
                'numero_expediente'   => $numeroExpediente,
                'anio'                => $anio,
                'mes'                 => $mes,
                'numero_mes'          => $numero_mes,
                'tipo_tramite'        => $request->tipo_tramite ?? 'SIN_TUPA',
                'solicitante_id'      => $request->solicitante_id,
                'numero_folio'        => $request->numero_folio,
                'tipoDocumento_id'    => $request->tipoDocumento_id,
                'numero_documento'    => $request->numero_documento,
                'descripcion'         => $request->descripcion,
                'asunto'              => $request->asunto,
                'dominio_destino_id'  => $dominioDestinoId,
                'estado'              => 'POR_RECIBIR',
                'usuario_registro_id' => $usuario->id,
                'fecha_registro'      => now(),
                'hora_registro'       => now()->format('H:i:s'),
                'documento_adjunto'   => $documentoAdjunto,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Registrar primer movimiento (movimientos_tramiteexterno)
            |--------------------------------------------------------------------------
            | Solo almacena dominio_origen_id y dominio_destino_id (La Dirección).
            */
            ExpedienteMovimiento::create([
                'expediente_id'      => $expediente->id,
                'dominio_origen_id'  => $dominioOrigenId,
                'usuario_origen_id'  => $usuario->id,
                'dominio_destino_id' => $dominioDestinoId,
                'usuario_destino_id' => $usuarioDestinoId,
                'tipo_movimiento'    => 'REGISTRO',
                'estado'             => 'POR_RECIBIR',
                'fecha_movimiento'   => now(),
                'usuario_accion_id'  => $usuario->id,
            ]);

            DB::commit();

            return response()->json([
                'success'           => true,
                'message'           => 'Expediente registrado correctamente.',
                'expediente_id'     => $expediente->id,
                'numero_expediente' => $expediente->numero_expediente,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No se pudo registrar el expediente.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API para listar expedientes y movimientos según bandeja y filtros.
     * Unificado para usar relaciones con la tabla dominio.
     */
    public function apiTramiteExterno(Request $request)
    {
        $tipo = $request->input('tipo', 'por_recibir');
        $buscar = trim($request->input('buscar', ''));
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $user = Auth::user();
        $userId = $user->id;
        $userDominioId = $user->dominio_id;
        $isSuperAdmin = ($user->tipoUsuario_id == 1);

        /*
        |--------------------------------------------------------------------------
        | CONSULTA PRINCIPAL
        |--------------------------------------------------------------------------
        */
        $query = DB::table('movimientos_tramiteexterno as m')
            ->join('tramite_externo as e', 'e.id', '=', 'm.expediente_id')

            /* ORIGEN: Dominio y Usuario */
            ->leftJoin('dominio as do', 'do.id', '=', 'm.dominio_origen_id')
            ->leftJoin('usuario as uo', 'uo.id', '=', 'm.usuario_origen_id')

            /* DESTINO: Dominio y Usuario */
            ->leftJoin('dominio as dd', 'dd.id', '=', 'm.dominio_destino_id')
            ->leftJoin('usuario as ud', 'ud.id', '=', 'm.usuario_destino_id')

            ->select(
                'm.id as movimiento_id',
                'm.expediente_id',

                // ORIGEN
                'm.dominio_origen_id',
                'do.domi_nombre as dominio_origen',
                'm.usuario_origen_id',
                'uo.nombre_completo as usuario_origen',

                // DESTINO
                'm.dominio_destino_id',
                'dd.domi_nombre as dominio_destino',
                'm.usuario_destino_id',
                'ud.nombre_completo as usuario_destino',

                // EXPEDIENTE
                'e.numero_expediente',
                'e.numero_documento',
                'e.asunto',
                'e.descripcion',
                'e.numero_folio',
                'e.tipo_tramite',

                // MOVIMIENTO
                'm.tipo_movimiento',
                'm.estado',
                'm.fecha_movimiento'
            );

        /*
        |--------------------------------------------------------------------------
        | FILTRADO POR BANDEJAS
        |--------------------------------------------------------------------------
        */
        switch ($tipo) {
            case 'por_recibir':
                $query->where('m.estado', 'POR_RECIBIR');
                if (!$isSuperAdmin) {
                    $query->where(function ($q) use ($userId, $userDominioId) {
                        $q->where('m.usuario_destino_id', $userId);
                        if ($userDominioId) {
                            $q->orWhere('m.dominio_destino_id', $userDominioId);
                        }
                    });
                }
                break;

            case 'recibidos':
                $query->where('m.estado', 'RECIBIDO');
                if (!$isSuperAdmin) {
                    $query->where(function ($q) use ($userId, $userDominioId) {
                        $q->where('m.usuario_destino_id', $userId);
                        if ($userDominioId) {
                            $q->orWhere('m.dominio_destino_id', $userDominioId);
                        }
                    });
                }
                break;

            case 'en_atencion':
                $query->where('m.estado', 'EN_ATENCION');
                if (!$isSuperAdmin) {
                    $query->where(function ($q) use ($userId, $userDominioId) {
                        $q->where('m.usuario_destino_id', $userId);
                        if ($userDominioId) {
                            $q->orWhere('m.dominio_destino_id', $userDominioId);
                        }
                    });
                }
                break;

            case 'derivados':
                $query->where('m.tipo_movimiento', 'DERIVACION');
                if (!$isSuperAdmin) {
                    $query->where(function ($q) use ($userId, $userDominioId) {
                        $q->where('m.usuario_origen_id', $userId);
                        if ($userDominioId) {
                            $q->orWhere('m.dominio_origen_id', $userDominioId);
                        }
                    });
                }
                break;

            case 'atendidos':
                $query->where('m.estado', 'ATENDIDO');
                if (!$isSuperAdmin) {
                    $query->where(function ($q) use ($userId, $userDominioId) {
                        $q->where('m.usuario_destino_id', $userId);
                        if ($userDominioId) {
                            $q->orWhere('m.dominio_destino_id', $userDominioId);
                        }
                    });
                }
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Bandeja no válida.'
                ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | BUSCADOR
        |--------------------------------------------------------------------------
        */
        if ($buscar !== '') {
            $query->where(function ($q) use ($buscar) {
                $q->where('e.numero_expediente', 'like', "%{$buscar}%")
                    ->orWhere('e.numero_documento', 'like', "%{$buscar}%")
                    ->orWhere('e.asunto', 'like', "%{$buscar}%")
                    ->orWhere('e.descripcion', 'like', "%{$buscar}%")
                    ->orWhere('do.domi_nombre', 'like', "%{$buscar}%")
                    ->orWhere('dd.domi_nombre', 'like', "%{$buscar}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ORDENAMIENTO + PAGINACIÓN
        |--------------------------------------------------------------------------
        */
        $documentos = $query->orderByDesc('m.id')->paginate($perPage);

        return response()->json([
            'success'    => true,
            'tipo'       => $tipo,
            'documentos' => $documentos->items(),
            'pagination' => [
                'current_page' => $documentos->currentPage(),
                'last_page'    => $documentos->lastPage(),
                'per_page'     => $documentos->perPage(),
                'total'        => $documentos->total(),
                'from'         => $documentos->firstItem(),
                'to'           => $documentos->lastItem(),
            ]
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
