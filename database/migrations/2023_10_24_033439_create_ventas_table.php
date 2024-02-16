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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            //$table->string('ublVersion');
            $table->string('tipoOperacion'); // Venta - Catalog. 51
            $table->string('tipoDoc'); // Factura - Catalog. 01 
            $table->string('serie');
            $table->string('correlativo');
            $table->dateTime('fechaEmision'); // Zona horaria: Lima
            $table->dateTime('fechaVencimiento'); // Zona horaria: Lima
            $table->string('formaPago'); // FormaPago: Contado
            $table->string('tipoMoneda'); // Sol - Catalog. 02
            $table->string('ruc');
            $table->string('razonSocial');
            $table->string('direccion');
            //Mto Operaciones
            $table->decimal('mtoOperGravadas',15,5)->nullable();
            $table->decimal('mtoOperExoneradas',15,5)->nullable();
            $table->decimal('mtoOperInafectas',15,5)->nullable();
            $table->decimal('mtoOperExportacion',15,5)->nullable();
            $table->decimal('mtoOperGratuitas',15,5)->nullable();
            //Impuestos
            $table->decimal('mtoIGV',15,5)->nullable();
            $table->decimal('mtoIGVGratuitas',15,5)->nullable();
            $table->decimal('icbper',15,5)->nullable();
            $table->decimal('totalImpuestos',15,5)->nullable();
            //Totales
            $table->decimal('valorVenta',15,5)->nullable();
            $table->decimal('subTotal',15,5)->nullable();
            $table->decimal('redondeo',15,5)->nullable();
            $table->decimal('mtoImpVenta',15,5)->nullable();
            //Productos
            $table->text('productos');            
            //Leyendas
            $table->text('legends');

            // Respuestas SUNAT
            $table->string('estadoSunat')->nullable(); // Sol - Catalog. 02
            $table->string('cdrResponse')->nullable();
            $table->text('XML')->nullable();
            $table->text('CDR')->nullable();

            //Empresas
            $table->unsignedBigInteger('organizacion_id')->nullable();
            $table->foreign('organizacion_id')->references('id')->on('organizacions')->onDelete('set null');

            //Usuario
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();/////////////////////////////////////////////////////////////////////////////////////
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
