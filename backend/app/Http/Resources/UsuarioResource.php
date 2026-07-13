<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'nombre'         => $this->nombre,
            'apellidos'      => $this->apellidos,
            'nombreCompleto' => $this->nombre . ' ' . $this->apellidos,
            'email'          => $this->email,
            'dni'            => $this->dni,
            'rolId'          => $this->rolId,
            'rol'            => $this->role ? $this->role->nombre : 'Sin rol',
            'estado'         => $this->estado,
            'areas'          => AreaResource::collection($this->whenLoaded('areas')),
            'carteras'       => $this->carteras ? $this->carteras->pluck('nombre_cartera') : [],
        ];
    }
}