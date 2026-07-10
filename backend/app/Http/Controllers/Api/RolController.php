<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // Listar roles activos
    public function index()
    {
       return Role::where('estado', true)
                  ->orderBy('id', 'asc')
                  ->paginate(5);
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $rol = Role::create($validated);

        return response()->json([
            'message' => 'Rol registrado correctamente.',
            'data' => $rol
        ], 201);
    }

    // Obtener un rol específico
    public function show($id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado.'], 404);
        }

        return response()->json($rol);
    }

    // Actualizar rol
    public function update(Request $request, $id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado.'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre,' . $id,
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'boolean'
        ]);

        $rol->update($validated);

        return response()->json([
            'message' => 'Rol actualizado correctamente.',
            'data' => $rol
        ]);
    }

    // Eliminación lógica (desactivar)
    public function destroy($id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado.'], 404);
        }

        $rol->update(['estado' => false]);

        return response()->json(['message' => 'Rol desactivado correctamente.']);
    }
}