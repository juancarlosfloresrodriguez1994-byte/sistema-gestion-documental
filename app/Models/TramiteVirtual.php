<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TramiteVirtual extends Model
{
    use HasFactory;

    protected $table = 'tramite_virtual';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'usuario_id',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
