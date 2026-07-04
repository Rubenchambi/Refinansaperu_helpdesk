<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->string('direccion')->nullable();
        $table->string('distrito')->nullable();
        $table->string('ciudad')->nullable();
        // Agregamos ->nullable() para evitar el error
        $table->string('cargo')->nullable()->after('telefono'); 
        $table->string('cartera_asignada')->nullable();
        $table->string('email')->nullable()->after('contacto');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
};
