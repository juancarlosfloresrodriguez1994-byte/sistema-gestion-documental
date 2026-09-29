<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_equipo',
        'estado',
        'are_id',
    ];
}
