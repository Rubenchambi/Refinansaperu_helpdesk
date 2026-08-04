<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // 1. Listar con paginación y búsqueda
    public function index(Request $request)
    {
        $query = Producto::where('estado', true)->withCount('activos');

        // Filtro de búsqueda general (por nombre, marca o categoría)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'ilike', "%{$busqueda}%")
                  ->orWhere('marca', 'ilike', "%{$busqueda}%")
                  ->orWhere('categoria', 'ilike', "%{$busqueda}%");
            });
        }

        // Paginación (por defecto 10 o lo que mande el frontend)
        $perPage = $request->get('per_page', 10);
        $productos = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return ProductoResource::collection($productos);
    }

    // 2. Guardar nuevo
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'stockMinimo' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $producto = Producto::create($request->all());
        return new ProductoResource($producto);
    }

    // 3. Ver uno solo
    public function show($id)
    {
        $producto = Producto::with('activos')->findOrFail($id);
        return new ProductoResource($producto);
    }

    // 4. Actualizar
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'marca' => 'sometimes|required|string|max:255',
            'categoria' => 'nullable|string|max:100',
            'stockMinimo' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
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

        return response()->json([
            'success' => true,
            'message' => 'Producto dado de baja correctamente'
        ], 200);
    }
}