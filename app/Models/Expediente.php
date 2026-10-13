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
        'dominio_destino_id',
        'usuario_registro_id',
        'documento_adjunto'
    ];

    public function dominioDestino()
    {
        return $this->belongsTo(Dominio::class, 'dominio_destino_id');
    }

    public function movimientos()
    {
        return $this->hasMany(ExpedienteMovimiento::class, 'expediente_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'solicitante_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipoDocumento_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
