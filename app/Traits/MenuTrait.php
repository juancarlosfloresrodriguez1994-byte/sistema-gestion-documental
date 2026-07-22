<?php

namespace App\Traits;

use App\Models\Menu;
use App\Models\Permisos;
use Illuminate\Support\Facades\DB;

trait MenuTrait
{
    public function crearMenu()
    {
        $menuAll = [];
        $indice = 0;

        $menusPadres = DB::table('menus')
            ->where('padre', '0')
            ->select(
                'menus.id as id',
                'menus.open as menu',
                'menus.descripcion as descripcion',
                'menus.icono as icono',
            )
            ->orderBy('orden')
            ->get();

        $menusAceptados = DB::table('menus')
            ->select(
                'menus.id as id',
                'menus.descripcion as descripcion',
                'menus.icono as icono',
                'menus.padre as padre',
            )
            ->orderBy('menus.open')
            ->orderBy('menus.orden')
            ->get()
            ->toArray();

        foreach ($menusPadres as $item) {
            $menu = (array) $item;

            if ($item->menu == 0) {
                $submenu = $this->getMenu($item->id, $menusAceptados);

                // dd($submenu);

                if ($submenu['estado']) {
                    $menuAll[$indice] = array_merge($menu, ['submenu' => $submenu['menu']]);
                    $indice += 1;
                }
            } else {
                $submenu = $this->getSubmenu($item->id, $menusAceptados);

                if ($submenu['estado']) {
                    $menuAll[$indice] = array_merge($menu, ['submenu' => $submenu['submenu']]);
                    $indice += 1;
                }
            }
        }


        // AGREGAR LOS PERMISOS
        $todosPermisos = Permisos::select('name', 'descripcion', 'menu_id')
            ->orderBy('orden')
            ->get()
            ->toArray();

        for ($i = 0; $i < count($menuAll); $i++) {
            foreach ($menuAll[$i]['submenu'] as $index => $submenu) {
                $submenu = (array) $submenu;

                $permisos = $this->getPermisos($submenu['id'], $todosPermisos);

                if ($permisos['estado']) {
                    $menuAll[$i]['submenu'][$index] = array_merge($submenu, ['permisos' => $permisos['permisos']]);
                } else {
                    $menuAll[$i]['submenu'][$index] = array_merge($submenu, ['permisos' => []]);
                }
            }
        }

        return $menuAll;
    }

    public function obtenerPermiso($menu_id)
    {

        //  dd($id);
        $permiso = Permisos::where('menu_id', $menu_id)
            ->orderBy('orden')
            ->get()
            ->toArray();

        return compact('permiso');
    }

    public function getMenu($menu, $aceptados)
    {
        $menu = array_filter($aceptados, function ($item) use ($menu) {
            return $item->id == $menu;
        });

        $estado = count($menu) > 0 ? true : false;

        return compact('menu', 'estado');
    }

    public function getSubmenu($menu, $aceptados)
    {
        $submenu = array_filter($aceptados, function ($item) use ($menu) {
            return $item->padre == $menu;
        });

        $submenu = array_values($submenu);

        $estado = count($submenu) > 0 ? true : false;

        return compact('submenu', 'estado');
    }

    public function getPermisos($menu, $todosPermisos)
    {
        $permisos = array_filter($todosPermisos, function ($item) use ($menu) {
            return $item['menu_id'] == $menu;
        });

        $estado = count($permisos) > 0 ? true : false;

        return compact('permisos', 'estado');
    }
}
