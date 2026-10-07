<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TramiteVirtual;

class TramiteVirtualController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('can:tramite-virtual.index')->only('index');
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = auth()->user();

        // Separar apellidos si están presentes
        $apellidoPaterno = '';
        $apellidoMaterno = '';
        if ($usuario && !empty($usuario->apellidos)) {
            $parts = explode(' ', trim($usuario->apellidos), 2);
            $apellidoPaterno = $parts[0] ?? '';
            $apellidoMaterno = $parts[1] ?? '';
        }

        $opciones = [
            'titlePage'       => 'Trámite Virtual',
            'usuario'         => $usuario,
            'apellidoPaterno' => $apellidoPaterno,
            'apellidoMaterno' => $apellidoMaterno,
        ];

        return view('tramiteVirtual.index', $opciones);
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
