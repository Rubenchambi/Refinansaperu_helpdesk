<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'codigo_ticket', 'usuarioReportanteId', 'tecnicoAsignadoId', 
        'activoId', 'tipoCasoId', 'asunto', 'descripcion', 
        'prioridad', 'estadoTicket', 'tipo_cola', 'asignado_at', 
        'finalizado_at', 'estado','imagen'
    ];

    public function bitacoras() {
        return $this->hasMany(TicketBitacora::class, 'ticketId','id');
    }

    public function reportante() {
        return $this->belongsTo(Usuario::class, 'usuarioReportanteId');
    }

    public function tecnico() {
        return $this->belongsTo(Usuario::class, 'tecnicoAsignadoId');
    }

    public function activo() {
        return $this->belongsTo(Activo::class, 'activoId');
    }
    public function tipoCaso()
    {
    return $this->belongsTo(TipoCaso::class, 'tipoCasoId');
    }

    public function archivos() { return $this->hasMany(TicketArchivo::class); }
}