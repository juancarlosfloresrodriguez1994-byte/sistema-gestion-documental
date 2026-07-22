<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    protected $table = 'solicitante';
    public $timestamps = false;

    protected $fillable = [
        'soli_nombre', 'soli_apellido', 'soli_documento', 'soli_razonsocial', 'soli_direccion', 'soli_telefono',
        'soli_correo', 'soli_tipo', 'nombre_completo',
    ];

    public function setSoliNombreAttribute($value)
    {
        $this->attributes['soli_Nombre'] = strtoupper($value);
    }

    public function setSoliApellidoAttribute($value)
    {
        $this->attributes['soli_Apellido'] = strtoupper($value);
    }

    public function setSoliDireccionAttribute($value)
    {
        $this->attributes['soli_Direccion'] = strtoupper($value);
    }

    public function setSoliRazonSocialAttribute($value)
    {
        $this->attributes['soli_RazonSocial'] = strtoupper($value);
    }

    public function setNombreCompletoAttribute($value)
    {
        $this->attributes['nombre_completo'] = strtoupper($value);
    }
}
