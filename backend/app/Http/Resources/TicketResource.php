<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource{

    public function toArray($request): array
        {
            return [
                'id' => $this->id,
                'asunto' => $this->asunto,
                'descripcion' => $this->descripcion,
                'prioridad' => $this->prioridad,
                'estadoTicket' => $this->estadoTicket,
                'reportadoPor' => $this->usuarioReportante->nombre,
                'activo' => new ActivoResource($this->whenLoaded('activo')),
                'tipoCaso' => $this->tipoCaso->nombre,
                'fechaCreacion' => $this->created_at->format('d/m/Y H:i'),
            ];
        }

}