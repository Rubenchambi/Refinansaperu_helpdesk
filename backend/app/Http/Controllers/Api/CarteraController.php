<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cartera;
use Illuminate\Http\Request;

class CarteraController extends Controller
{
    // Listar carteras activas
    public function index()
    {
        return Cartera::where('estado', true)
                        ->orderBy('id', 'asc')
                        ->paginate(5);
    }

    // Crear una nueva cartera
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_cartera' => 'required|string|max:255|unique:carteras,nombre_cartera',
            'descripcion'    => 'nullable|string|max:255',
        ]);

        $cartera = Cartera::create($validated);

        return response()->json([
            'message' => 'Cartera registrada correctamente.',
            'data' => $cartera
        ], 201);
    }

    // Obtener una cartera específica
    public function show($id)
    {
        $cartera = Cartera::find($id);

        if (!$cartera) {
            return response()->json(['message' => 'Cartera no encontrada.'], 404);
        }

        return response()->json($cartera);
    }

    // Actualizar cartera
    public function update(Request $request, $id)
    {
        $cartera = Cartera::find($id);

        if (!$cartera) {
            return response()->json(['message' => 'Cartera no encontrada.'], 404);
        }

        $validated = $request->validate([
            'nombre_cartera' => 'required|string|max:255|unique:carteras,nombre_cartera,' . $id,
            'descripcion'    => 'nullable|string|max:255',
            'estado'         => 'boolean'
        ]);

        $cartera->update($validated);

        return response()->json([
            'message' => 'Cartera actualizada correctamente.',
            'data' => $cartera
        ]);
    }

    // Eliminación lógica
    public function destroy($id)
    {
        $cartera = Cartera::find($id);

        if (!$cartera) {
            return response()->json(['message' => 'Cartera no encontrada.'], 404);
        }

        $cartera->update(['estado' => false]);

        return response()->json(['message' => 'Cartera desactivada correctamente.']);
    }
}