<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nombreCompleto' => $this->nombre . ' ' . $this->apellidos,
            'email' => $this->email,
            'area' => $this->area,
            'rol' => $this->rol->nombre,
        ];
    }


}