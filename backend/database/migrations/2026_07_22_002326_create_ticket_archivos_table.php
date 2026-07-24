<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_archivos', function (Blueprint $table) {
            $table->id();
            
            // Relación correcta apuntando a la tabla 'tickets' (usará el 'id' de esa tabla)
            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->onDelete('cascade');
            
            $table->string('nombre_original'); // Nombre original del archivo (ej: reporte.pdf)
            $table->string('ruta_archivo');    // Ruta física en el storage
            $table->string('extension');       // Extensión (pdf, docx, pptx, etc.)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_archivos');
    }
};