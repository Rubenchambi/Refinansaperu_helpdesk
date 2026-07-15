<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Models\TicketBitacora;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('estado', true);

        return TicketResource::collection($query->orderByRaw("CASE 
                WHEN tipo_cola = 'soporte' THEN 1 
                WHEN tipo_cola = 'general' THEN 2 
                ELSE 3 
                END ASC")
            ->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuarioReportanteId' => 'required|exists:usuarios,id',
            'tipoCasoId'          => 'required|exists:tipo_casos,id',
            'asunto'              => 'required|string|max:255',
            'descripcion'         => 'required|string',
            'prioridad'           => 'required|integer',
            'tipo_cola'           => 'required|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $ticket = Ticket::create($validated + [
                'estadoTicket'  => 'Pendiente',
                'codigo_ticket' => date('Ymd') . '-' . date('His') . '-' . (Ticket::max('id') + 1)
            ]);

            TicketBitacora::create([
                'ticketId'     => $ticket->id,
                'usuarioId'    => $request->usuarioReportanteId,
                'accion'       => 'creacion',
                'comentario'   => 'Ticket creado exitosamente.',
                'estadoNuevo'  => 'Pendiente'
            ]);

            return response()->json(['message' => 'Ticket generado.', 'data' => new TicketResource($ticket)], 201);
        });
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        return DB::transaction(function () use ($request, $ticket) {
            $estadoAnterior = $ticket->estadoTicket;
            $ticket->update($request->only(['estadoTicket', 'tecnicoAsignadoId', 'prioridad']));

            if ($request->has('estadoTicket') && $estadoAnterior !== $request->estadoTicket) {
                TicketBitacora::create([
                    'ticketId'       => $ticket->id,
                    'usuarioId'      => auth()->id(),
                    'accion'         => 'cambio_estado',
                    'estadoAnterior' => $estadoAnterior,
                    'estadoNuevo'    => $request->estadoTicket
                ]);
            }
            
            return response()->json(['message' => 'Ticket actualizado.', 'data' => new TicketResource($ticket)]);
        });
    }

    public function show($id)
    {
        $ticket = Ticket::with(['bitacoras.usuario'])->findOrFail($id);
        return new TicketResource($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['estado' => false]);

        TicketBitacora::create([
            'ticketId'   => $ticket->id,
            'usuarioId'  => auth()->id(),
            'accion'     => 'eliminacion_logica',
            'comentario' => 'El ticket fue eliminado del sistema.'
        ]);

        return response()->json(['message' => 'Ticket eliminado correctamente.']);
    }

public function storeBitacora(Request $request, $id) 
    {
        $request->validate(['mensaje' => 'required|string|max:1000']);
        
        $ticket = Ticket::findOrFail($id);
        $usuario = auth()->user();
        $usuario->load('role');

        // Validar permisos de rol
        if (!$usuario->role || !in_array($usuario->role->id, [1, 2])) {
            return response()->json(['message' => 'No tienes permisos para esta acción.'], 403);
        }

        // Restricción: No añadir notas a tickets cerrados
        if (in_array($ticket->estadoTicket, ['Resuelto', 'Cerrado'])) {
            return response()->json(['message' => 'No se pueden añadir notas a un ticket cerrado.'], 403);
        }

        $ticket->bitacoras()->create([
            'ticketId'   => $ticket->id,
            'usuarioId'  => $usuario->id,
            'accion'     => 'comentario',
            'comentario' => $request->mensaje, 
        ]);
        
        return response()->json(['message' => 'Nota agregada con éxito']);
    }

    public function updateEstado(Request $request, $id) 
    {
        $request->validate([
            'estadoTicket' => 'required|string|in:Pendiente,En Proceso,Resuelto'
        ]);
        
        $usuario = auth()->user();
        $usuario->load('role');

        // Validar permisos de rol (Misma seguridad que en storeBitacora)
        if (!$usuario->role || !in_array($usuario->role->id, [1, 2])) {
            return response()->json(['message' => 'No tienes permisos para cambiar estados.'], 403);
        }
        
        $ticket = Ticket::findOrFail($id);
        $estadoAnterior = $ticket->estadoTicket;
        $nuevoEstado = $request->estadoTicket;
        $fechaFinalizacion = ($nuevoEstado === 'Resuelto') ? now() : null;
        // Actualizar ticket
        $ticket->update([
        'estadoTicket'  => $nuevoEstado,
        'finalizado_at' => $fechaFinalizacion 
        ]);
   
        
        // Registrar en bitácora
        TicketBitacora::create([
            'ticketId'       => $ticket->id,
            'usuarioId'      => $usuario->id,
            'accion'         => 'cambio_estado',
            'comentario'     => "Estado cambiado de $estadoAnterior a {$request->estadoTicket}",
            'estadoAnterior' => $estadoAnterior,
            'estadoNuevo'    => $request->estadoTicket
        ]);
        
        return response()->json(['message' => 'Estado actualizado correctamente']);
    }

    public function asignarTecnico(Request $request, $id) 
    {
        // 1. Validar la existencia del técnico
        $request->validate([
            'tecnicoAsignadoId' => 'required|exists:usuarios,id'
        ]);

        // 2. Buscar el ticket y el usuario
        $ticket = Ticket::findOrFail($id);
        $tecnico = Usuario::findOrFail($request->tecnicoAsignadoId);

        // 3. Actualizar el ticket
        // Nota: Si en tu tabla 'tickets' tienes una columna 'nombre_tecnico',
        // puedes incluirla aquí para tener el nombre guardado directamente.
        $ticket->update([
            'tecnicoAsignadoId' => $tecnico->id,
            'asignado_at'       => now(),
            'estadoTicket'      => 'En Proceso'
        ]);

        // 4. Registro en Bitácora
        TicketBitacora::create([
            'ticketId'   => $ticket->id,
            'usuarioId'  => auth()->id(), // El ID del Admin que hace la asignación
            'accion'     => 'asignacion',
            'comentario' => "Ticket asignado al técnico: " . $tecnico->nombre,
        ]);

        return response()->json([
            'message' => 'Asignado a ' . $tecnico->nombre,
            'tecnico' => $tecnico->nombre // Esto ayuda al frontend a actualizarse sin recargar
        ]);
    }
}