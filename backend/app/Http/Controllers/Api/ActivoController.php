<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activo;
use App\Http\Resources\ActivoResource;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    // 1. Listar activos con paginación, relaciones y búsqueda
    public function index(Request $request) 
    {
        $query = Activo::with(['producto', 'usuarioAsignado'])->where('estado', true);

        // Filtro de búsqueda (código patrimonial, número de serie, teléfono o estado físico)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('codigoPatrimonial', 'like', "%{$busqueda}%")
                  ->orWhere('numeroSerie', 'like', "%{$busqueda}%")
                  ->orWhere('numeroTelefono', 'like', "%{$busqueda}%")
                  ->orWhere('estadoFisico', 'like', "%{$busqueda}%");
            });
        }

        // Filtro adicional por categoría de producto o por estado físico si lo mandas desde Vue
        if ($request->has('estadoFisico') && !empty($request->estadoFisico)) {
            $query->where('estadoFisico', $request->estadoFisico);
        }

        $perPage = $request->get('per_page', 10);
        $activos = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return ActivoResource::collection($activos);
    }

    // 2. Crear nuevo activo (con validación de los campos nuevos)
    public function store(Request $request) 
    {
        $request->validate([
            'productoId' => 'required|exists:productos,id',
            'codigoPatrimonial' => 'required|string|unique:activos,codigoPatrimonial',
            'numeroSerie' => 'required|string',
            'estadoFisico' => 'required|string|in:operativo,averiado,reparacion',
            'usuarioAsignadoId' => 'nullable|exists:usuarios,id',
            'numeroTelefono' => 'nullable|string|max:20',
            'operador' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $activo = Activo::create($request->all());
        
        // Cargamos las relaciones para que el Resource devuelva los datos completos de inmediato
        $activo->load(['producto', 'usuarioAsignado']);

        return new ActivoResource($activo);
    }

    // 3. Ver detalle de un activo
    public function show($id) 
    {
        $activo = Activo::with(['producto', 'usuarioAsignado'])->findOrFail($id);
        return new ActivoResource($activo);
    }

    // 4. Actualizar activo
    public function update(Request $request, $id) 
    {
        $activo = Activo::findOrFail($id);
        
        $request->validate([
            'productoId' => 'sometimes|required|exists:productos,id',
            'codigoPatrimonial' => 'sometimes|required|string|unique:activos,codigoPatrimonial,' . $id,
            'numeroSerie' => 'sometimes|required|string',
            'estadoFisico' => 'sometimes|required|string|in:operativo,averiado,reparacion',
            'usuarioAsignadoId' => 'nullable|exists:usuarios,id',
            'numeroTelefono' => 'nullable|string|max:20',
            'operador' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $activo->update($request->all());
        $activo->load(['producto', 'usuarioAsignado']);

        return new ActivoResource($activo);
    }

    // 5. Eliminación lógica
    public function destroy($id) 
    {
        $activo = Activo::findOrFail($id);
        $activo->update(['estado' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Activo dado de baja correctamente'
        ], 200);
    }
}