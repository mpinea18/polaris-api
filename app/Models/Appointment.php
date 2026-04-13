<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'drone_id',
        'tecnico_id',
        'empresa_id',
        'tech_name',
        'service',
        'tipo',
        'date',
        'fecha',
        'time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function drone()
    {
        return $this->belongsTo(Drone::class, 'drone_id');
    }

    public function tech()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}