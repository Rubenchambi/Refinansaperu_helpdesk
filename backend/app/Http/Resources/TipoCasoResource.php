<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoCasoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nombre'         => $this->nombre,
            'descripcion'    => $this->descripcion,
            'prioridadBase'  => $this->prioridadBase,
            'estado'         => (bool) $this->estado,
            'creado_en'      => $this->created_at?->format('d/m/Y H:i'),
            'actualizado_en' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}