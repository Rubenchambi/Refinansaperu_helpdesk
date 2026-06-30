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
    Schema::create('solicitud_compras', function (Blueprint $table) {
        $table->id();
        $table->foreignId('productoId')->constrained('productos');
        $table->integer('cantidad');
        $table->string('motivo');
        $table->string('estadoAprobacion'); // 'pendiente', 'aprobado', 'rechazado'
        $table->foreignId('usuarioSolicitanteId')->constrained('usuarios');
        $table->boolean('estado')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_compras');
    }
};
