<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InicioController;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoUsuarioController;

use App\Http\Controllers\TramiteExternoController;

use App\Http\Controllers\SolicitanteController;

use App\Http\Controllers\ConsultarApisController;

use App\Http\Controllers\DominioController;

use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RecuperarPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'index']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ─── Registro público ───
Route::get('registro', [RegistroController::class, 'index'])->name('registro');
Route::post('registro/buscar-dni', [RegistroController::class, 'buscarDni'])->name('registro.buscar-dni');
Route::post('registro', [RegistroController::class, 'registrar'])->name('registro.enviar');
Route::post('registro/reenviar', [RegistroController::class, 'reenviar'])->name('registro.reenviar');
Route::get('registro/verificar/{token}', [RegistroController::class, 'verificar'])->name('registro.verificar');

// ─── Recuperar contraseña ───
Route::get('recuperar-password', [RecuperarPasswordController::class, 'index'])->name('password.solicitar');
Route::post('recuperar-password', [RecuperarPasswordController::class, 'enviarEnlace'])->name('password.enviar');
Route::get('reset-password/{token}', [RecuperarPasswordController::class, 'formularioReset'])->name('password.reset');
Route::post('reset-password', [RecuperarPasswordController::class, 'resetear'])->name('password.resetear');


Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('limpiar', [InicioController::class, 'limpiar'])->name('limpiar.index');

Route::resource('usuarios', UsuarioController::class);
Route::post('usuarios/filtro', [UsuarioController::class, 'filtro'])->name('usuarios.filtro');
Route::put('estado-usuarios/{id}', [UsuarioController::class, 'estado'])->name('usuarios.estado');
Route::post('filter-Usuario', [UsuarioController::class, 'filterUsuario']);
Route::get('permisos/{id}', [UsuarioController::class, 'permisos'])->name('permisos.accesos');
Route::get('asignar/{id}', [UsuarioController::class, 'asignarPermisos'])->name('asignar.accesos');
Route::put('asignacion-accesos/{id}', [UsuarioController::class, 'asignarAccesos'])->name('usuarios.asignar-accesos');
Route::put('asignacion-permisos/{id}', [UsuarioController::class, 'guardarPermisos'])->name('usuarios.asignar-permisos');
Route::get('apiUsuarios', [UsuarioController::class, 'apiUsuarios'])->name('apiUsuarios.index');
Route::get('filtro-usuario', [UsuarioController::class, 'autocompletado'])->name('usuario.autocompletados');

Route::resource('tipo-usuario', TipoUsuarioController::class);
Route::post('tipo-usuario/filtro', [TipoUsuarioController::class, 'filtro'])->name('tipo-usuario.filtro');
Route::put('estado-usuarios/{id}', [UsuarioController::class, 'estado'])->name('usuarios.estado');

Route::resource('tramite-externo', TramiteExternoController::class);
Route::post('filterEquipo', [TramiteExternoController::class, 'filterEquipo']);
Route::post('filtersubEquipo', [TramiteExternoController::class, 'filtersubEquipo']);
Route::get('apiTramiteExterno', [TramiteExternoController::class, 'apiTramiteExterno'])->name('apiTramiteExterno.index');

Route::get('filtrosolicitante', [SolicitanteController::class, 'autocompletadoSolicitante']);

Route::post('BuscarDocumentoUsuario', [ConsultarApisController::class, 'BuscarDocumentoUsuario'])->name('BuscarDocumentoUsuario.index');

Route::get('filtrodominio', [DominioController::class, 'autocompletadoDominio']);

