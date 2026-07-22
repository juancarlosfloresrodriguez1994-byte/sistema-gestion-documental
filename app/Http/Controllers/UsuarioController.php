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

        $opciones = [
            'placeholder' => 'usuario',
            'titlePage' => 'Usuarios',
            'tipos' => $tipos,
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
            // 'oficina' => $oficina,
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
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $order  = $request->input('order', []);
        $search = trim((string) $request->input('search.value', ''));

        // Columnas esperadas (índices desde el front)
        $columns = ['usuario.id', 'usuario.dni', 'nombre_completo', 'tipo_usuario.descripcion as descripcion', 'estado'];

        //$query = Usuario::query()->select($columns);


        $query = Usuario::query()
            ->select($columns)
            ->leftJoin('tipo_usuario', 'tipo_usuario.id', '=', 'usuario.tipoUsuario_id');



        // Filtro global
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'LIKE', "%{$search}%")
                    ->orWhere('dni', 'LIKE', "%{$search}%");

                if (ctype_digit($search)) {
                    $q->orWhere('usuario.id', (int) $search);
                }
            });
        }

        $recordsTotal    = Usuario::count();
        $recordsFiltered = (clone $query)->count();

        // Orden
        if (!empty($order)) {
            foreach ($order as $ord) {
                $colIdx = (int) data_get($ord, 'column', 0);
                $dir    = data_get($ord, 'dir', 'asc') === 'desc' ? 'desc' : 'asc';
                $col    = $columns[$colIdx] ?? 'id';
                $query->orderBy($col, $dir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        
        

        // Paginación
        $data = $query->skip($start)->take($length)->get();

        $data = $data->map(function ($row) {

           $botones = '';

         $botones .= '
<div class="symbol symbol-20px">
      <a href="#"
       data-fancybox
       class="btn btn-sm btn-icon btn-primary btn-active-primary w-20px h-20px"
       data-bs-toggle="tooltip" title="Ver documento">
        <i class="ki-outline ki-devices-2"></i>
    </a>
</div>';

 $botones .= '
        <div class="symbol symbol-20px">
            <a href="#"
               data-fancybox data-type="pdf" 
               class="btn btn-sm btn-icon btn-success btn-active-success w-20px h-20px" 
               data-bs-toggle="tooltip" title="Ver anexo">
                <i class="ki-outline ki-sms"></i>
            </a>
        </div>';

            return [
                'checkbox'        => ' <div class="form-check form-check-sm form-check-custom form-check-solid">
										<input name="idusuario" class="form-check-input idusuario" data-id=' . $row->id . ' data-url=' . route('permisos.accesos', $row->id) . '   type="checkbox" value="' . $row->id . '" /></div>',
                'id'              => $row->id,
                'documento'       => $row->dni,
                'nombre_completo' => $row->nombre_completo,
                'descripcion' => $row->descripcion,
                'estado' => $row->estado,
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
            'documento'  => $request->documento,
            'nombre_completo'  => $request->apellidos . ' ' . $request->nombres,
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

        return response()->json([
            'action' => route('usuarios.estado', $id),
            'tipos' => $tipos,
            'usuario' => $usuario,
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
            'documento' => ['required', 'digits:8', 'numeric', Rule::unique('usuario', 'documento')->ignore($id)],
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
                    'documento'  => $request->documento,
                    'nombre_completo'  => $request->apellidos . ', ' . $request->nombres,
                ]);
        } else {
            Usuario::find($id)
                ->update([
                    'nombres'   => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'nickname'  => $request->nickname,
                    'password'  => $request->password,
                    'tipoUsuario_id' => $request->tipoUsuario_id,
                    'documento'  => $request->documento,
                    'nombre_completo'  => $request->apellidos . ', ' . $request->nombres,
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
