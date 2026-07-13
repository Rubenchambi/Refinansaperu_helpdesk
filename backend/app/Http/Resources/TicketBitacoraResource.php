<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketBitacoraResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'accion' => $this->accion,
            'comentario' => $this->comentario,
            'estado_anterior' => $this->estadoAnterior,
            'estado_nuevo' => $this->estadoNuevo,
            'fecha_registro' => $this->created_at->diffForHumans(), // Ej: "hace 5 minutos"
        ];
    }
}