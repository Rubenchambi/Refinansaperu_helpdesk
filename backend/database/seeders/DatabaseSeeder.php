<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\TipoCaso;
use App\Models\Cliente;
use App\Models\Activo;
use App\Models\Ticket;
use App\Models\TicketBitacora;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        Role::insert([
            ['nombre' => 'Admin'],
            ['nombre' => 'Soporte'],
            ['nombre' => 'Asesor'],
        ]);

        // 2. Usuarios (Password: password123)
        Usuario::create([
            'nombre' => 'Edwin',
            'apellidos' => 'Dev',
            'dni' => '12345678',
            'email' => 'admin@refinansa.pe',
            'password' => bcrypt('password123'),
            'rolId' => 1,
            'area' => 'Sistemas'
        ]);

        // 3. Productos (Inventario)
        Producto::insert([
            ['nombre' => 'Mouse Logitech M170', 'marca' => 'Logitech', 'stockMinimo' => 5],
            ['nombre' => 'Monitor 24" HP', 'marca' => 'HP', 'stockMinimo' => 3],
        ]);

        // 4. Activos (Inventario Físico)
        Activo::create([
            'productoId' => 1,
            'codigoPatrimonial' => 'ACT-MOUSE-001',
            'numeroSerie' => 'SN-123456',
            'estadoFisico' => 'Operativo',
            'usuarioAsignadoId' => 1
        ]);

        // 5. Clientes
        Cliente::insert([
            ['razonSocial' => 'Empresa Test SAC', 'ruc' => '20123456789', 'contacto' => 'Juan Perez', 'telefono' => '999888777'],
        ]);

        // 6. Tipos de Caso
        TipoCaso::insert([
            ['nombre' => 'Falla de Hardware', 'prioridadBase' => 'Alta'],
            ['nombre' => 'Consulta de Cobranza', 'prioridadBase' => 'Media'],
        ]);
         
        // 7. Tickets (El corazón del sistema)
        $ticket = Ticket::create([
            'asunto' => 'Mouse no funciona',
            'descripcion' => 'El mouse asignado a Edwin no hace clic izquierdo',
            'prioridad' => 'Alta',
            'estadoTicket' => 'Abierto',
            'usuarioReportanteId' => 1,
            'activoId' => 1,
            'tipoCasoId' => 1,
            'estado' => true
        ]);

        // 8. Bitácora (El historial del ticket)
        TicketBitacora::create([
            'ticketId' => $ticket->id,
            'usuarioId' => 1,
            'comentario' => 'Ticket creado y asignado a soporte técnico.',
            'estadoAnterior' => 'N/A',
            'estadoNuevo' => 'Abierto'
        ]);


    }
}