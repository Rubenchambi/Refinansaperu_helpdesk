<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivoResource extends JsonResource{

public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'codigoPatrimonial' => $this->codigoPatrimonial,
            'numeroSerie' => $this->numeroSerie,
            'estadoFisico' => $this->estadoFisico,
            // 📱 Nuevas columnas para celulares / chips / líneas
            'numeroTelefono' => $this->numeroTelefono,
            'operador' => $this->operador,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
            
            // Relaciones
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'usuarioAsignado' => $this->usuarioAsignado ? $this->usuarioAsignado->nombre : 'Sin asignar',
            'usuarioAsignadoId' => $this->usuarioAsignadoId, // Útil por si en Vue necesitas el ID para editar
        ];
    }

}