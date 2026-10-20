<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    public $timestamps = false;

    protected $fillable = [
        'nombre_area',
        'siglas',
        'descripcion',
        'responsable',
        'estado',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'are_id', 'id');
    }

    public function dominio()
    {
        return $this->hasOne(Dominio::class, 'domi_idarea', 'id')->where('domi_tipo', 'AREA');
    }
}
