<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['nombre', 'estado'];

    // Relación inversa: Un área tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'area_id');
    }
}