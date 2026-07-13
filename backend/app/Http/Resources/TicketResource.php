<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo_ticket' => $this->codigo_ticket,
            'asunto' => $this->asunto,
            'descripcion' => $this->descripcion,
            'prioridad' => $this->prioridad,
            'estado_ticket' => $this->estadoTicket,
            'tipo_cola' => $this->tipo_cola,
            'fecha_creacion' => $this->created_at->format('d/m/Y H:i'),
            // Relaciones para que el frontend no reciba solo un ID
            'reportante' => $this->usuarioReportanteId, 
            'tecnico_asignado' => $this->tecnicoAsignadoId,
            'bitacoras' => TicketBitacoraResource::collection($this->whenLoaded('bitacoras')),
        ];
    }
}