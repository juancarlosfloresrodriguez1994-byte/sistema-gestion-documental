<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\TipoUsuario;
use App\Models\PermisosTipoUsuario;
use App\Models\Menu;
use App\Traits\MenuTrait;

class TipoUsuarioController extends Controller
{
    use MenuTrait;

    public function __construct(Request $request)
    {
        $this->middleware('can:tipo-usuario.index')->only('index');
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuAll = $this->crearMenu();

        $opciones = [
            'titlePage' => 'Tipos de Usuario',
            'menuAll'   => $menuAll,
        ];

        return view('seguridad.tipoUsuarios.index', $opciones);
    }

    /**
     * API para DataTable server-side de tipos de usuario.
     */
    public function apiTipoUsuarios(Request $request)
    {
        $draw   = (int) $request->input('draw', 1);
        $start  = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $order  = $request->input('order', []);
        $search = trim((string) $request->input('search.value', ''));

        $length = $length === -1
            ? 1000
            : max(1, min($length, 100));

        $columnasOrdenables = [
            1 => 'tipo_usuario.id',
            2 => 'tipo_usuario.descripcion',
        ];

        $query = TipoUsuario::query()
            ->select([
                'tipo_usuario.id',
                'tipo_usuario.descripcion',
            ]);

        // Total de registros sin filtros
        $recordsTotal = TipoUsuario::count();

        // Búsqueda
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('tipo_usuario.descripcion', 'LIKE', "%{$search}%");

                if (ctype_digit($search)) {
                    $q->orWhere('tipo_usuario.id', (int) $search);
                }
            });
        }

        // Total con filtros
        $recordsFiltered = (clone $query)->count('tipo_usuario.id');

        // Ordenamiento
        $ordenAplicado = false;

        if (!empty($order)) {
            foreach ($order as $ord) {
                $colIdx = (int) data_get($ord, 'column', 1);
                $dir = data_get($ord, 'dir', 'asc') === 'desc' ? 'desc' : 'asc';

                if (isset($columnasOrdenables[$colIdx])) {
                    $query->orderBy($columnasOrdenables[$colIdx], $dir);
                    $ordenAplicado = true;
                }
            }
        }

        if (!$ordenAplicado) {
            $query->orderByDesc('tipo_usuario.id');
        }

        // Paginación
        $tiposUsuario = $query
            ->skip($start)
            ->take($length)
            ->get();

        $data = $tiposUsuario->map(function ($row) {
            $botones = '';

            $botones .= '
                <a href="' . route('tipo-usuario.edit', $row->id) . '"
                   class="btn btn-sm btn-icon btn-light-warning btn-active-warning
                          w-25px h-25px"
                   title="Editar accesos">
                    <i class="ki-outline ki-setting-2 fs-5"></i>
                </a>
            ';

            return [
                'id'          => $row->id,
                'descripcion' => $row->descripcion,
                'acciones'    => $botones,
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
            'descripcion' => ['required', 'string', 'max:255', 'unique:tipo_usuario,descripcion'],
        ];

        $messages = [
            'descripcion.required' => 'El campo <strong class="text-uppercase">Descripción</strong> es obligatorio.',
            'descripcion.unique'   => 'El tipo de usuario <strong class="text-uppercase">ya existe</strong>.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores'   => $validator->errors()
            ]);
        }

        // Recopilar accesos (menu_ids)
        $accesos = $request->input('accesos', []);
        $accesosCSV = implode(',', array_filter($accesos));

        // Crear tipo usuario
        $tipoUsuario = TipoUsuario::create([
            'descripcion' => $request->descripcion,
            'accesos'     => $accesosCSV,
        ]);

        // Guardar permisos del tipo usuario
        $permisos = $request->input('permisos', []);

        foreach ($permisos as $permiso) {
            PermisosTipoUsuario::create([
                'permiso'        => $permiso,
                'tipoUsuario_id' => $tipoUsuario->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'ruta'    => route('tipo-usuario.index'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $elemento = TipoUsuario::findOrFail($id);

        $menuAll = $this->crearMenu();

        $opciones = [
            'titlePage' => 'Editar Tipo de Usuario',
            'elemento'  => $elemento,
            'menuAll'   => $menuAll,
            'otherLink' => [
                'name' => 'Tipos de Usuario',
                'link' => 'tipo-usuario'
            ]
        ];

        return view('seguridad.tipoUsuarios.editar', $opciones);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tipoUsuario = TipoUsuario::findOrFail($id);

        $input = $request->all();

        $rules = [
            'descripcion' => ['required', 'string', 'max:255', 'unique:tipo_usuario,descripcion,' . $id],
        ];

        $messages = [
            'descripcion.required' => 'El campo <strong class="text-uppercase">Descripción</strong> es obligatorio.',
            'descripcion.unique'   => 'El tipo de usuario <strong class="text-uppercase">ya existe</strong>.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores'   => $validator->errors()
            ]);
        }

        // 1. Obtener accesos y permisos ANTERIORES
        $accesosAnteriores = explode(',', $tipoUsuario->accesos);
        $permisosAnteriores = PermisosTipoUsuario::where('tipoUsuario_id', $id)->pluck('permiso')->toArray();

        // 2. Nuevos accesos y permisos desde el form
        $accesosNuevos = $request->input('accesos', []);
        $accesosCSV = implode(',', array_filter($accesosNuevos));
        $permisosNuevos = $request->input('permisos', []);

        // 3. Actualizar TipoUsuario
        $tipoUsuario->update([
            'descripcion' => $request->descripcion,
            'accesos'     => $accesosCSV,
        ]);

        // 4. Actualizar tabla PermisosTipoUsuario
        PermisosTipoUsuario::where('tipoUsuario_id', $id)->delete();
        foreach ($permisosNuevos as $permiso) {
            PermisosTipoUsuario::create([
                'permiso'        => $permiso,
                'tipoUsuario_id' => $tipoUsuario->id,
            ]);
        }

        // ==========================================
        // 5. SINCRONIZACIÓN INTELIGENTE A USUARIOS
        // ==========================================
        // Para no borrar permisos exclusivos, calculamos qué se agregó y qué se quitó
        $accesosAgregados = array_diff($accesosNuevos, $accesosAnteriores);
        $accesosQuitados  = array_diff($accesosAnteriores, $accesosNuevos);

        $permisosAgregados = array_diff($permisosNuevos, $permisosAnteriores);
        $permisosQuitados  = array_diff($permisosAnteriores, $permisosNuevos);

        $usuarios = \App\Models\Usuario::where('tipoUsuario_id', $id)->get();

        foreach ($usuarios as $user) {
            // --- Sincronizar Accesos (Menús) ---
            // Quitar los que se desmarcaron
            if (!empty($accesosQuitados)) {
                \Illuminate\Support\Facades\DB::table('privilegios')
                    ->where('usuario_id', $user->id)
                    ->whereIn('menu_id', $accesosQuitados)
                    ->delete();
            }
            // Agregar los nuevos
            foreach ($accesosAgregados as $acc) {
                \Illuminate\Support\Facades\DB::table('privilegios')->updateOrInsert([
                    'usuario_id' => $user->id,
                    'menu_id'    => $acc
                ]);
            }

            // --- Sincronizar Permisos (Spatie) ---
            // Quitar los que se desmarcaron
            foreach ($permisosQuitados as $perm) {
                if ($user->hasPermissionTo($perm)) {
                    $user->revokePermissionTo($perm);
                }
            }
            // Agregar los nuevos
            foreach ($permisosAgregados as $perm) {
                $permiso_registrado = \Spatie\Permission\Models\Permission::where('name', $perm)->first();
                if (!$permiso_registrado) {
                    \Spatie\Permission\Models\Permission::create(['name' => $perm]);
                }
                if (!$user->hasPermissionTo($perm)) {
                    $user->givePermissionTo($perm);
                }
            }
        }

        return response()->json([
            'success' => true,
            'ruta'    => route('tipo-usuario.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
