<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Dominio;
use App\Models\Equipo;
use App\Models\SubEquipo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DominioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dominio.index')->only('index');
    }

    /**
     * Vista principal del Módulo de Gestión de Dominios.
     */
    public function index()
    {
        // KPIs institucionales
        $totalDominios   = Dominio::whereIn('domi_tipo', ['AREA', 'EQUIPO', 'SUBEQUIPO'])->count();
        $totalAreas      = Dominio::where('domi_tipo', 'AREA')->count();
        $totalEquipos    = Dominio::where('domi_tipo', 'EQUIPO')->count();
        $totalSubequipos = Dominio::where('domi_tipo', 'SUBEQUIPO')->count();
        $totalCas        = Dominio::where('domi_tipo', 'CONVOCATORIA')->count();
        $totalExterno    = Dominio::where('domi_tipo', 'EXTERNO')->count();

        // Lista de Áreas para los selects modales
        $areas = Dominio::where('domi_tipo', 'AREA')
            ->orderBy('domi_nombre')
            ->get(['id', 'domi_nombre', 'domi_siglas', 'domi_idarea']);

        return view('seguridad.dominio.index', compact(
            'totalDominios',
            'totalAreas',
            'totalEquipos',
            'totalSubequipos',
            'totalCas',
            'totalExterno',
            'areas'
        ));
    }

    /**
     * API DataTables server-side para listar dominios institucionales.
     */
    public function apiDominios(Request $request)
    {
        $draw    = (int) $request->input('draw', 1);
        $start   = max((int) $request->input('start', 0), 0);
        $length  = (int) $request->input('length', 10);
        $search  = trim((string) $request->input('search.value', ''));
        $tipoTab = trim((string) $request->input('tipo_filtro', ''));

        $length = $length === -1 ? 1000 : max(1, min($length, 100));

        $query = DB::table('dominio as d')
            ->select([
                'd.id',
                'd.domi_tipo',
                'd.domi_nombre',
                'd.domi_siglas',
                'd.domi_descripcion',
                'd.domi_responsable',
                'd.domi_idarea',
                'd.domi_idarea_str',
                'd.domi_idequipo',
                'd.domi_idequipo_str',
                'd.domi_idsubequipo',
                'd.domi_idpadre',
                DB::raw('(SELECT COUNT(*) FROM usuario u WHERE u.dominio_id = d.id AND u.estado = 1) AS usuarios_count'),
                DB::raw('(SELECT COUNT(*) FROM dominio h WHERE h.domi_idpadre = d.id) AS dependencias_count'),
            ]);

        // Filtro por pestaña de tipo/nivel
        if (!empty($tipoTab) && $tipoTab !== 'ALL') {
            $query->where('d.domi_tipo', $tipoTab);
        }

        // Búsqueda en varios campos
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('d.domi_nombre', 'like', "%{$search}%")
                    ->orWhere('d.domi_siglas', 'like', "%{$search}%")
                    ->orWhere('d.domi_responsable', 'like', "%{$search}%")
                    ->orWhere('d.domi_idarea_str', 'like', "%{$search}%")
                    ->orWhere('d.domi_idequipo_str', 'like', "%{$search}%")
                    ->orWhere('d.domi_tipo', 'like', "%{$search}%");
            });
        }

        $recordsTotal = DB::table('dominio')->count();
        $recordsFiltered = (clone $query)->count();

        $data = $query->orderByRaw("FIELD(d.domi_tipo, 'AREA', 'EQUIPO', 'SUBEQUIPO', 'EXTERNO', 'CONVOCATORIA', 'INACTIVO'), d.domi_nombre ASC")
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
     * Devuelve las Áreas o los Equipos pertenecientes a un Área para selects en cascada.
     */
    public function opcionesPadre(Request $request)
    {
        $nivel   = $request->input('nivel');
        $areaId  = $request->input('area_id');

        if ($nivel === 'EQUIPOS_DE_AREA' && $areaId) {
            $equipos = Dominio::where('domi_tipo', 'EQUIPO')
                ->where('domi_idpadre', $areaId)
                ->orderBy('domi_nombre')
                ->get(['id', 'domi_nombre', 'domi_siglas', 'domi_idequipo']);

            return response()->json($equipos);
        }

        // Por defecto, retorna todas las Áreas
        $areas = Dominio::where('domi_tipo', 'AREA')
            ->orderBy('domi_nombre')
            ->get(['id', 'domi_nombre', 'domi_siglas', 'domi_idarea']);

        return response()->json($areas);
    }

    /**
     * Registrar un nuevo dominio y sincronizar su tabla física (Enfoque A).
     */
    public function store(Request $request)
    {
        $request->validate([
            'domi_tipo'        => 'required|in:AREA,EQUIPO,SUBEQUIPO',
            'domi_nombre'      => 'required|string|max:255',
            'domi_siglas'      => 'nullable|string|max:50',
            'domi_descripcion' => 'nullable|string',
            'domi_responsable' => 'nullable|string|max:255',
            'area_padre_id'    => 'required_if:domi_tipo,EQUIPO,SUBEQUIPO|nullable|exists:dominio,id',
            'equipo_padre_id'  => 'required_if:domi_tipo,SUBEQUIPO|nullable|exists:dominio,id',
        ], [
            'domi_tipo.required'        => 'El tipo/nivel de dominio es obligatorio.',
            'domi_nombre.required'      => 'El nombre de la dependencia es obligatorio.',
            'area_padre_id.required_if' => 'Debe seleccionar el Área a la que pertenece.',
            'equipo_padre_id.required_if' => 'Debe seleccionar el Equipo al que pertenece.',
        ]);

        try {
            DB::beginTransaction();

            $tipo        = $request->domi_tipo;
            $nombre      = mb_strtoupper(trim($request->domi_nombre), 'UTF-8');
            $siglas      = $request->domi_siglas ? mb_strtoupper(trim($request->domi_siglas), 'UTF-8') : null;
            $descripcion = trim($request->domi_descripcion ?? '');
            $responsable = trim($request->domi_responsable ?? '');

            if ($tipo === 'AREA') {
                // 1. Crear en tabla `area`
                $area = Area::create([
                    'nombre_area' => $nombre,
                    'siglas'      => $siglas,
                    'descripcion' => $descripcion,
                    'responsable' => $responsable,
                    'estado'      => 1,
                ]);

                // 2. Crear en tabla `dominio`
                $dominio = Dominio::create([
                    'domi_idpadre'      => 0,
                    'domi_tipo'         => 'AREA',
                    'domi_nombre'       => $nombre,
                    'domi_siglas'       => $siglas ?? '',
                    'domi_descripcion'  => $descripcion ?: 'Órgano de Nivel 1 (Área)',
                    'domi_responsable'  => $responsable,
                    'domi_idarea'       => $area->id,
                    'domi_idarea_str'   => $nombre,
                    'domi_idequipo'     => 0,
                    'domi_idequipo_str' => '',
                    'domi_idsubequipo'  => null,
                ]);
            } elseif ($tipo === 'EQUIPO') {
                $areaDominio = Dominio::findOrFail($request->area_padre_id);

                // 1. Crear en tabla `equipo`
                $equipo = Equipo::create([
                    'nombre_equipo' => $nombre,
                    'are_id'        => $areaDominio->domi_idarea ?: $areaDominio->id,
                    'siglas'        => $siglas,
                    'descripcion'   => $descripcion,
                    'responsable'   => $responsable,
                    'estado'        => 1,
                ]);

                // 2. Crear en tabla `dominio`
                $dominio = Dominio::create([
                    'domi_idpadre'      => $areaDominio->id,
                    'domi_tipo'         => 'EQUIPO',
                    'domi_nombre'       => $nombre,
                    'domi_siglas'       => $siglas ?? '',
                    'domi_descripcion'  => $descripcion ?: 'Equipo de Nivel 2 dependiente de ' . $areaDominio->domi_nombre,
                    'domi_responsable'  => $responsable,
                    'domi_idarea'       => $areaDominio->domi_idarea ?: $areaDominio->id,
                    'domi_idarea_str'   => $areaDominio->domi_nombre,
                    'domi_idequipo'     => $equipo->id,
                    'domi_idequipo_str' => $nombre,
                    'domi_idsubequipo'  => null,
                ]);
            } else { // SUBEQUIPO
                $areaDominio   = Dominio::findOrFail($request->area_padre_id);
                $equipoDominio = Dominio::findOrFail($request->equipo_padre_id);

                // 1. Crear en tabla `subequipo`
                $subequipo = SubEquipo::create([
                    'nombre_subequipo' => $nombre,
                    'equipo_id'        => $equipoDominio->domi_idequipo ?: $equipoDominio->id,
                    'siglas'           => $siglas,
                    'descripcion'      => $descripcion,
                    'responsable'      => $responsable,
                    'estado'           => 1,
                ]);

                // 2. Crear en tabla `dominio`
                $dominio = Dominio::create([
                    'domi_idpadre'      => $equipoDominio->id,
                    'domi_tipo'         => 'SUBEQUIPO',
                    'domi_nombre'       => $nombre,
                    'domi_siglas'       => $siglas ?? '',
                    'domi_descripcion'  => $descripcion ?: 'Oficina / Subequipo de Nivel 3 dependiente de ' . $equipoDominio->domi_nombre,
                    'domi_responsable'  => $responsable,
                    'domi_idarea'       => $areaDominio->domi_idarea ?: $areaDominio->id,
                    'domi_idarea_str'   => $areaDominio->domi_nombre,
                    'domi_idequipo'     => $equipoDominio->domi_idequipo ?: $equipoDominio->id,
                    'domi_idequipo_str' => $equipoDominio->domi_nombre,
                    'domi_idsubequipo'  => $subequipo->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dominio institucional "' . $nombre . '" registrado con éxito.',
                'data'    => $dominio,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar Dominio: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el dominio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener los datos de un Dominio específico para edición.
     */
    public function show($id)
    {
        $dominio = Dominio::findOrFail($id);

        $areaPadreId   = null;
        $equipoPadreId = null;

        if ($dominio->domi_tipo === 'EQUIPO') {
            $areaPadreId = $dominio->domi_idpadre;
        } elseif ($dominio->domi_tipo === 'SUBEQUIPO') {
            $equipoPadreId = $dominio->domi_idpadre;
            $equipoPadre   = Dominio::find($equipoPadreId);
            $areaPadreId   = $equipoPadre ? $equipoPadre->domi_idpadre : null;
        }

        return response()->json([
            'dominio'         => $dominio,
            'area_padre_id'   => $areaPadreId,
            'equipo_padre_id' => $equipoPadreId,
        ]);
    }

    /**
     * Actualizar los datos de un Dominio y sincronizar su tabla física.
     */
    public function update(Request $request, $id)
    {
        $dominio = Dominio::findOrFail($id);

        // Protección de IDs críticos
        if (in_array((int) $id, [6, 128]) && $request->has('domi_tipo') && $request->domi_tipo !== $dominio->domi_tipo) {
            return response()->json([
                'success' => false,
                'message' => 'No está permitido modificar el tipo de este dominio protegido por protocolo del sistema.',
            ], 422);
        }

        $request->validate([
            'domi_nombre'      => 'required|string|max:255',
            'domi_siglas'      => 'nullable|string|max:50',
            'domi_descripcion' => 'nullable|string',
            'domi_responsable' => 'nullable|string|max:255',
        ], [
            'domi_nombre.required' => 'El nombre de la dependencia es obligatorio.',
        ]);

        try {
            DB::beginTransaction();

            $nombre      = mb_strtoupper(trim($request->domi_nombre), 'UTF-8');
            $siglas      = $request->domi_siglas ? mb_strtoupper(trim($request->domi_siglas), 'UTF-8') : null;
            $descripcion = trim($request->domi_descripcion ?? '');
            $responsable = trim($request->domi_responsable ?? '');

            // Actualizar tabla dominio
            $dominio->update([
                'domi_nombre'      => $nombre,
                'domi_siglas'      => $siglas ?? '',
                'domi_descripcion' => $descripcion,
                'domi_responsable' => $responsable,
            ]);

            // Sincronizar tabla física según el tipo
            if ($dominio->domi_tipo === 'AREA') {
                if ($dominio->domi_idarea) {
                    Area::where('id', $dominio->domi_idarea)->update([
                        'nombre_area' => $nombre,
                        'siglas'      => $siglas,
                        'descripcion' => $descripcion,
                        'responsable' => $responsable,
                    ]);
                }

                // Actualizar nombres cacheados en hijos
                Dominio::where('domi_idarea', $dominio->domi_idarea)->update([
                    'domi_idarea_str' => $nombre,
                ]);
            } elseif ($dominio->domi_tipo === 'EQUIPO') {
                if ($dominio->domi_idequipo) {
                    Equipo::where('id', $dominio->domi_idequipo)->update([
                        'nombre_equipo' => $nombre,
                        'siglas'        => $siglas,
                        'descripcion'   => $descripcion,
                        'responsable'   => $responsable,
                    ]);
                }

                // Actualizar nombres cacheados en subequipos hijos
                Dominio::where('domi_idequipo', $dominio->domi_idequipo)->update([
                    'domi_idequipo_str' => $nombre,
                ]);
            } elseif ($dominio->domi_tipo === 'SUBEQUIPO') {
                if ($dominio->domi_idsubequipo) {
                    SubEquipo::where('id', $dominio->domi_idsubequipo)->update([
                        'nombre_subequipo' => $nombre,
                        'siglas'           => $siglas,
                        'descripcion'      => $descripcion,
                        'responsable'      => $responsable,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dominio "' . $nombre . '" actualizado con éxito.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar Dominio: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el dominio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un Dominio institucional con validaciones de seguridad.
     */
    public function destroy($id)
    {
        // 1. Proteger dominios clave del sistema
        if ((int) $id === 6) {
            return response()->json([
                'success' => false,
                'message' => 'El dominio [DIRECCIÓN] (ID: 6) es el despacho principal del sistema y no puede eliminarse.',
            ], 422);
        }

        if ((int) $id === 128) {
            return response()->json([
                'success' => false,
                'message' => 'El dominio [USUARIO VIRTUAL] (ID: 128) es necesario para los trámites ciudadanos externos y no puede eliminarse.',
            ], 422);
        }

        $dominio = Dominio::findOrFail($id);

        // 2. Validar que no tenga usuarios activos asignados
        $usuariosAsignados = Usuario::where('dominio_id', $id)->count();
        if ($usuariosAsignados > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar este dominio porque tiene ' . $usuariosAsignados . ' usuario(s) asignado(s). Reasigne a los usuarios antes de eliminar.',
            ], 422);
        }

        // 3. Validar que no tenga dependencias hijas
        $hijos = Dominio::where('domi_idpadre', $id)->count();
        if ($hijos > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar este dominio porque tiene ' . $hijos . ' equipo(s) o subequipo(s) dependiente(s). Elimine o reasigne primero las dependencias hijas.',
            ], 422);
        }

        // 4. Validar que no tenga trámites en movimientos
        $tramitesVinculados = DB::table('movimientos_tramiteexterno')
            ->where('dominio_destino_id', $id)
            ->count();
        if ($tramitesVinculados > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar este dominio porque cuenta con ' . $tramitesVinculados . ' expediente(s) derivados históricamente.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Eliminar de la tabla física respectiva
            if ($dominio->domi_tipo === 'AREA' && $dominio->domi_idarea) {
                Area::where('id', $dominio->domi_idarea)->delete();
            } elseif ($dominio->domi_tipo === 'EQUIPO' && $dominio->domi_idequipo) {
                Equipo::where('id', $dominio->domi_idequipo)->delete();
            } elseif ($dominio->domi_tipo === 'SUBEQUIPO' && $dominio->domi_idsubequipo) {
                SubEquipo::where('id', $dominio->domi_idsubequipo)->delete();
            }

            // Eliminar el registro en dominio
            $nombreEliminado = $dominio->domi_nombre;
            $dominio->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dominio "' . $nombreEliminado . '" eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar Dominio: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el dominio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Autocompletado Select2 para búsqueda general de dominios con jerarquía visual.
     */
    public function autocompletadoDominio(Request $request)
    {
        $term = trim($request->term ?? '');

        $elementos = DB::table('dominio as d')
            ->select(
                'd.id as id',
                DB::raw("
                    CASE d.domi_tipo
                        WHEN 'AREA' THEN
                            CONCAT('[ÁREA] ', d.domi_nombre)
                        WHEN 'EQUIPO' THEN
                            CONCAT('[EQUIPO] ', d.domi_nombre, ' (Área: ', d.domi_idarea_str, ')')
                        WHEN 'SUBEQUIPO' THEN
                            CONCAT('[SUBEQUIPO] ', d.domi_nombre, ' (Equipo: ', d.domi_idequipo_str, ' > Área: ', d.domi_idarea_str, ')')
                        ELSE d.domi_nombre
                    END AS text
                "),
                'd.domi_nombre as destino',
                'd.domi_tipo as tipo'
            )
            ->where(function ($q) use ($term) {
                $q->where('d.domi_nombre', 'like', "%{$term}%")
                    ->orWhere('d.domi_siglas', 'like', "%{$term}%")
                    ->orWhere('d.domi_idequipo_str', 'like', "%{$term}%")
                    ->orWhere('d.domi_idarea_str', 'like', "%{$term}%");
            })
            ->whereIn('d.domi_tipo', ['AREA', 'EQUIPO', 'SUBEQUIPO'])
            ->orderByRaw("FIELD(d.domi_tipo, 'AREA', 'EQUIPO', 'SUBEQUIPO'), d.domi_nombre")
            ->limit(25)
            ->get();

        return response()->json($elementos);
    }
}
