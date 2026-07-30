<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Ticket;

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
        $usuario = Usuario::with('role')->where('email', $request->email)->first();
        // 2.1 revisar usuario si esta desactivado
        if ($usuario && !$usuario->estado) {
            return response()->json(['message' => 'Tu cuenta ha sido desactivada. Contacta al administrador.'], 403);
        }

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
                'dni' => $usuario->dni,
                'email' => $usuario->email,
                'created_at' => $usuario->created_at,
                'rol' => $usuario->role ? [
                    'id' => $usuario->role->id,
                    'nombre' => $usuario->role->nombre,
                ] : null,
            ]
        ]);
    }

    public function changePassword(Request $request)
        {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min:6',
            ]);

            $usuario = $request->user();

            // Verificación real y segura con Hash::check
            if (!Hash::check($request->current_password, $usuario->password)) {
                return response()->json([
                    'message' => 'La contraseña actual es incorrecta.'
                ], 422);
            }

            // Guardamos la nueva contraseña encriptada de verdad
            $usuario->password = Hash::make($request->new_password);
            $usuario->save();

            return response()->json([
                'message' => 'Contraseña actualizada correctamente'
            ], 200);
        }

        public function estadisticas(Request $request)
        {
            // 1. Obtenemos el usuario autenticado y su rol
            $user = $request->user();
            $rolId = $user->rolId ?? ($user->role->id ?? null);

            // 2. Creamos la consulta base aplicando las MISMAS reglas del index
            $query = Ticket::where('estado', true);

            if ($rolId === 5) {
                // SUPERVISOR (ID: 5): Estadísticas de los tickets de sus carteras asignadas
                $carterasIds = $user->carteras()->pluck('carteras.id'); 
                
                $query->whereHas('reportante', function($q) use ($carterasIds) {
                    $q->whereHas('carteras', function($subQ) use ($carterasIds) {
                        $subQ->whereIn('carteras.id', $carterasIds);
                    });
                });

            } elseif ($rolId === 2) {
                // TÉCNICO (ID: 2): Estadísticas de los tickets asignados a él
                $query->where('tecnicoAsignadoId', $user->id); 

            } elseif ($rolId === 3 || $rolId === 4) {
                // ASESORES (Call ID: 3, Campo ID: 4): Estadísticas solo de los tickets que reportaron
                $query->where('usuarioReportanteId', $user->id); 
            }
            // Si es Admin ($rolId === 1), cuenta todo sin restricciones.

            // 3. Calculamos usando clones de la consulta filtrada por rol
            $ticketsCreados = (clone $query)->where('usuarioReportanteId', $user->id)->count();
            $ticketsAsignados = (clone $query)->where('tecnicoAsignadoId', $user->id)->count();
            $ticketsCerrados = (clone $query)->whereIn('estadoTicket', ['Resuelto', 'Finalizado'])->count();

            return response()->json([
                'ticketsCreados' => $ticketsCreados,
                'ticketsAsignados' => $ticketsAsignados,
                'ticketsCerrados' => $ticketsCerrados,
            ]);
        }

}