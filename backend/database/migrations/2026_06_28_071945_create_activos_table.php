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
    Schema::create('activos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('productoId')->constrained('productos');
        $table->string('codigoPatrimonial')->unique();
        $table->string('numeroSerie');
        $table->string('estadoFisico'); // 'operativo', 'averiado', 'reparacion'
        $table->foreignId('usuarioAsignadoId')->nullable()->constrained('usuarios');
        $table->boolean('estado')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
