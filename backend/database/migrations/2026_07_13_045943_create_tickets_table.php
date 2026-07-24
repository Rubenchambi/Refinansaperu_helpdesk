<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ticket')->unique(); // El formato 20260712-180100-1
            
            $table->foreignId('usuarioReportanteId')->constrained('usuarios');
            $table->foreignId('tecnicoAsignadoId')->nullable()->constrained('usuarios');
            $table->foreignId('activoId')->nullable()->constrained('activos');
            $table->foreignId('tipoCasoId')->constrained('tipo_casos');
            
            $table->string('asunto');
            $table->text('descripcion');
            $table->integer('prioridad'); // Cambiado a integer para orden lógico (1=Alta)
            $table->string('estadoTicket'); // 'pendiente', 'en_proceso', 'resuelto', 'cerrado'
            $table->string('tipo_cola')->default('general'); // 'soporte' o 'general'
            
            $table->timestamp('asignado_at')->nullable();
            $table->timestamp('finalizado_at')->nullable();
            $table->boolean('estado')->default(true); // Para eliminación lógica
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};