<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketBitacora extends Model
{
    protected $fillable = [
        'ticketId', 'usuarioId', 'accion', 'comentario', 
        'estadoAnterior', 'estadoNuevo'
    ];
}