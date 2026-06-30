<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketBitacora extends Model {
    protected $guarded = [];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticketId');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }
}