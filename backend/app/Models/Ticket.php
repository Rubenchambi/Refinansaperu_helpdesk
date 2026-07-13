<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'codigo_ticket', 'usuarioReportanteId', 'tecnicoAsignadoId', 
        'activoId', 'tipoCasoId', 'asunto', 'descripcion', 
        'prioridad', 'estadoTicket', 'tipo_cola', 'asignado_at', 
        'finalizado_at', 'estado'
    ];

    public function bitacoras() {
        return $this->hasMany(TicketBitacora::class, 'ticketId');
    }

    public function reportante() {
        return $this->belongsTo(Usuario::class, 'usuarioReportanteId');
    }
}