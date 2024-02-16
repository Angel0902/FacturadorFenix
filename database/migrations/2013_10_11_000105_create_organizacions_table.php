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
        Schema::create('organizacions', function (Blueprint $table) {
            $table->id();
            
            $table->string('razon_social');
            $table->string('ruc');
            $table->string('direccion');
            $table->string('logo_path')->nullable();

            // Crendenciales
            $table->string('sol_user');// Usuario SOL 
            $table->string('sol_pass');// Clave SOL
            $table->string('cert_path')->nullable();// Certificado Digital

            // Credenciales API
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->boolean('production')->default(false);// Esta o no enviando a SUNAT

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizacions');
    }
};
