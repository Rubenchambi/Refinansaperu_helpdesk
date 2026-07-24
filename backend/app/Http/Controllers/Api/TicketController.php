<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Models\TicketBitacora;
use App\Models\TicketArchivo; // <-- Importante para guardar los adjuntos
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['tecnico'])
                     ->where('estado', true);

        if ($request->filled('busqueda')) {
            $search = trim($request->busqueda);
            $searchLower = strtolower($search);

            // Diccionario para mapear texto a los números de prioridad
            $prioridadesMap = [
                'urgente' => 1,
                'alta'    => 2,
                'media'   => 3,
                'baja'    => 4,
            ];

            // Buscamos si lo que escribió coincide con alguna prioridad
            $prioridadEncontrada = null;
            foreach ($prioridadesMap as $texto => $valor) {
                if (str_contains($texto, $searchLower)) {
                    $prioridadEncontrada = $valor;
                    break;
                }
            }

            $query->where(function($q) use ($search, $searchLower, $prioridadEncontrada) {
                $q->orWhere('codigo_ticket', 'like', '%' . $search . '%')
                  ->orWhere('asunto', 'ilike', '%' . $search . '%')
                  ->orWhere('estadoTicket', 'ilike', '%' . $search . '%');

                if ($prioridadEncontrada !== null) {
                    $q->orWhere('prioridad', $prioridadEncontrada);
                }
                $q->orWhere('prioridad', 'like', '%' . $search . '%');
                $q->orWhereHas('tecnico', function($subQuery) use ($search) {
                      $subQuery->where('nombre', 'ilike', '%' . $search . '%');
                  });
            });
        }

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
            'imagen'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de foto
            'documentos.*'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120', // Validación de adjuntos
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $ticket = Ticket::create($validated + [
                'estadoTicket'  => 'Pendiente',
                'codigo_ticket' => date('Ymd') . '-' . date('His') . '-' . (Ticket::max('id') + 1)
            ]);

            // Guardar la imagen si viene en el request
            if ($request->hasFile('imagen')) {
                $rutaImagen = $request->file('imagen')->store('tickets/fotos', 'public');
                $ticket->update(['imagen' => $rutaImagen]);
            }

            // Guardar los documentos múltiples si vienen en el request
            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $archivo) {
                    $rutaArchivo = $archivo->store('tickets/documentos', 'public');

                    TicketArchivo::create([
                        'ticket_id'       => $ticket->id,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'ruta_archivo'    => $rutaArchivo,
                        'extension'       => $archivo->getClientOriginalExtension(),
                    ]);
                }
            }

            TicketBitacora::create([
                'ticketId'    => $ticket->id,
                'usuarioId'   => $request->usuarioReportanteId,
                'accion'      => 'creacion',
                'comentario'  => 'Ticket creado exitosamente.',
                'estadoNuevo' => 'Pendiente'
            ]);

            return response()->json(['message' => 'Ticket generado.', 'data' => new TicketResource($ticket->load('archivos'))], 201);
        });
    }

public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'usuarioReportanteId' => 'sometimes|exists:usuarios,id',
            'tipoCasoId'          => 'sometimes|exists:tipo_casos,id',
            'asunto'              => 'sometimes|string|max:255',
            'descripcion'         => 'sometimes|string',
            'prioridad'           => 'sometimes|integer',
            'tipo_cola'           => 'sometimes|string',
            'estadoTicket'        => 'sometimes|string',
            'tecnicoAsignadoId'   => 'nullable|exists:usuarios,id',
        ]);

        return DB::transaction(function () use ($request, $ticket) {
            $estadoAnterior = $ticket->estadoTicket;

            // Actualizamos los campos que vengan en la petición JSON
            $ticket->update($request->only([
                'usuarioReportanteId', 
                'tipoCasoId', 
                'asunto', 
                'descripcion', 
                'prioridad', 
                'tipo_cola', 
                'estadoTicket', 
                'tecnicoAsignadoId'
            ]));

            // Si cambió el estado, registramos la bitácora
            if ($request->has('estadoTicket') && $estadoAnterior !== $request->estadoTicket) {
                TicketBitacora::create([
                    'ticketId'       => $ticket->id,
                    'usuarioId'      => auth()->id(),
                    'accion'         => 'cambio_estado',
                    'estadoAnterior' => $estadoAnterior,
                    'estadoNuevo'    => $request->estadoTicket
                ]);
            }
            
            return response()->json([
                'message' => 'Ticket actualizado correctamente.', 
                'data'    => new TicketResource($ticket->load('archivos'))
            ]);
        });
    }
    
    public function updateConArchivos(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        return DB::transaction(function () use ($request, $ticket) {
            $estadoAnterior = $ticket->estadoTicket;

            // 1. Actualizamos los textos de forma directa
            $ticket->update([
                'usuarioReportanteId' => $request->input('usuarioReportanteId'),
                'tipoCasoId'          => $request->input('tipoCasoId'),
                'asunto'              => $request->input('asunto'),
                'descripcion'         => $request->input('descripcion'),
                'prioridad'           => $request->input('prioridad'),
                'tipo_cola'           => $request->input('tipo_cola'),
            ]);

            // 2. Si viene una imagen nueva, la guardamos y actualizamos la columna
            if ($request->hasFile('imagen')) {
                $rutaImagen = $request->file('imagen')->store('tickets/fotos', 'public');
                $ticket->update(['imagen' => $rutaImagen]);
            }

            // 3. Guardar documentos nuevos en la tabla tickets_archivos
            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $archivo) {
                    $rutaArchivo = $archivo->store('tickets/documentos', 'public');

                    TicketArchivo::create([
                        'ticket_id'       => $ticket->id,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'ruta_archivo'    => $rutaArchivo,
                        'extension'       => $archivo->getClientOriginalExtension(),
                    ]);
                }
            }

            // 4. Bitácora si cambia el estado
            if ($request->has('estadoTicket') && $estadoAnterior !== $request->estadoTicket) {
                $ticket->update(['estadoTicket' => $request->estadoTicket]);
                
                TicketBitacora::create([
                    'ticketId'       => $ticket->id,
                    'usuarioId'      => auth()->id(),
                    'accion'         => 'cambio_estado',
                    'estadoAnterior' => $estadoAnterior,
                    'estadoNuevo'    => $request->estadoTicket
                ]);
            }
            
            return response()->json([
                'message' => 'Ticket actualizado correctamente.', 
                'data'    => $ticket->fresh('archivos')
            ]);
        });
    }


    public function show($id)
    {
        $ticket = Ticket::with(['bitacoras.usuario', 'archivos'])->findOrFail($id);
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

        if (!$usuario->role || !in_array($usuario->role->id, [1, 2])) {
            return response()->json(['message' => 'No tienes permisos para esta acción.'], 403);
        }

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
            'estadoTicket' => 'required|string|in:Pendiente,En Proceso,Resuelto,Finalizado'
        ]);
        
        $ticket = Ticket::findOrFail($id);

        if ($ticket->estadoTicket === 'Finalizado') {
            return response()->json(['message' => 'No se puede modificar un ticket ya finalizado.'], 403);
        }
        
        $usuario = auth()->user();
        $usuario->load('role');

        if (!$usuario->role || !in_array($usuario->role->id, [1, 2])) {
            return response()->json(['message' => 'No tienes permisos.'], 403);
        }
        
        $estadoAnterior = $ticket->estadoTicket;
        $nuevoEstado = $request->estadoTicket;
        
        $fechaFinalizacion = ($nuevoEstado === 'Finalizado') ? now() : null;

        $ticket->update([
            'estadoTicket'  => $nuevoEstado,
            'finalizado_at' => $fechaFinalizacion 
        ]);
        
        TicketBitacora::create([
            'ticketId'       => $ticket->id,
            'usuarioId'      => $usuario->id,
            'accion'         => 'cambio_estado',
            'comentario'     => "Estado cambiado de $estadoAnterior a $nuevoEstado",
            'estadoAnterior' => $estadoAnterior,
            'estadoNuevo'    => $nuevoEstado
        ]);
        
        return response()->json(['message' => 'Estado actualizado correctamente']);
    }

    public function asignarTecnico(Request $request, $id) 
    {
        $request->validate([
            'tecnicoAsignadoId' => 'required|exists:usuarios,id'
        ]);

        $ticket = Ticket::findOrFail($id);
        $tecnico = Usuario::findOrFail($request->tecnicoAsignadoId);

        $ticket->update([
            'tecnicoAsignadoId' => $tecnico->id,
            'asignado_at'       => now(),
            'estadoTicket'      => 'En Proceso'
        ]);

        TicketBitacora::create([
            'ticketId'   => $ticket->id,
            'usuarioId'  => auth()->id(),
            'accion'     => 'asignacion',
            'comentario' => "Ticket asignado al técnico: " . $tecnico->nombre,
        ]);

        return response()->json([
            'message' => 'Asignado a ' . $tecnico->nombre,
            'tecnico' => $tecnico->nombre
        ]);
    }

    public function descargarArchivo(Request $request)
    {
        try {
            $ruta = $request->query('ruta');

            if (!$ruta) {
                return response()->json(['error' => 'Ruta no especificada'], 400);
            }

            // Limpieza de ruta
            if (str_contains($ruta, '/storage/')) {
                $ruta = explode('/storage/', $ruta)[1];
            }

            // Limpiar espacios o codificaciones raras que a veces mandan los navegadores
            $ruta = urldecode($ruta);

            // Verificamos si existe en el disco public
            if (!Storage::disk('public')->exists($ruta)) {
                return response()->json(['error' => 'El archivo no existe en el servidor: ' . $ruta], 404);
            }

            $nombreOriginal = $request->query('nombre') ?: basename($ruta);

            return Storage::disk('public')->download($ruta, $nombreOriginal);

        } catch (\Exception $e) {
            // Esto devolverá el error exacto a tu consola de Vue en lugar del genérico 500
            return response()->json([
                'error' => 'Error en el servidor: ' . $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    public function estadisticas()
    {
        $total = Ticket::count();
        $pendientes = Ticket::where('estadoTicket', 'Pendiente')->count();
        $enProceso = Ticket::where('estadoTicket', 'En Proceso')->count();
        $resuelto = Ticket::where('estadoTicket', 'Resuelto')->count();
        $finalizados = Ticket::where('estadoTicket', 'Finalizado')->count();

        return response()->json([
            'total' => $total,
            'pendientes' => $pendientes,
            'en_proceso' => $enProceso,
            'resuelto' => $resuelto,
            'finalizados' => $finalizados,
        ]);
    }
}