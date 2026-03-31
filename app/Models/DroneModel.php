<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DroneModel extends Model
{
    protected $fillable = [
        'marca',
        'modelo',
        'tipo_uas',
        'num_motores',
        'peso_fabrica_gr',
        'pais_fabricacion',
        'autonomia_min',
        'camara',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
