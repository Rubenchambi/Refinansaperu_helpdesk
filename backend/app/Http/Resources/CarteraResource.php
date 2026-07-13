<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarteraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nombre_cartera' => $this->nombre_cartera,
            'descripcion'    => $this->descripcion,
            'estado'         => $this->estado,
            // Agregamos la fecha de creación por si la necesitas en el frontend
            'created_at'     => $this->created_at ? $this->created_at->format('d/m/Y') : null,
        ];
    }
}