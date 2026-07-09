<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Usuario, Producto, Activo, Cliente, TipoCaso, Ticket, TicketBitacora, Area};
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Áreas (Importante crearlas primero para tener los IDs)
        $areas = ['Derrama', 'Pichincha', 'Judicial', 'Confianza', 'Hipotecario', 'Efectiva', 'Sistemas'];
        foreach ($areas as $nombre) {
            Area::updateOrCreate(['nombre' => $nombre], ['estado' => true]);
        }

        // 2. Roles
        Role::updateOrCreate(['nombre' => 'Admin'], ['nombre' => 'Admin']);
        Role::updateOrCreate(['nombre' => 'Soporte'], ['nombre' => 'Soporte']);
        Role::updateOrCreate(['nombre' => 'Asesor'], ['nombre' => 'Asesor']);

        // 3. Usuarios (Busca el área 'Sistemas' para asignar el ID)
        $areaSistemas = Area::where('nombre', 'Sistemas')->first();
        Usuario::updateOrCreate(
            ['email' => 'admin@refinansa.pe'],
            [
                'nombre' => 'Edwin Ruben',
                'apellidos' => 'Humpiri',
                'dni' => '12345678',
                'password' => bcrypt('password123'),
                'rolId' => 1,
                'area_id' => $areaSistemas->id
            ]
        );

        // 4. Productos
        Producto::updateOrCreate(['nombre' => 'Mouse Logitech M170'], ['marca' => 'Logitech', 'stockMinimo' => 5]);
        Producto::updateOrCreate(['nombre' => 'Monitor 24" HP'], ['marca' => 'HP', 'stockMinimo' => 3]);

        // 5. Activos
        Activo::updateOrCreate(
            ['codigoPatrimonial' => 'ACT-MOUSE-001'],
            [
                'productoId' => 1,
                'numeroSerie' => 'SN-123456',
                'estadoFisico' => 'Operativo',
                'usuarioAsignadoId' => 1
            ]
        );

        // 6. Clientes
        Cliente::updateOrCreate(
            ['ruc' => '20123456789'],
            ['razonSocial' => 'Empresa Test SAC', 'contacto' => 'Juan Perez', 'telefono' => '999888777']
        );

        // 7. Tipos de Caso
        TipoCaso::updateOrCreate(['nombre' => 'Falla de Hardware'], ['prioridadBase' => 'Alta']);
        TipoCaso::updateOrCreate(['nombre' => 'Consulta de Cobranza'], ['prioridadBase' => 'Media']);
         
        // 8. Tickets
        $ticket = Ticket::updateOrCreate(
            ['asunto' => 'Mouse no funciona'],
            [
                'descripcion' => 'El mouse asignado a Edwin no hace clic izquierdo',
                'prioridad' => 'Alta',
                'estadoTicket' => 'Abierto',
                'usuarioReportanteId' => 1,
                'activoId' => 1,
                'tipoCasoId' => 1,
                'estado' => true
            ]
        );

        // 9. Bitácora
        TicketBitacora::updateOrCreate(
            ['ticketId' => $ticket->id, 'comentario' => 'Ticket creado y asignado a soporte técnico.'],
            [
                'usuarioId' => 1,
                'estadoAnterior' => 'N/A',
                'estadoNuevo' => 'Abierto'
            ]
        );
    }
}