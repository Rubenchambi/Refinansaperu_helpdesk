<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ProductoController, 
    TicketController, 
    ActivoController, 
    UsuarioController, 
    TicketBitacoraController, 
    SolicitudCompraController,
    ClienteController,
    TipoCasoController,
    AreaController,
    RolController,
    CarteraController,
    AuthController // No olvides importar tu AuthController
};

// 1. Ruta pública para loguearse
Route::post('/login', [AuthController::class, 'login']);

// 2. Rutas protegidas: Solo acceden quienes envíen un token válido
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/tickets/{id}/bitacoras', [TicketController::class, 'storeBitacora']);
    Route::put('/tickets/{id}/estado', [TicketController::class, 'updateEstado']);
    Route::put('/tickets/{id}/asignar', [TicketController::class, 'asignarTecnico']); // <-- Esta es la nueva
    Route::get('/tecnicos', [UsuarioController::class, 'getTecnicos']);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('activos', ActivoController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('bitacoras', TicketBitacoraController::class);
    Route::apiResource('solicitudes', SolicitudCompraController::class);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('tipos-casos', TipoCasoController::class);
    Route::apiResource('areas',AreaController::class);
    Route::apiResource('roles', RolController::class);
    Route::apiResource('carteras',CarteraController::class);
    //Route::apiResource('ticket',TicketController::class);
    
});