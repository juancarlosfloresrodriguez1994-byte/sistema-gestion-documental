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
        'dominio_origen_id',
        'usuario_origen_id',
        'dominio_destino_id',
        'usuario_destino_id',
        'tipo_movimiento',
        'estado',
        'fecha_movimiento',
        'usuario_accion_id',
    ];

    public function dominioOrigen()
    {
        return $this->belongsTo(Dominio::class, 'dominio_origen_id');
    }

    public function dominioDestino()
    {
        return $this->belongsTo(Dominio::class, 'dominio_destino_id');
    }

    public function usuarioOrigen()
    {
        return $this->belongsTo(Usuario::class, 'usuario_origen_id');
    }

    public function usuarioDestino()
    {
        return $this->belongsTo(Usuario::class, 'usuario_destino_id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}
