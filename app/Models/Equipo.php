<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_equipo',
        'are_id',
        'siglas',
        'descripcion',
        'responsable',
        'estado',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'are_id', 'id');
    }

    public function subequipos()
    {
        return $this->hasMany(SubEquipo::class, 'equipo_id', 'id');
    }

    public function dominio()
    {
        return $this->hasOne(Dominio::class, 'domi_idequipo', 'id')->where('domi_tipo', 'EQUIPO');
    }
}
