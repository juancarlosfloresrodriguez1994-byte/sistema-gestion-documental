<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dominio extends Model
{
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
        'domi_idold',
    ];
}
