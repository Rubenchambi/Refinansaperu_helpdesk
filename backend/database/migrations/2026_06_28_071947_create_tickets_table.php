<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuarioReportanteId')->constrained('usuarios');
        $table->foreignId('tecnicoAsignadoId')->nullable()->constrained('usuarios');
        $table->foreignId('activoId')->nullable()->constrained('activos');
        $table->foreignId('tipoCasoId')->constrained('tipo_casos');
        $table->string('asunto');
        $table->text('descripcion');
        $table->string('prioridad');
        $table->string('estadoTicket');
        $table->boolean('estado')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
