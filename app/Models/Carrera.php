<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_carrera';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nombre',
    ];

    public function aspirantes()
    {
        return $this->hasMany(Aspirante::class, 'id_carrera', 'id_carrera');
    }
}
