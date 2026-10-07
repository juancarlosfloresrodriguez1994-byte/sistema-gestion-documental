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
                'd.id  as id',

                DB::raw("
                CASE d.domi_tipo
                    WHEN 'AREA' THEN
                        CONCAT('A: ', d.domi_nombre)

                    WHEN 'EQUIPO' THEN
                        CONCAT(
                            'E: ',
                            d.domi_idarea_str,
                            ' > ',
                            d.domi_nombre
                        )

                    WHEN 'SUBEQUIPO' THEN
                        CONCAT(
                            'SE: ',
                            d.domi_idarea_str,
                            ' > ',
                            d.domi_idequipo,
                            ' > ',
                            d.domi_nombre
                        )
                END AS text
            "),

                'd.domi_nombre as destino',
                'd.domi_Tipo as tipo'
            )
            ->where('d.domi_nombre', 'like', "%{$term}%")
            ->whereIn('d.domi_tipo', [
                'AREA',
                'EQUIPO',
                'SUBEQUIPO'
            ])
            ->limit(10)
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
