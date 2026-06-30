<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $guarded = [];

    // Relación: Quién reportó el ticket
    public function usuarioReportante() {
        return $this->belongsTo(Usuario::class, 'usuarioReportanteId');
    }

    // Relación: Qué activo está fallando
    public function activo() {
        return $this->belongsTo(Activo::class, 'activoId');
    }

    // Relación: Tipo de caso
    public function tipoCaso() {
        return $this->belongsTo(TipoCaso::class, 'tipoCasoId');
    }
}