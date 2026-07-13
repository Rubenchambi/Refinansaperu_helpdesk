<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ticket_bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticketId')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('usuarioId')->constrained('usuarios');
            $table->string('accion'); 
            $table->text('comentario')->nullable();
            $table->string('estadoAnterior')->nullable();
            $table->string('estadoNuevo')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ticket_bitacoras'); }
};