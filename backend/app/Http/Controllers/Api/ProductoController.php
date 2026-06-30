<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // 1. Listar todos
    public function index()
    {
        // Solo traemos los productos con estado true
        $productos = Producto::where('estado', true)->get();
        return ProductoResource::collection($productos);
    }

    // 2. Guardar nuevo
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
        ]);

        $producto = Producto::create($request->all());
        return new ProductoResource($producto);
    }

    // 3. Ver uno solo
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return new ProductoResource($producto);
    }

    // 4. Actualizar
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'marca' => 'sometimes|required|string|max:255',
        ]);

        $producto->update($request->all());
        return new ProductoResource($producto);
    }

    // 5. Eliminar (Eliminación Lógica)
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Cambiamos el estado a false en lugar de borrar el registro
        $producto->update(['estado' => false]);

        return response()->json(['message' => 'Producto dado de baja correctamente'], 200);
    }
}