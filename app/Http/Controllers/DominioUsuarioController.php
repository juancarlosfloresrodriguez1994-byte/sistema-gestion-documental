<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Dominio;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DominioUsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dominio-usuario.index')->only('index');
    }

    /**
     * Vista principal del módulo Dominio Usuario.
     */
    public function index()
    {
        $totalUsuarios  = Usuario::where('estado', 1)->count();
        $conDominio     = Usuario::where('estado', 1)->whereNotNull('dominio_id')->count();
        $sinDominio     = Usuario::where('estado', 1)->whereNull('dominio_id')->count();
        $totalDominios  = Dominio::count();

        // Lista de usuarios activos para el selector del modal
        $usuarios = Usuario::where('estado', 1)
            ->orderBy('nombre_completo')
            ->get(['id', 'nombre_completo', 'dni', 'tipoUsuario_id', 'dominio_id', 'cargo']);

        return view('seguridad.dominioUsuario.index', compact(
            'totalUsuarios',
            'conDominio',
            'sinDominio',
            'totalDominios',
            'usuarios'
        ));
    }

    /**
     * API DataTables para listar usuarios con su Dominio asignado.
     */
    public function apiDominioUsuarios(Request $request)
    {
        $draw    = (int) $request->input('draw', 1);
        $start   = max((int) $request->input('start', 0), 0);
        $length  = (int) $request->input('length', 10);
        $search  = trim((string) $request->input('search.value', ''));

        $query = DB::table('usuario as u')
            ->leftJoin('tipo_usuario as tu', 'tu.id', '=', 'u.tipoUsuario_id')
            ->leftJoin('dominio as d', 'd.id', '=', 'u.dominio_id')
            ->select(
                'u.id as usuario_id',
                'u.dni',
                'u.nombres',
                'u.apellidos',
                'u.nombre_completo',
                'u.correo',
                'u.nickname',
                'u.estado',
                'u.cargo',
                'tu.descripcion as tipo_usuario',
                'u.dominio_id',
                'd.domi_tipo',
                'd.domi_nombre as dominio_nombre',
                'd.domi_idarea_str',
                'd.domi_idequipo',
                'd.domi_idequipo_str'
            );

        $recordsTotal = DB::table('usuario')->count();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.nombre_completo', 'like', "%{$search}%")
                    ->orWhere('u.dni', 'like', "%{$search}%")
                    ->orWhere('u.correo', 'like', "%{$search}%")
                    ->orWhere('u.cargo', 'like', "%{$search}%")
                    ->orWhere('tu.descripcion', 'like', "%{$search}%")
                    ->orWhere('d.domi_nombre', 'like', "%{$search}%")
                    ->orWhere('d.domi_tipo', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $data = $query->orderBy('u.nombre_completo', 'asc')
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    /**
     * Asignar o actualizar el dominio y cargo de un usuario.
     */
    public function asignar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:usuario,id',
            'dominio_id' => 'required|exists:dominio,id',
            'cargo'      => 'nullable|string|max:150',
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario.',
            'usuario_id.exists'   => 'El usuario seleccionado no existe.',
            'dominio_id.required' => 'Debe seleccionar un dominio institucional.',
            'dominio_id.exists'   => 'El dominio seleccionado no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $cargo = $request->filled('cargo') ? strtoupper(trim($request->cargo)) : null;

            Usuario::where('id', $request->usuario_id)->update([
                'dominio_id' => $request->dominio_id,
                'cargo'      => $cargo,
            ]);

            $dominio = Dominio::find($request->dominio_id);
            $usuario = Usuario::find($request->usuario_id);

            return response()->json([
                'success' => true,
                'message' => "Se asignó exitosamente a {$usuario->nombre_completo} al dominio {$dominio->domi_nombre}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar el dominio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desvincular el dominio de un usuario.
     */
    public function desvincular($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->update([
                'dominio_id' => null,
                'cargo'      => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Se desvinculó el dominio del usuario {$usuario->nombre_completo}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desvincular: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Autocompletado Select2 para buscar dominios con su jerarquía completa.
     */
    public function filtroDominio(Request $request)
    {
        $term = trim($request->term ?? '');

        $elementos = DB::table('dominio as d')
            ->select(
                'd.id',
                'd.domi_tipo as tipo',
                'd.domi_nombre as nombre',
                'd.domi_idarea_str as area_padre',
                DB::raw("
                    CASE d.domi_tipo
                        WHEN 'AREA' THEN
                            CONCAT('[ÁREA] ', d.domi_nombre)
                        WHEN 'EQUIPO' THEN
                            CONCAT('[EQUIPO] ', d.domi_nombre, ' (Área: ', d.domi_idarea_str, ')')
                        WHEN 'SUBEQUIPO' THEN
                            CONCAT('[SUBEQUIPO] ', d.domi_nombre, ' (Área: ', d.domi_idarea_str, ')')
                        ELSE d.domi_nombre
                    END AS text
                ")
            )
            ->where(function ($q) use ($term) {
                $q->where('d.domi_nombre', 'like', "%{$term}%")
                    ->orWhere('d.domi_idarea_str', 'like', "%{$term}%");
            })
            ->whereIn('d.domi_tipo', ['AREA', 'EQUIPO', 'SUBEQUIPO'])
            ->orderByRaw("FIELD(d.domi_tipo, 'AREA', 'EQUIPO', 'SUBEQUIPO'), d.domi_nombre")
            ->limit(20)
            ->get();

        return response()->json($elementos);
    }
}
