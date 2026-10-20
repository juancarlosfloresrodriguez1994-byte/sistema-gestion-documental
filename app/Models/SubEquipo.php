<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubEquipo extends Model
{
    use HasFactory;

    protected $table = 'subequipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_subequipo',
        'equipo_id',
        'siglas',
        'descripcion',
        'responsable',
        'estado',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id', 'id');
    }

    public function dominio()
    {
        return $this->hasOne(Dominio::class, 'domi_idsubequipo', 'id')->where('domi_tipo', 'SUBEQUIPO');
    }
}
