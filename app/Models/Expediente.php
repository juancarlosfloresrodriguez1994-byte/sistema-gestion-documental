<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'tramite_externo';
    public $timestamps = false;

    protected $fillable = [
        'numero_expediente',
        'expe_codigo',
        'asunto',
        'descripcion',
        'fecha_registro',
        'hora_registro',
        'numero_documento',
        'numero_folio',
        'solicitante_id',
        'soli_tipo',
        'nombre_solicitante',
        'documento_solicitante',
        'tipoDocumento_id',
        'nombre_documento',
        'tipo_tramite',
        'anio',
        'estado',
        'mes',
        'numero_mes',
        'usuario_actual_id',
        'area_actual_id',
        'equipo_actual_id',
        'sub_equipo_actual_id',
        'usuario_registro_id',
        'documento_adjunto'
    ];
}
