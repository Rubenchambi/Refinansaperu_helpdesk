<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // 1. Listar clientes
public function index(Request $request)
    {
        return Cliente::where('estado', true)
            ->orderBy('id', 'desc')
            ->paginate(10); // 10 registros por página
    }

    // 2. Crear nuevo cliente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'RazonSocial'      => 'nullable|string|max:255',
            'ruc'              => 'required|string|max:20',
            'contacto'         => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'direccion'        => 'nullable|string',
            'distrito'         => 'nullable|string',
            'ciudad'           => 'nullable|string',
            'cargo'            => 'required|string',
            'cartera_asignada' => 'nullable|string',
        ]);

        try {
            $cliente = Cliente::create($validated);
            return response()->json([
                'message' => 'Asesor registrado correctamente.',
                'data' => $cliente
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al registrar el asesor.'], 500);
        }
    }

    // 3. Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Asesor no encontrado.'], 404);
        }

        $validated = $request->validate([
            'RazonSocial'      => 'nullable|string',
            'ruc'              => 'sometimes|required|string',
            'contacto'         => 'sometimes|required|string',
            'telefono'         => 'sometimes|required|string',
            'direccion'        => 'nullable|string',
            'distrito'         => 'nullable|string',
            'ciudad'           => 'nullable|string',
            'cargo'            => 'sometimes|required|string',
            'cartera_asignada' => 'nullable|string',
        ]);

        $cliente->update($validated);

        return response()->json([
            'message' => 'Asesor actualizado correctamente.',
            'data' => $cliente
        ]);
    }

// 4. Eliminación Lógica (Desactivar cliente)
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Asesor no encontrado.'], 404);
        }

        // En lugar de eliminar, cambiamos el estado a false (0 en la BD)
        $cliente->update(['estado' => false]);

        return response()->json([
            'message' => 'Asesor desactivado correctamente.'
        ]);
    }
}