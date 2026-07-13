<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cartera extends Model
{

    protected $fillable = [
        'nombre_cartera',
        'descripcion',
        'estado'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_cartera', 'cartera_id', 'usuario_id');
    }
}
