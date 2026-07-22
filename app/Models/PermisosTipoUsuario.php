<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisosTipoUsuario extends Model
{
    protected $table = 'permiso_tipousuario';
    public $timestamps = false;

    protected $fillable = [
        'permiso',
        'tipoUsuario_id',
    ];
}
