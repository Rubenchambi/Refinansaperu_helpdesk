<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
    {
        // 1. Añadimos la categoría al producto para filtrar si es Laptop, Celular, etc.
        Schema::table('productos', function (Blueprint $table) {
            $table->string('categoria')->default('Hardware')->after('nombre'); // Ej: Laptop, Celular, Periférico, etc.
            $table->text('descripcion')->nullable()->after('marca');
        });

        // 2. Añadimos columnas flexibles a los activos (ideal para celulares con línea o chips)
        Schema::table('activos', function (Blueprint $table) {
            $table->string('numeroTelefono')->nullable()->after('numeroSerie'); // Para los celulares de masivos / chips
            $table->string('operador')->nullable()->after('numeroTelefono');    // Claro, Movistar, Entel, etc.
            $table->text('observaciones')->nullable()->after('estadoFisico');   // Notas rápidas de soporte
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'descripcion']);
        });

        Schema::table('activos', function (Blueprint $table) {
            $table->dropColumn(['numeroTelefono', 'operador', 'observaciones']);
        });
    }
};
