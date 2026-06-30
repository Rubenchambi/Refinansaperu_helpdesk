<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activo extends Model {
    protected $guarded = [];

    public function producto() {
        return $this->belongsTo(Producto::class, 'productoId');
    }

    public function usuarioAsignado() {
        return $this->belongsTo(Usuario::class, 'usuarioAsignadoId');
    }
}