<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use App\Models\Privilegio;

use Spatie\Permission\Models\Permission;
use App\Models\Permisos;
use App\Models\PermisosTipoUsuario;

use App\Models\Menu;
use Illuminate\Validation\Rule;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Http\Requests\UsuarioRequest;

use Illuminate\Support\Arr;
use App\Traits\MenuTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Area;

use App\Models\Equipo;
use App\Models\SubEquipo;

class UsuarioController extends Controller
{
    use MenuTrait;

    public function __construct(Request $request)
    {
        /*
        $this->middleware('can:usuarios.index')->only('index');
        $this->middleware('can:usuarios.create')->only('usuarios');
        $this->middleware('can:usuarios.edit')->only('edit');
        $this->middleware('can:usuarios.destroy')->only('destroy');
        */
        $this->middleware('auth');
    }

    public function index()
    {
        $tipos = TipoUsuario::orderBy('descripcion')
            ->get();

        $area = Area::orderBy('nombre_area')
            ->get();

        $opciones = [
            'placeholder' => 'usuario',
            'titlePage' => 'Usuarios',
            'tipos' => $tipos,
            'area' => $area,
        ];

        return view('seguridad.usuarios.index',  $opciones);
    }


    public function create()
    {
        $tipos = TipoUsuario::orderBy('descripcion')
            ->get();



        $opciones = [
            'titlePage' => 'Agregar Usuarios',
            'tipos' => $tipos,

            'otherLink' => [
                'name' => 'Usuarios',
                'link' => 'usuarios'
            ]
        ];

        return view('seguridad.usuarios.create', $opciones);
    }

    public function apiUsuarios(Request $request)
    {
        $draw   = (int) $request->input('draw', 1);
        $start  = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $order  = $request->input('order', []);
        $search = trim((string) $request->input('search.value', ''));

        $length = $length === -1
            ? 1000
            : max(1, min($length, 100));

        /*
     * Columnas actuales de DataTables:
     *
     * 0 = correlativo
     * 1 = documento
     * 2 = nombre completo
     * 3 = tipo usuario
     * 4 = estado
     */
        $columnasOrdenables = [
            1 => 'usuario.dni',
            2 => 'usuario.nombre_completo',
            3 => 'tipo_usuario.descripcion',
            4 => 'usuario.estado',
        ];

        $query = Usuario::query()
            ->select([
                'usuario.id',
                'usuario.dni',
                'usuario.nombre_completo',
                'tipo_usuario.descripcion as descripcion',
                'usuario.estado',
            ])
            ->leftJoin(
                'tipo_usuario',
                'tipo_usuario.id',
                '=',
                'usuario.tipoUsuario_id'
            );

        // Total de registros sin filtros
        $recordsTotal = Usuario::count();

        // Búsqueda
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('usuario.nombre_completo', 'LIKE', "%{$search}%")
                    ->orWhere('usuario.dni', 'LIKE', "%{$search}%")
                    ->orWhere(
                        'tipo_usuario.descripcion',
                        'LIKE',
                        "%{$search}%"
                    );

                if (ctype_digit($search)) {
                    $q->orWhere('usuario.id', (int) $search);
                }
            });
        }

        // Total con filtros
        $recordsFiltered = (clone $query)
            ->count('usuario.id');

        // Ordenamiento
        $ordenAplicado = false;

        if (!empty($order)) {
            foreach ($order as $ord) {
                $colIdx = (int) data_get($ord, 'column', 1);

                $dir = data_get($ord, 'dir', 'asc') === 'desc'
                    ? 'desc'
                    : 'asc';

                if (isset($columnasOrdenables[$colIdx])) {
                    $query->orderBy(
                        $columnasOrdenables[$colIdx],
                        $dir
                    );

                    $ordenAplicado = true;
                }
            }
        }

        if (!$ordenAplicado) {
            $query->orderByDesc('usuario.id');
        }

        // Paginación
        $usuarios = $query
            ->skip($start)
            ->take($length)
            ->get();

        $data = $usuarios->map(function ($row) {
            $botones = '';

            $botones .= '
            
                <a href="#"
                   class="btn btn-sm btn-icon btn-light-success btn-active-success
                          w-25px h-25px btn_editarUsuarios"
                   data-usuario-id="' . $row->id . '"
                   data-bs-toggle="modal"
                   data-bs-target="#modal_usuariosEditar"
                   title="Editar">
                    <i class="ki-outline ki-pencil fs-5"></i>
                </a>
        ';


            $botones .= '
                <a href="#"
                   class="btn btn-sm btn-icon btn-light-primary btn-active-primary
                          w-25px h-25px btn-usuario-detalle"
                   data-usuario-id="' . $row->id . '"
                   data-bs-toggle="modal"
                   data-bs-target="#modalUsuarioDetalle"
                   title="Detalle">
                    <i class="ki-outline ki-eye fs-5"></i>
                </a>
        ';



            return [
                'id'              => $row->id,
                'documento'       => $row->dni,
                'nombre_completo' => $row->nombre_completo,
                'descripcion'     => $row->descripcion ?? 'SIN TIPO',
                'estado'          => $row->estado,
                'acciones'        => $botones,
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }



    public function store(Request $request)
    {
        $input = $request->all();

        $rules = [
            'nombres' => ['required'],
            'apellidos' => ['required'],
            'nickname' => ['required'],
            'tipoUsuario_id' => ['required'],
            'documento' => ['required'],
            'password' => ['required'],
        ];

        $messages = [
            'nombres.required' => 'El campo <strong class="text-uppercase">nombres</strong> es obligatorio.',
            'apellidos.required' => 'El campo <strong class="text-uppercase">apellidos</strong> es obligatorio.',
            'nickname.required' => 'El campo <strong class="text-uppercase">Usuario</strong> es obligatorio.',
            'tipoUsuario_id.required' => 'El campo <strong class="text-uppercase">Tipo Usuario</strong> es obligatorio.',
            'documento.required' => 'El campo <strong class="text-uppercase">documento</strong> es obligatorio.',
            'password.required' => 'El campo <strong class="text-uppercase">Contraseña</strong> es obligatorio.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores' => $validator->errors()
            ]);
        }


        $tipoUsuario = TipoUsuario::where('id', $request->tipoUsuario_id)
            ->first();

        //dd( $tipoUsuario);

        $usuario = Usuario::create([
            'nombres'   => $request->nombres,
            'apellidos' => $request->apellidos,
            'nickname'  => $request->nickname,
            'password'  => $request->password,
            'tipoUsuario_id' => $tipoUsuario->id,
            'estado' => 1,
            'dni'  => $request->documento,
            'nombre_completo'  => $request->apellidos . ' ' . $request->nombres,
            'area_id'        => $request->area_id,
            'equipo_id'      => $request->filled('equipo_id') ? $request->equipo_id : null,
            'sub_equipo_id'   => $request->filled('subequipo_id') ? $request->subequipo_id : null,
        ]);

        $this->cambiarPermisos($usuario, $tipoUsuario->id);

        return response()->json([
            'ruta' => route('usuarios.index')
        ]);
    }

    public function cambiarPermisos($usuario, $tipoUsuario_id)
    {
        $tipoUsuario = TipoUsuario::where('id', $tipoUsuario_id)
            ->first();

        $token = explode(',', $tipoUsuario->accesos);

        //dd($tipoUsuario_id);

        $arrayAccesos = [];

        foreach ($token as $acceso) {
            array_push($arrayAccesos, [
                'usuario_id' => $usuario->id,
                'menu_id' => $acceso
            ]);
        }


        if (count($arrayAccesos) >= 1) {

            DB::table('privilegios')->insert($arrayAccesos);

            $permisos = PermisosTipoUsuario::where('tipoUsuario_id', $tipoUsuario_id)
                ->get();


            foreach ($permisos as $permiso) {
                $permiso_registrado = Permission::where('name', $permiso->permiso)
                    ->first();

                if (!isset($permiso_registrado->id)) {
                    Permission::create(['name' => $permiso->permiso]);
                }

                $usuario->givePermissionTo($permiso->permiso);
            }
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $tipos = TipoUsuario::orderBy('descripcion')
            ->get();

        $usuario = Usuario::where('id', $id)
            ->first();

        $area = Area::orderBy('nombre_area')
            ->get();

        $equipo = Equipo::orderBy('nombre_equipo')
            ->get();

        $SubEquipo = SubEquipo::orderBy('nombre_subequipo')
            ->get();

        return response()->json([
            'action' => route('usuarios.estado', $id),
            'tipos' => $tipos,
            'usuario' => $usuario,
            'area' => $area,
            'equipo' => $equipo,
            'SubEquipo' => $SubEquipo,
        ]);
    }


    public function update(Request $request, $id)
    {


        $input = $request->all();

        $rules = [
            'nombres' => ['required', 'regex:/^[a-zA-ZÑñ&\s]+$/'],
            'apellidos' => ['required', 'regex:/^[a-zA-ZÑñ&\s]+$/'],
            'nickname' => ['required'],
            'tipoUsuario_id' => ['required'],
            'documento' => ['required', 'digits:8', 'numeric', Rule::unique('usuario', 'dni')->ignore($id)],
        ];

        $messages = [
            'nombres.regex' => 'Este campo Nombres <strong class="text-uppercase">es solo letras</strong>.',
            'apellidos.regex' => 'Este campo Apellidos <strong class="text-uppercase">es solo letras</strong>.',
            'documento.unique' => 'El <strong class="text-uppercase">Documento</strong> ingresado ya ha sido registrado.',
            'nombres.required' => 'El campo <strong class="text-uppercase">nombres</strong> es obligatorio.',
            'apellidos.required' => 'El campo <strong class="text-uppercase">Apellidos</strong> es obligatorio.',
            'nickname.required' => 'El campo <strong class="text-uppercase">Usuarios</strong> es obligatorio.',
            'tipoUsuario_id.required' => 'El campo <strong class="text-uppercase">Tipo Usuario</strong> es obligatorio.',
            'documento.required' => 'El campo <strong class="text-uppercase">Documento</strong> es obligatorio.',
            'documento.digits' => 'El campo <strong class="text-uppercase"> DNI solo es 8 dígitos </strong>.',
            'documento.numeric' => 'El campo <strong class="text-uppercase"> es solo numeros </strong>.',
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorForm' => true,
                'errores' => $validator->errors()
            ]);
        }

        $usuario = Usuario::where('id', $id)
            ->first();

        if (empty($request->password)) {
            Usuario::find($id)
                ->update([
                    'nombres'   => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'nickname'  => $request->nickname,
                    'tipoUsuario_id' => $request->tipoUsuario_id,
                    'dni'  => $request->documento,
                    'nombre_completo'  => $request->apellidos . ', ' . $request->nombres,
                    'area_id'        => $request->area_id,
                    'equipo_id'      => $request->filled('equipo_id') ? $request->equipo_id : null,
                    'sub_equipo_id'   => $request->filled('subequipo_id') ? $request->subequipo_id : null,
                ]);
        } else {
            Usuario::find($id)
                ->update([
                    'nombres'   => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'nickname'  => $request->nickname,
                    'password'  => $request->password,
                    'tipoUsuario_id' => $request->tipoUsuario_id,
                    'dni'  => $request->documento,
                    'nombre_completo'  => $request->apellidos . ', ' . $request->nombres,
                    'area_id'        => $request->area_id,
                    'equipo_id'      => $request->filled('equipo_id') ? $request->equipo_id : null,
                    'sub_equipo_id'   => $request->filled('subequipo_id') ? $request->subequipo_id : null,
                ]);
        }

        $tipoUsuario = TipoUsuario::where('id', $request->tipoUsuario_id)
            ->first();

        //dd($tipoUsuario->id);

        Usuario::find($id)
            ->update([
                'tipoUsuario_id' => $tipoUsuario->id
            ]);

        Privilegio::where('usuario_id', $id)->delete();

        DB::table('model_has_permissions')
            ->where('model_has_permissions.model_id', $id)
            ->delete();

        $this->cambiarPermisos($usuario, $tipoUsuario->id);

        return response()->json([
            'ruta' => route('usuarios.index')
        ]);
    }

    public function guardarPermisos(Request $request, $id)
    {
        $usuario = Usuario::where('id', $id)
            ->first();

        $clave0 = array_search(0, $request->accesos);

        $accesos = $clave0 !== false
            ? Arr::except($request->accesos, [$clave0])
            : $request->accesos;

        Privilegio::where('usuario_id', $id)->delete();

        DB::table('model_has_permissions')
            ->where('model_id', $usuario->id)
            ->delete();

        $arrayAccesos = [];

        foreach ($accesos as $acceso) {
            array_push($arrayAccesos, [
                'usuario_id' => $id,
                'menu_id' => $acceso
            ]);
        }

        if (count($arrayAccesos) >= 1) {
            DB::table('privilegios')->insert($arrayAccesos);

            // AGREGAR PRIVILEGIOS
            foreach ($request->permisos as $permiso) {
                $permisosRegistrados = Permission::where('name', $permiso)
                    ->first();

                if (!isset($permisosRegistrados->id)) {
                    Permission::create(['name' => $permiso]);
                }

                $usuario->givePermissionTo($permiso);
            }
        }


        return redirect()->route('usuarios.index');
    }

    public function permisos($id)
    {

        $menuAll = UsuarioController::crearMenu();

        $usuario = Usuario::find($id);

        $opciones = [
            'titlePage' => 'Asignar Permisos',
            'usuario' => $usuario,
            'menuAll' => $menuAll,
            'elemento' => $id,
            'otherLink' => [
                'name' => 'Usuarios',
                'link' => 'usuarios'
            ]
        ];

        return view('seguridad.usuarios.permisos', $opciones);
    }


    public function editarEstado($id)
    {

        $usuario = Usuario::where('id', $id)
            ->first();

        return response()->json([
            'action' => route('usuarios.estado', $id),
            'usuario' => $usuario,
        ]);
    }


    public function estado(Request $request, $id)
    {
        /*
        $usuario = Usuario::where('id', $id)
            ->first();

        if ($usuario->estado) {
            Usuario::where('id', $id)
                ->update([
                    'estado' => 0
                ]);
        } else {
            Usuario::where('id', $id)
                ->update([
                    'estado' => 1
                ]);
        }
        */

        Usuario::where('id', $id)
            ->update([
                'estado' => $request->estado
            ]);

        return response()->json([
            'ruta' => route('usuarios.index'),
        ]);
    }



    public function destroy($id)
    {
        //
    }
}
