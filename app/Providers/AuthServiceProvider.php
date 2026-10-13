<?php
// use App\Models\User;
// Crear el layaut menu

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use App\Models\Menu;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // SuperAdministrador (ID: 2) y Administrador (ID: 1) tienen autorización total en todas las habilidades
        Gate::before(function ($user, $ability) {
            if ($user && in_array($user->tipoUsuario_id, [1, 2])) {
                return true;
            }
        });
        View::composer('layouts.menu', function ($view) {
            $menuAll = [];
            $userLogin = Auth::id();

            $menuAll = [];
            $menus = Menu::where('padre', '0')
                ->orderBy('orden')
                ->get()
                ->toArray();

            for ($i = 0; $i < count($menus); $i++) {
                if ($menus[$i]['open'] == 0) {
                    $submenu = $this->getMenu($menus[$i]);

                    if ($submenu['estado']) {
                        $menuAll[$i] = array_merge($menus[$i], ['submenu' => []]);
                    }
                } else {
                    $submenu = $this->getSubmenu($menus[$i]);

                    if ($submenu['estado']) {
                        $menuAll[$i] = array_merge($menus[$i], ['submenu' => $submenu['submenu']]);
                    }
                }
            }

            $urlGlobal = request()->path();
            $token = explode('/', $urlGlobal);

            // dd($token[0]);

            $urlActual = $token[0];


            //dd($urlActual);

            $view->with('menuAll', $menuAll)
                ->with('urlActual', $urlActual);
        });
    }

    public function getSubmenu($menu)
    {
        $user = Auth::user();
        if ($user && in_array($user->tipoUsuario_id, [1, 2])) {
            $submenu = DB::table('menus')
                ->where('padre', $menu['id'])
                ->orderBy('orden')
                ->get()
                ->toArray();
            return ['submenu' => $submenu, 'estado' => count($submenu) > 0];
        }

        $estado = false;
        $submenu = DB::table('privilegios')
            ->join('menus', 'menus.id', '=', 'privilegios.menu_id')
            ->orderBy('menus.orden')
            ->where([
                ['privilegios.usuario_id', Auth::id()],
                ['menus.padre', $menu['id']]
            ])
            ->get()
            ->toArray();

        if (count($submenu) > 0) {
            $estado = true;
        }

        return compact('submenu', 'estado');
    }

    public function getMenu($menu)
    {
        $user = Auth::user();
        if ($user && in_array($user->tipoUsuario_id, [1, 2])) {
            $menus = DB::table('menus')
                ->where('padre', '0')
                ->where('id', $menu['id'])
                ->orderBy('orden')
                ->get()
                ->toArray();
            return ['menus' => $menus, 'estado' => count($menus) > 0];
        }

        $estado = false;
        $menus = DB::table('privilegios')
            ->join('menus', 'menus.id', '=', 'privilegios.menu_id')
            ->orderBy('menus.orden')
            ->where([
                ['privilegios.usuario_id', Auth::id()],
                ['menus.padre', '0'],
                ['menus.id', $menu['id']]
            ])
            ->get()
            ->toArray();

        if (count($menus) > 0) {
            $estado = true;
        }

        return compact('menus', 'estado');
    }
}
