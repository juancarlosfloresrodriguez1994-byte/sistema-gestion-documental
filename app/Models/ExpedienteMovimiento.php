<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteMovimiento extends Model
{
    protected $table = 'movimientos_tramiteexterno';
    public $timestamps = false;

    protected $fillable = [
        'expediente_id',
        'area_origen_id',
        'equipo_origen_id',
        'sub_equipo_origen_id',
        'usuario_origen_id',

        'area_destino_id',
        'equipo_destino_id',
        'sub_equipo_destino_id',
        'usuario_destino_id',
        'tipo_movimiento',
        'estado',
        'fecha_movimiento',
        'usuario_accion_id',
    ];
}
