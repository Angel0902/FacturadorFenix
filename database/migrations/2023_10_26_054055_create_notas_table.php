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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();

            //$table->string('ublVersion');
            $table->string('tipoDoc'); // Factura - Catalog. 01 
            $table->string('serie');
            $table->string('correlativo');
            $table->dateTime('fechaEmision'); // Zona horaria: Lima
            $table->dateTime('fechaVencimiento'); // Zona horaria: Lima
            $table->string('tipDocAfectado');
            $table->string('numDocfectado');
            $table->string('codMotivo');
            $table->string('desMotivo');
            $table->string('tipoMoneda'); // Sol - Catalog. 02
            $table->string('ruc');
            $table->string('razonSocial');
            $table->string('direccion');
            //Mto Operaciones
            $table->decimal('mtoOperGravadas',15,5);
            $table->decimal('mtoOperExoneradas',15,5);
            $table->decimal('mtoOperInafectas',15,5);
            $table->decimal('mtoOperExportacion',15,5);
            $table->decimal('mtoOperGratuitas',15,5);
            //Impuestos
            $table->decimal('mtoIGV',15,5);
            $table->decimal('mtoIGVGratuitas',15,5);
            $table->decimal('icbper',15,5);
            $table->decimal('totalImpuestos',15,5);
            //Totales
            $table->decimal('valorVenta',15,5);
            $table->decimal('subTotal',15,5);
            $table->decimal('redondeo',15,5);
            $table->decimal('mtoImpVenta',15,5);
            
            //Productos
            $table->text('productos');            
            //Leyendas
            $table->text('legends');

            // Respuestas SUNAT
            $table->string('estadoSunat')->nullable(); // Sol - Catalog. 02
            $table->string('cdrResponse')->nullable();
            $table->string('XML')->nullable();
            $table->string('CDR')->nullable();

            //Empresas
            $table->unsignedBigInteger('organizacion_id')->nullable();
            $table->foreign('organizacion_id')->references('id')->on('organizacions')->onDelete('set null');

            //Usuario
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
