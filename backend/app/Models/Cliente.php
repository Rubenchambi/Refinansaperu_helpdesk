<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
   protected $fillable = [
        'RazonSocial', 
        'ruc',
        'contacto', 
        'telefono', 
        'direccion', 
        'distrito', 
        'ciudad', 
        'cargo', 
        'cartera_asignada'
];
}
