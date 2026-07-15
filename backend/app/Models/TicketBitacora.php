<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketBitacora extends Model
{
    protected $table = 'ticket_bitacoras';
    protected $fillable = [
        'ticketId', 'usuarioId', 'accion', 'comentario', 
        'estadoAnterior', 'estadoNuevo'
    ];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }
}