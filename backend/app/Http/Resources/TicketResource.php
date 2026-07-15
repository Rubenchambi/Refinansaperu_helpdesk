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
                    'estadoTicket' => $this->estadoTicket,
                    'tipo_cola' => $this->tipo_cola,
                    'fecha_creacion' => $this->created_at ? $this->created_at->format('d/m/Y H:i') : null,
                    
                    // Mantenemos solo los IDs que sí existen en tu tabla
                    'reportante_id' => $this->usuarioReportanteId, 
                    'tecnicoAsignadoId' => $this->tecnicoAsignadoId,
                    
                    // Solo cargamos la bitácora si ya está configurada
                    'bitacoras' => TicketBitacoraResource::collection($this->whenLoaded('bitacoras')),
                ];
    }
}