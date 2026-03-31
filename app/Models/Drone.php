<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $fillable = [
        'user_id',
        'plate',
        'model',
        'marca',
        'drone_model_id',
        'numero_serie',
        'tipo_uas',
        'num_motores',
        'color',
        'peso_real',
        'tipo_registro',
        'sistemas',
        'poliza',
        'sistema_recuperacion',
        'equipo_fabrica',
        'hours',
        'status',
        'last_service',
    ];

    protected $casts = [
        'sistemas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function droneModel()
    {
        return $this->belongsTo(DroneModel::class);
    }
}