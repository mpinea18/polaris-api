<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    protected $fillable = [
        'user_id',
        'drone_id',
        'tech_id',
        'nombre',
        'cedula_nit',
        'direccion',
        'ciudad',
        'telefono',
        'codigo_producto',
        'nombre_producto',
        'numero_factura',
        'fecha_compra',
        'numero_serial',
        'serial_baterias',
        'serial_control',
        'serial_cargador',
        'falla_reportada',
        'contenido',
        'usuario_final',
        'sufrió_accidente',
        'observaciones',
        'status',
        'motivo_negacion',
        'adjuntos',
    ];

    protected $casts = [
        'usuario_final'    => 'boolean',
        'sufrió_accidente' => 'boolean',
        'fecha_compra'     => 'date',
        'adjuntos'         => 'array',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function drone()  { return $this->belongsTo(Drone::class); }
    public function tech()   { return $this->belongsTo(User::class, 'tech_id'); }
}
