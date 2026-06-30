<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index() {
        return TicketResource::collection(Ticket::with(['usuarioReportante', 'activo', 'tipoCaso'])->where('estado', true)->get());
    }

    public function store(Request $request) {
        $request->validate(['asunto' => 'required', 'tipoCasoId' => 'required']);
        return new TicketResource(Ticket::create($request->all()));
    }

    public function show($id) {
        return new TicketResource(Ticket::with(['usuarioReportante', 'activo', 'tipoCaso'])->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());
        return new TicketResource($ticket);
    }

    public function destroy($id) {
        Ticket::findOrFail($id)->update(['estado' => false]);
        return response()->json(['message' => 'Ticket anulado'], 200);
    }
}