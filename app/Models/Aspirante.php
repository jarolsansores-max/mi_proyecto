<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspirante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_aspirantes';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'folio',
        'curp',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'fecha_nacimiento',
        'email',
        'telefono',
        'id_carrera',
        'promedio_bachillerato',
        'estatus',
        'documentos_completos',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'promedio_bachillerato' => 'decimal:2',
        'documentos_completos' => 'integer',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'id_aspirante', 'id_aspirantes');
    }
}
