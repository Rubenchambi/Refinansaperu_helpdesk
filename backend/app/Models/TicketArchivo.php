<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketArchivo extends Model
{
    use HasFactory;

    protected $table = 'ticket_archivos';

    protected $fillable = [
        'ticket_id',
        'nombre_original',
        'ruta_archivo',
        'extension',
    ];

    // Relación inversa: Un archivo pertenece a un ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}