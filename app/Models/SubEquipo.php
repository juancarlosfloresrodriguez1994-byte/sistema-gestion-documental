<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubEquipo extends Model
{
    protected $table = 'subequipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_subequipo',
        'estado',
        'equipo_id',
    ];
}
