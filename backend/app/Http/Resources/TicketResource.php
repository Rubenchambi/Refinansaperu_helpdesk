<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
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
                    'fecha_creacion' => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y H:i') : null,
                    'asignado_at' => $this->asignado_at ? Carbon::parse($this->asignado_at)->format('d/m/Y H:i') : null,
                    // Mantenemos solo los IDs que sí existen en tu tabla
                    'usuarioReportanteId' => $this->usuarioReportanteId, 
                    'tecnicoAsignadoId' => $this->tecnicoAsignadoId,
                    'tipoCasoId' => $this->tipoCasoId,
                    // Solo cargamos la bitácora si ya está configurada
                    'bitacoras' => TicketBitacoraResource::collection($this->whenLoaded('bitacoras')),
                    'usuario_nombre' => $this->reportante ? trim("{$this->reportante->nombre} {$this->reportante->apellidos}") : 'Sistema',
                    'nombre_tecnico' => $this->tecnico ? $this->tecnico->nombre : null,
                    'imagen' => $this->imagen ? asset('storage/' . $this->imagen) : null,
                    'archivos' => $this->whenLoaded('archivos', function () {
                                return $this->archivos->map(function ($archivo) {
                                    return [
                                        'id' => $archivo->id,
                                        'nombre_original' => $archivo->nombre_original,
                                        'url' => asset('storage/' . $archivo->ruta_archivo),
                                        'extension' => $archivo->extension,
                                    ];
                                });
                            }),
                ];
    }
}