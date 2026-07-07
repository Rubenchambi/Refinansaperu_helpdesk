<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCaso extends Model
{
     protected $table = 'tipo_casos';

    protected $fillable = [
        'nombre',
        'prioridadBase',
        'estado',
        'descripcion',      // ← nueva columna
    ];
    
    protected $casts = [
        'estado' => 'boolean',
    ];

}
