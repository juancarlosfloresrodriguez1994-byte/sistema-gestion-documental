<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InicioController;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoUsuarioController;

use App\Http\Controllers\TramiteExternoController;
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
