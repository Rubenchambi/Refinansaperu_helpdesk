<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model {
    protected $guarded = [];

    public function producto() {
        return $this->belongsTo(Producto::class, 'productoId');
    }

    public function usuarioSolicitante() {
        return $this->belongsTo(Usuario::class, 'usuarioSolicitanteId');
    }
}