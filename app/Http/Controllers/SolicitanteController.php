<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Solicitante;

class SolicitanteController extends Controller
{

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
        //
    }

    public function autocompletadoSolicitante(Request $request)
    {

        $term = trim($request->term ?? '');

        $elementos = Solicitante::query()
            ->where(function ($query) use ($term) {

                $query->where('soli_nombre', 'like', '%' . $term . '%')
                    ->orWhere('soli_documento', 'like', '%' . $term . '%')
                    ->orWhere('soli_razonsocial', 'like', '%' . $term . '%')
                    ->orWhere('soli_apellido', 'like', '%' . $term . '%');
            })
            ->orderBy('soli_apellido')
            ->take(20)
            ->get();

        $respuesta = [];

        foreach ($elementos as $elemento) {

            if (!empty($elemento->soli_razonsocial)) {

                $texto = $elemento->soli_razonsocial . ' | ' . $elemento->soli_documento;
            } else {

                $texto = $elemento->soli_apellido. ' ' . $elemento->soli_nombre  . ' | ' . $elemento->soli_documento;
            }

            $respuesta[] = [
                'id'   => $elemento->id,
                'text' => $texto
            ];
        }

        
      // dd($respuesta);

        return response()->json($respuesta);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
