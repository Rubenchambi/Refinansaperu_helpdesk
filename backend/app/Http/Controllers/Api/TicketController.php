<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketBitacora;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('estado', true);

        // Lógica de "Cola": Soporte primero, luego general
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
            'prioridad'           => 'required|integer', // 1, 2, 3
            'tipo_cola'           => 'required|string',
        ]);

        // Usamos transacciones para asegurar que el Ticket y la Bitácora se guarden juntos
        return DB::transaction(function () use ($validated, $request) {
            $ticket = Ticket::create($validated + [
                'estadoTicket' => 'pendiente',
                // Generación de código: YYYYMMDD-HHMMSS-ID
                'codigo_ticket' => date('Ymd') . '-' . date('His') . '-' . (Ticket::max('id') + 1)
            ]);

            // Creamos el primer registro en la bitácora automáticamente
            TicketBitacora::create([
                'ticketId'       => $ticket->id,
                'usuarioId'      => $request->usuarioReportanteId,
                'accion'         => 'creacion',
                'comentario'     => 'Ticket creado exitosamente.',
                'estadoNuevo'    => 'pendiente'
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

            // Si cambió el estado, registramos en bitácora
            if ($request->has('estadoTicket') && $estadoAnterior !== $request->estadoTicket) {
                TicketBitacora::create([
                    'ticketId' => $ticket->id,
                    'usuarioId' => auth()->id(), // El técnico que hizo el cambio
                    'accion' => 'cambio_estado',
                    'estadoAnterior' => $estadoAnterior,
                    'estadoNuevo' => $request->estadoTicket
                ]);
            }
            
            return response()->json(['message' => 'Ticket actualizado.', 'data' => new TicketResource($ticket)]);
        });
    }

    public function show($id)
    {
        // Cargamos la relación de bitácoras para que el Resource pueda mostrarlas
        $ticket = Ticket::with(['bitacoras'])->findOrFail($id);
        return new TicketResource($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Marcamos como inactivo/eliminado
        $ticket->update(['estado' => false]);

        // Opcional: Registramos en la bitácora que se eliminó
        TicketBitacora::create([
            'ticketId' => $ticket->id,
            'usuarioId' => auth()->id(),
            'accion' => 'eliminacion_logica',
            'comentario' => 'El ticket fue eliminado del sistema.'
        ]);

        return response()->json(['message' => 'Ticket eliminado correctamente.']);
    }
}