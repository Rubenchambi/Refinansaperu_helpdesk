<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Http\Resources\UsuarioResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listar (con filtros y paginación)
    public function index(Request $request)
    {
        $query = Usuario::where('estado', true)->with(['areas', 'role']);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('apellidos')) {
            $query->where('apellidos', 'like', '%' . $request->apellidos . '%');
        }
        
        return UsuarioResource::collection($query->orderBy('id', 'asc')->paginate(5));
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'     => 'required|string|max:255',
            'apellidos'  => 'required|string|max:255',
            'dni'        => 'required|string|max:20|unique:usuarios,dni',
            'email'      => 'required|email|unique:usuarios,email',
            'password'   => 'required|string|min:6',
            'rolId'      => 'required|exists:roles,id',
            'area_ids'   => 'required|array',
            'area_ids.*' => 'exists:areas,id',
        ]);

        // Separamos los datos del usuario de los IDs de áreas
        $datosUsuario = $validated;
        unset($datosUsuario['area_ids']);
        $datosUsuario['password'] = Hash::make($validated['password']);
        
        $usuario = Usuario::create($datosUsuario);

        // Guardamos la relación en la tabla pivote
        $usuario->areas()->sync($validated['area_ids']);
        $usuario->carteras()->sync($request->cartera_ids);

        return response()->json(['message' => 'Usuario registrado.', 'data' => new UsuarioResource($usuario)], 201);
    }

    // Ver detalle para editar
    public function show($id)
    {
        $usuario = Usuario::with(['areas', 'role', 'carteras'])->findOrFail($id);
        return new UsuarioResource($usuario);
    }

    // Actualizar
   public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);
    $validated = $request->validate([
        'nombre'     => 'sometimes|required|string',
        'apellidos'  => 'sometimes|required|string',
        'dni'        => 'sometimes|required|unique:usuarios,dni,' . $id,
        'email'      => 'sometimes|required|email|unique:usuarios,email,' . $id,
        'password'   => 'nullable|string|min:6',
        'rolId'      => 'sometimes|required|exists:roles,id',
        'area_ids'   => 'nullable|array', 
        'area_ids.*' => 'exists:areas,id',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = Hash::make($request->password);
    } else {
        unset($validated['password']);
    }

    if ($request->has('area_ids')) {
        $usuario->areas()->sync($request->area_ids);
    }
    
    if ($request->has('cartera_ids')) {
            $usuario->carteras()->sync($request->cartera_ids);
    }
    // 4. Limpiamos antes de actualizar el modelo principal
    unset($validated['area_ids']);
    unset($validated['cartera_ids']);
    // 5. Guardamos cambios
    $usuario->update($validated);

    return response()->json([
        'message' => 'Usuario actualizado.', 
        'data' => new UsuarioResource($usuario)
    ]);
}

    // Eliminación lógica
    public function destroy($id)
    {
        Usuario::findOrFail($id)->update(['estado' => false]);
        return response()->json(['message' => 'Usuario eliminado.']);
    }

    public function getTecnicos() {
    return response()->json(Usuario::where('rolId', 2)->get());
}
}