<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dominio extends Model
{
    use HasFactory;

    protected $table = 'dominio';
    public $timestamps = false;

    protected $fillable = [
        'domi_idpadre',
        'domi_tipo',
        'domi_nombre',
        'domi_siglas',
        'domi_descripcion',
        'domi_responsable',
        'domi_idarea',
        'domi_idarea_str',
        'domi_idequipo',
        'domi_idequipo_str',
        'domi_idsubequipo',
        'domi_idold',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'domi_idarea', 'id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'domi_idequipo', 'id');
    }

    public function subequipo()
    {
        return $this->belongsTo(SubEquipo::class, 'domi_idsubequipo', 'id');
    }

    public function padre()
    {
        return $this->belongsTo(Dominio::class, 'domi_idpadre', 'id');
    }

    public function hijos()
    {
        return $this->hasMany(Dominio::class, 'domi_idpadre', 'id');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'dominio_id', 'id');
    }
}
