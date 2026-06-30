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
    Schema::create('ticket_bitacoras', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ticketId')->constrained('tickets');
        $table->foreignId('usuarioId')->constrained('usuarios');
        $table->text('comentario');
        $table->string('estadoAnterior');
        $table->string('estadoNuevo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_bitacoras');
    }
};
