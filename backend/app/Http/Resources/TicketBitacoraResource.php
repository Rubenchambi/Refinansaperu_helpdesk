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
            // Traemos el nombre del usuario de la relación
            'usuario_nombre' => $this->usuario ? $this->usuario->nombre : 'Sistema',
            // Fecha legible y precisa
            'fecha' => $this->created_at->format('d/m/Y H:i'),
            'fecha_relativa' => $this->created_at->diffForHumans(),
        ];
    }
}