<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model {
    protected $guarded = [];

    public function activos() {
        return $this->hasMany(Activo::class, 'productoId');
    }
}
