<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketBitacoraResource extends JsonResource{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'comentario' => $this->comentario,
            'estadoAnterior' => $this->estadoAnterior,
            'estadoNuevo' => $this->estadoNuevo,
            'fecha' => $this->created_at->format('d/m/Y H:i'),
            'usuario' => $this->usuario->nombre,
        ];
    }


}