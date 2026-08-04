<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria, // 👈 Lo nuevo de la migración
            'marca' => $this->marca,
            'descripcion' => $this->descripcion, // 👈 Lo nuevo de la migración
            'stockMinimo' => $this->stockMinimo,
            'estado' => $this->estado,
            // Opcional pero útil: cuántos activos físicos existen de este producto
            'activos_count' => $this->whenCounted('activos'), 
            'created_at' => $this->created_at,
        ];
    }
}
