<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmpresaSetting extends Model
{
    protected $fillable = ['empresa_id', 'key', 'value'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}