<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    // Listar áreas activas (para el select del frontend)
    public function index()
    {
        // Traemos solo las activas y ordenadas por nombre
        return Area::where('estado', true)
                    ->orderBy('id', 'asc')
                    ->paginate(5);
    }

    // Crear una nueva área
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre',
        ]);

        $area = Area::create($validated);

        return response()->json([
            'message' => 'Área registrada correctamente.',
            'data' => $area
        ], 201);
    }

        // Obtener una área específica (Para edición o detalle)
    public function show($id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }

        return response()->json($area);
    }
    // Actualizar área
    public function update(Request $request, $id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre,' . $id,
            'estado' => 'boolean'
        ]);

        $area->update($validated);

        return response()->json([
            'message' => 'Área actualizada correctamente.',
            'data' => $area
        ]);
    }

    // Eliminación lógica
    public function destroy($id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }

        $area->update(['estado' => false]);

        return response()->json(['message' => 'Área desactivada correctamente.']);
    }
}