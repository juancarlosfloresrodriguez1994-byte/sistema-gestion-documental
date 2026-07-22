<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDocumento;

class TramiteExternoController extends Controller
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

        $tipos = TipoDocumento::orderBy('descripcion')
            ->get();

        $opciones = [
            'placeholder' => 'Tramite Externo',
            'titlePage' => 'Tramite Externo',
            'tipos' => $tipos,
        ];

        return view('mesaPartes.index',  $opciones);
    }


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
