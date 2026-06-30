<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activo;
use App\Http\Resources\ActivoResource;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    // Listar activos cargando su relación con Producto
    public function index() {
        return ActivoResource::collection(Activo::with('producto')->where('estado', true)->get());
    }

    // Crear nuevo activo
    public function store(Request $request) {
        $request->validate(['productoId' => 'required', 'codigoPatrimonial' => 'required']);
        return new ActivoResource(Activo::create($request->all()));
    }

    // Ver detalle
    public function show($id) {
        return new ActivoResource(Activo::with('producto')->findOrFail($id));
    }

    // Actualizar activo
    public function update(Request $request, $id) {
        $activo = Activo::findOrFail($id);
        $activo->update($request->all());
        return new ActivoResource($activo);
    }

    // Eliminación lógica
    public function destroy($id) {
        Activo::findOrFail($id)->update(['estado' => false]);
        return response()->json(['message' => 'Activo dado de baja correctamente'], 200);
    }
}