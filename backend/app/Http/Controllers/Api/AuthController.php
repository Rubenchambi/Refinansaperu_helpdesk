<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Buscamos usuario
        $usuario = Usuario::with('rol')->where('email', $request->email)->first();

        // 3. Verificación de bloqueo
        if ($usuario && $usuario->bloqueado_hasta && now()->lessThan($usuario->bloqueado_hasta)) {
            $minutos = now()->diffInMinutes($usuario->bloqueado_hasta);
            return response()->json([
                'message' => "Usuario bloqueado. Intenta nuevamente en {$minutos} minutos."
            ], 403);
        }

        // 4. Verificación de credenciales
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            
            if ($usuario) {
                $usuario->increment('intentos_fallidos');
                
                // Bloqueo tras 3 intentos
                if ($usuario->intentos_fallidos >= 3) {
                    $usuario->update(['bloqueado_hasta' => now()->addMinutes(15)]);
                    return response()->json([
                        'message' => 'Demasiados intentos. Usuario bloqueado por 15 minutos.', 
                        'intentos' => $usuario->intentos_fallidos
                    ], 403);
                }

                // Fallo normal
                return response()->json([
                    'message' => 'Credenciales incorrectas.',
                    'intentos' => $usuario->intentos_fallidos
                ], 401);
            }
            
            // Si el usuario ni siquiera existe
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        // 5. Login exitoso: Reset de intentos
        $usuario->update([
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null
        ]);

        // 6. Respuesta final
        return response()->json([
            'token' => $usuario->createToken('auth_token')->plainTextToken,
            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $usuario->rol ? [
                    'id' => $usuario->rol->id,
                    'nombre' => $usuario->rol->nombre,
                ] : null,
            ]
        ]);
    }
}