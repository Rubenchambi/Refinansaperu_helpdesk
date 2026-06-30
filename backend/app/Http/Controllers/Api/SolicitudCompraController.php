<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SolicitudCompra;
use App\Http\Resources\SolicitudCompraResource; // Asegúrate de crear este Resource
use Illuminate\Http\Request;

class SolicitudCompraController extends Controller
{
    public function index() {
        return SolicitudCompraResource::collection(SolicitudCompra::where('estado', true)->get());
    }

    public function store(Request $request) {
        return new SolicitudCompraResource(SolicitudCompra::create($request->all()));
    }

    public function show($id) {
        return new SolicitudCompraResource(SolicitudCompra::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $solicitud = SolicitudCompra::findOrFail($id);
        $solicitud->update($request->all());
        return new SolicitudCompraResource($solicitud);
    }

    public function destroy($id) {
        SolicitudCompra::findOrFail($id)->update(['estado' => false]);
        return response()->json(['message' => 'Solicitud de compra cancelada'], 200);
    }
}