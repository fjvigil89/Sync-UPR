<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantAdulto');
            $table->integer('cantidadMenores');
            $table->timestamp('fechaLlegada');
            $table->timestamp('fechaSalida');
            $table->integer('operacion');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->integer('paquete_id')->unsigned();            
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');
            $table->integer('estacion_id')->unsigned();            
            $table->foreign('estacion_id')->references('id')->on('estacions')->onDelete('cascade');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
