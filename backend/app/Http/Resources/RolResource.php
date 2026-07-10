<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'estado'      =>(bool) $this->estado,
            'descripcion' => $this->descripcion,
            'created_at'  => $this->created_at->format('d/m/Y'),
        ];
    }
}