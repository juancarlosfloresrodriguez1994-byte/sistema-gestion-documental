<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dominio;

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

        $elementos = Dominio::query()
            ->where(function ($query) use ($term) {

                $query->where('domi_nombre', 'like', '%' . $term . '%');
            })
            ->orderBy('domi_nombre')
            ->take(20)
            ->get();

        $respuesta = [];

        foreach ($elementos as $elemento) {


            $texto = $elemento->domi_tipo . ': ' . $elemento->domi_nombre;

            $respuesta[] = [
                'id'   => $elemento->id,
                'text' => $texto
            ];
        }


        // dd($respuesta);

        return response()->json($respuesta);
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
