<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'matricula',
        'curp',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'fecha_nacimiento',
        'email',
        'telefono',
        'id_carrera',
        'estatus',
        'id_aspirante',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function aspirante()
    {
        return $this->belongsTo(Aspirante::class, 'id_aspirante', 'id_aspirantes');
    }
}
