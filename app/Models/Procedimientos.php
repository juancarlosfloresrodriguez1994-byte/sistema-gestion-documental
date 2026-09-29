<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimientos extends Model
{
    protected $table = 'procedimiento';
    public $timestamps = false;

    protected $fillable = [
        'prod_descripcion',
        'prod_plazo',
        'prod_monto',
    ];
}
