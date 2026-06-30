<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Http\Resources\UsuarioResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listar solo usuarios activos
    public function index() {
        return UsuarioResource::collection(Usuario::where('estado', true)->get());
    }

    // Crear nuevo usuario
    public function store(Request $request) {
        $data = $request->all();
        // Aseguramos que la contraseña sea segura al guardar
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return new UsuarioResource(Usuario::create($data));
    }

    // Ver detalle
    public function show($id) {
        return new UsuarioResource(Usuario::findOrFail($id));
    }

    // Actualizar usuario
    public function update(Request $request, $id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        return new UsuarioResource($usuario);
    }

    // Eliminación lógica
    public function destroy($id) {
        Usuario::findOrFail($id)->update(['estado' => false]);
        return response()->json(['message' => 'Usuario desactivado correctamente'], 200);
    }
}