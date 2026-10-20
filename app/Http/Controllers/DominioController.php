<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dominio;
use Illuminate\Support\Facades\DB;

class DominioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }



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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
