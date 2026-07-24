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
    $query = Cliente::where('estado', true);
    if ($request->filled('contacto')) {
        $contacto = trim($request->contacto);
        $query->where('contacto', 'ilike', '%' . $contacto . '%');
    }
    if ($request->filled('ruc')) {
        $query->where('ruc', 'like', '%' . $request->ruc . '%');
    }
    if ($request->filled('cargo')) {
        $query->where('cargo', $request->cargo);
    }

    return $query->orderBy('id', 'asc')->paginate(5);
    }

    // 2. Crear nuevo cliente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'razonSocial'      => 'nullable|string|max:255',
            'ruc'              => 'required|string|max:20',
            'contacto'         => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'direccion'        => 'nullable|string',
            'distrito'         => 'nullable|string',
            'ciudad'           => 'nullable|string',
            'cargo'            => 'required|string',
            'cartera_asignada' => 'nullable|string',
            'email'            => 'nullable|email|max:255',
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

    // 1.5. Obtener un cliente específico (¡Este faltaba!)
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Asesor no encontrado.'], 404);
        }

        return response()->json($cliente);
    }

    // 3. Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Asesor no encontrado.'], 404);
        }

        $validated = $request->validate([
            'razonSocial'      => 'nullable|string',
            'ruc'              => 'sometimes|required|string',
            'contacto'         => 'sometimes|required|string',
            'telefono'         => 'sometimes|required|string',
            'direccion'        => 'nullable|string',
            'distrito'         => 'nullable|string',
            'ciudad'           => 'nullable|string',
            'cargo'            => 'sometimes|required|string',
            'cartera_asignada' => 'nullable|string',
            'email'            => 'nullable|email',
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
            'message' => 'Asesor Eliminado correctamente.'
        ]);
    }
}