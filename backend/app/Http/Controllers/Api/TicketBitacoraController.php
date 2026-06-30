<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketBitacora;
use App\Http\Resources\TicketBitacoraResource;
use Illuminate\Http\Request;

class TicketBitacoraController extends Controller
{
    public function index() {
        return TicketBitacoraResource::collection(TicketBitacora::with(['ticket', 'usuario'])->get());
    }

    public function store(Request $request) {
        $request->validate(['ticketId' => 'required', 'comentario' => 'required']);
        return new TicketBitacoraResource(TicketBitacora::create($request->all()));
    }

    public function show($id) {
        return new TicketBitacoraResource(TicketBitacora::with(['ticket', 'usuario'])->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $bitacora = TicketBitacora::findOrFail($id);
        $bitacora->update($request->all());
        return new TicketBitacoraResource($bitacora);
    }

    public function destroy($id) {
        // La bitácora suele ser inmutable (no se debe borrar/editar), 
        // pero por consistencia de tu arquitectura, aquí la tienes:
        $bitacora = TicketBitacora::findOrFail($id);
        $bitacora->delete();
        return response()->json(['message' => 'Entrada de bitácora eliminada'], 200);
    }
}