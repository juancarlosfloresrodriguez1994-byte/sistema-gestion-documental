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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Procedimientos;
use Illuminate\Support\Facades\DB;

class TramiteExternoController extends Controller
{
    public function __construct(Request $request)
    {
        
        $this->middleware('can:tramite-externo.index')->only('index');
        /*
        $this->middleware('can:usuarios.create')->only('usuarios');
        $this->middleware('can:usuarios.edit')->only('edit');
        $this->middleware('can:usuarios.destroy')->only('destroy');
        */
        $this->middleware('auth');
    }

    public function index()
    {

        $tipos = TipoDocumento::orderBy('descripcion')
            ->get();

        $area = Area::orderBy('nombre_area')
            ->get();

        $procedimientos = Procedimientos::orderBy('prod_descripcion')
            ->get();

        $opciones = [
            // 'placeholder' => 'Tramite Externo',
            // 'titlePage' => 'Tramite Externo',
            'tipos' => $tipos,
            'area' => $area,
            'procedimientos' => $procedimientos,
        ];

        return view('mesaPartes.index',  $opciones);
    }

    public function filterEquipo(Request $request)
    {


        $equipo = Equipo::where('are_id', $request->area_id)
            ->get();


        $usuario = Usuario::where('area_id', $request->area_id)
            ->get();


        return response()->json([
            'equipo' => $equipo,
            'usuario' => $usuario,
        ]);
    }

    public function filtersubEquipo(Request $request)
    {

        $SubEquipo = SubEquipo::where('equipo_id', $request->equipo_id)
            ->get();



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
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $rules = [
            'solicitante_id' => ['required'],
            'tipoDocumento_id' => ['required'],
            'numero_documento' => ['required'],
            'numero_folio' => ['required'],
            'area_id' => ['required'],
            'descripcion' => ['required'],
            'asunto' => ['required'],
        ];

        $messages = [
            'solicitante_id.required' => 'El campo <strong class="text-uppercase">Solicitante</strong> es obligatorio.',
            'tipoDocumento_id.required' => 'El campo <strong class="text-uppercase">Documento</strong> es obligatorio.',
            'numero_documento.required' => 'El campo <strong class="text-uppercase">N° Documento</strong> es obligatorio.',
            'numero_folio.required' => 'El campo <strong class="text-uppercase">N° Folio</strong> es obligatorio.',
            'descripcion.required' => 'El campo <strong class="text-uppercase">Descripcion</strong> es obligatorio.',
            'asunto.required' => 'El campo <strong class="text-uppercase">Asunto</strong> es obligatorio.',
            'area_id.required' => 'El campo <strong class="text-uppercase">Area</strong> es obligatorio.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores' => $validator->errors()
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

            $numeroExpediente =
                'EXP-' .
                $anio .
                '-' .
                str_pad($correlativo, 6, '0', STR_PAD_LEFT);




            $usuario = Auth::user();




            $usuarioDestino = null;

            // 1. Si seleccionó Sub Equipo
            if ($request->filled('subequipo_id')) {

                $usuarioDestino = Usuario::where('area_id', $request->area_id)
                    ->where('equipo_id', $request->equipo_id)
                    ->where('sub_equipo_id', $request->sub_equipo_id)
                    ->where('estado', 1)
                    ->first();
            }
            // 2. Si seleccionó Equipo pero NO Sub Equipo
            elseif ($request->filled('equipo_id')) {

                $usuarioDestino = Usuario::where('area_id', $request->area_id)
                    ->where('equipo_id', $request->equipo_id)
                    ->whereNull('sub_equipo_id')
                    ->where('estado', 1)
                    ->first();
            }
            // 3. Si solamente seleccionó Área
            elseif ($request->filled('area_id')) {

                $usuarioDestino = Usuario::where('area_id', $request->area_id)
                    ->whereNull('equipo_id')
                    ->whereNull('sub_equipo_id')
                    ->where('estado', 1)
                    ->first();
            }

            $expediente = Expediente::create([

                'numero_expediente' => $numeroExpediente,

                'anio' => $anio,

                'mes' => $mes,
                'numero_mes' => $numero_mes,

                'tipo_tramite' => $request->tipo_tramite,

                'solicitante_id' => $request->solicitante_id,

                'numero_folio' => $request->numero_folio,

                'tipoDocumento_id' =>
                $request->tipoDocumento_id,

                'numero_documento' =>
                $request->numero_documento,

                //'fecha_documento' =>$request->fecha_documento,

                'descripcion' =>
                $request->descripcion,

                'asunto' =>
                $request->asunto,

                'numero_folios' =>
                $request->numero_folios,

                // 'prioridad' =>$request->prioridad ?? 'NORMAL',

                /*
             * Ubicación actual
             */

                'area_actual_id' =>
                $request->area_id,

                'equipo_actual_id' =>
                $request->equipo_id,

                'sub_equipo_actual_id' =>
                $request->sub_equipo_id,

                'usuario_actual_id' => $usuarioDestino->id,

                'estado' =>
                'POR_RECIBIR',

                'usuario_registro_id' =>
                $usuario->id,

                'fecha_registro' => now(),
            ]);


            /*
        |--------------------------------------------------------------------------
        | Registrar primer movimiento
        |--------------------------------------------------------------------------
        */

            ExpedienteMovimiento::create([

                'expediente_id' =>
                $expediente->id,

                /*
             * Origen
             */

                'area_origen_id' =>
                $usuario->area_id,

                'equipo_origen_id' =>
                $usuario->equipo_id,

                'sub_equipo_origen_id' =>
                $usuario->sub_equipo_id,

                'usuario_origen_id' =>
                $usuario->id,

                /*
             * Destino
             */

                'area_destino_id' =>
                $request->area_id,

                'equipo_destino_id' =>
                $request->equipo_id,

                'sub_equipo_destino_id' =>
                $request->subequipo_id,

                'usuario_destino_id' =>
                $usuarioDestino->id,

                'tipo_movimiento' =>
                'REGISTRO',

                'estado' =>
                'POR_RECIBIR',

                //'proveido' => $request->proveido,

                'fecha_movimiento' =>
                now(),

                'usuario_accion_id' =>
                $usuario->id,
            ]);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Expediente registrado correctamente.',
                'expediente_id' =>
                $expediente->id,
                'numero_expediente' =>
                $expediente->numero_expediente,
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' =>
                'No se pudo registrar el expediente.',
                'error' => $e->getMessage(),
            ], 500);
        }



        return response()->json([
            'ruta' => route('tramite-externo.index')
        ]);
    }





    public function apiTramiteExterno(Request $request)
    {
        $tipo = $request->input(
            'tipo',
            'POR_RECIBIR'
        );

        $buscar = trim(
            $request->input('buscar', '')
        );

        $perPage = 1;


        /*
    |--------------------------------------------------------------------------
    | CONSULTA
    |--------------------------------------------------------------------------
    */

        $query = DB::table('movimientos_tramiteexterno as m')

            ->join(
                'tramite_externo as e',
                'e.id',
                '=',
                'm.expediente_id'
            )


            /*
        |--------------------------------------------------------------------------
        | ORIGEN
        |--------------------------------------------------------------------------
        */

            ->leftJoin(
                'area as ao',
                'ao.id',
                '=',
                'm.area_origen_id'
            )

            ->leftJoin(
                'equipo as eo',
                'eo.id',
                '=',
                'm.equipo_origen_id'
            )

            ->leftJoin(
                'subequipo as seo',
                'seo.id',
                '=',
                'm.sub_equipo_origen_id'
            )

            ->leftJoin(
                'usuario as uo',
                'uo.id',
                '=',
                'm.usuario_origen_id'
            )


            /*
        |--------------------------------------------------------------------------
        | DESTINO
        |--------------------------------------------------------------------------
        */

            ->leftJoin(
                'area as ad',
                'ad.id',
                '=',
                'm.area_destino_id'
            )

            ->leftJoin(
                'equipo as ed',
                'ed.id',
                '=',
                'm.equipo_destino_id'
            )

            ->leftJoin(
                'subequipo as sed',
                'sed.id',
                '=',
                'm.sub_equipo_destino_id'
            )

            ->leftJoin(
                'usuario as ud',
                'ud.id',
                '=',
                'm.usuario_destino_id'
            )


            /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        */

            ->select(

                'm.id as movimiento_id',
                'm.expediente_id',

                // ORIGEN
                'm.area_origen_id',
                'm.equipo_origen_id',
                'm.sub_equipo_origen_id',
                'm.usuario_origen_id',

                'ao.nombre_area as area_origen',
                'eo.nombre_equipo as equipo_origen',
                'seo.nombre_subequipo as sub_equipo_origen',
                'uo.nombre_completo as usuario_origen',

                // DESTINO
                'm.area_destino_id',
                'm.equipo_destino_id',
                'm.sub_equipo_destino_id',
                'm.usuario_destino_id',

                'ad.nombre_area as area_destino',
                'ed.nombre_equipo as equipo_destino',
                'sed.nombre_subequipo as sub_equipo_destino',
                'ud.nombre_completo as usuario_destino',

                // EXPEDIENTE
                'e.numero_expediente',
                'e.numero_documento',
                'e.descripcion',
                'e.numero_folio',
                //'e.prioridad',

                // MOVIMIENTO
                'm.tipo_movimiento',
                'm.estado',
                'm.fecha_movimiento'
            )->where(
                'e.usuario_actual_id',
                Auth::id()
            );

        /*
    |--------------------------------------------------------------------------
    | BANDEJAS
    |--------------------------------------------------------------------------
    */

        switch ($tipo) {


            /*
        |--------------------------------------------------------------------------
        | POR RECIBIR
        |--------------------------------------------------------------------------
        */

            case 'por_recibir':

                $query
                    ->where(
                        'm.usuario_destino_id',
                        Auth::id()
                    )
                    ->where(
                        'm.estado',
                        'POR_RECIBIR'
                    );

                break;


            /*
        |--------------------------------------------------------------------------
        | RECIBIDOS
        |--------------------------------------------------------------------------
        */

            case 'recibidos':

                $query
                    ->where(
                        'm.usuario_destino_id',
                        Auth::id()
                    )
                    ->where(
                        'm.estado',
                        'RECIBIDO'
                    );

                break;


            /*
        |--------------------------------------------------------------------------
        | EN ATENCION
        |--------------------------------------------------------------------------
        */

            case 'en_atencion':

                $query
                    ->where(
                        'm.usuario_destino_id',
                        Auth::id()
                    )
                    ->where(
                        'm.estado',
                        'EN_ATENCION'
                    );

                break;


            /*
        |--------------------------------------------------------------------------
        | DERIVADOS
        |--------------------------------------------------------------------------
        */

            case 'derivados':

                $query
                    ->where(
                        'm.usuario_origen_id',
                        Auth::id()
                    )
                    ->where(
                        'm.tipo_movimiento',
                        'DERIVACION'
                    );

                break;


            /*
        |--------------------------------------------------------------------------
        | ATENDIDOS
        |--------------------------------------------------------------------------
        */

            case 'atendidos':

                $query
                    ->where(
                        'm.usuario_destino_id',
                        Auth::id()
                    )
                    ->where(
                        'm.estado',
                        'ATENDIDO'
                    );

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

                $q->where(
                    'e.numero_expediente',
                    'like',
                    "%{$buscar}%"
                )

                    ->orWhere(
                        'e.numero_documento',
                        'like',
                        "%{$buscar}%"
                    )

                    ->orWhere(
                        'e.descripcion',
                        'like',
                        "%{$buscar}%"
                    )

                    ->orWhere(
                        'ao.nombre_area',
                        'like',
                        "%{$buscar}%"
                    )

                    ->orWhere(
                        'ad.nombre_area',
                        'like',
                        "%{$buscar}%"
                    );
            });
        }


        /*
    |--------------------------------------------------------------------------
    | ORDENAMIENTO + PAGINACION
    |--------------------------------------------------------------------------
    */

        $documentos = $query

            ->orderByDesc('m.id')

            ->paginate($perPage);


        /*
    |--------------------------------------------------------------------------
    | RESPUESTA
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,

            'tipo' => $tipo,

            'documentos' => $documentos->items(),

            'pagination' => [

                'current_page' =>
                $documentos->currentPage(),

                'last_page' =>
                $documentos->lastPage(),

                'per_page' =>
                $documentos->perPage(),

                'total' =>
                $documentos->total(),

                'from' =>
                $documentos->firstItem(),

                'to' =>
                $documentos->lastItem()

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
