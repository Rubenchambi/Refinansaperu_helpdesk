<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoCaso; // Asegúrate de tener tu modelo TipoCaso
use App\Http\Resources\TipoCasoResource;
use Illuminate\Http\Request;

class TipoCasoController extends Controller
{
    // 1. Listar tipos de casos (con filtros opcionales)
    public function index(Request $request)
    {
        $query = TipoCaso::where('estado', true);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . trim($request->nombre) . '%');
        }
        if ($request->has('all')) {
            return TipoCasoResource::collection($query->orderBy('id', 'asc')->get());
        }

        // Ordenamos por prioridad o nombre para mejor vista en el frontend
        return $query->orderBy('prioridadBase', 'asc')->paginate(5);
    }

    // 2. Crear nuevo tipo de caso
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:255|unique:tipo_casos,nombre',
            'prioridadBase' => 'required|string|min:1|max:50',
            'descripcion'   => 'nullable|string|max:500',
        ]);

        try {
            // Estado por defecto true al crear
            $validated['estado'] = true;
            $tipoCaso = TipoCaso::create($validated);
            
            return response()->json([
                'message' => 'Tipo de caso registrado correctamente.',
                'data' => $tipoCaso
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al registrar el tipo de caso.'], 500);
        }
    }

    // 3. Obtener un tipo de caso específico
    public function show($id)
    {
        $tipoCaso = TipoCaso::find($id);

        if (!$tipoCaso || !$tipoCaso->estado) {
            return response()->json(['message' => 'Tipo de caso no encontrado.'], 404);
        }

        return response()->json($tipoCaso);
    }

    // 4. Actualizar tipo de caso
    public function update(Request $request, $id)
    {
        $tipoCaso = TipoCaso::find($id);

        if (!$tipoCaso) {
            return response()->json(['message' => 'Tipo de caso no encontrado.'], 404);
        }

        $validated = $request->validate([
            'nombre'        => 'sometimes|required|string|max:255|unique:tipo_casos,nombre,' . $id,
            'prioridadBase' => 'sometimes|required|string|min:1|max:50',
            'descripcion'   => 'nullable|string|max:500',
        ]);

        $tipoCaso->update($validated);

        return response()->json([
            'message' => 'Tipo de caso actualizado correctamente.',
            'data' => $tipoCaso
        ]);
    }

    // 5. Eliminación Lógica
    public function destroy($id)
    {
        $tipoCaso = TipoCaso::find($id);

        if (!$tipoCaso) {
            return response()->json(['message' => 'Tipo de caso no encontrado.'], 404);
        }

        $tipoCaso->update(['estado' => false]);

        return response()->json([
            'message' => 'Tipo de caso eliminado correctamente.'
        ]);
    }
}