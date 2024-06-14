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
        Schema::create('puntos_gps', function (Blueprint $table) {
            $table->id();
            $table->text('dispositivo');
            $table->text('imei');
            $table->text('tiempo');
            $table->string('placa');
            $table->string('version');
            $table->text('latitud');
            $table->text('longitud');
            $table->dateTime('fecha_recepcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_gps');
    }
};
