<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido1');
            $table->string('apellido2');
            $table->string('genero');
            $table->string('email')->unique();
            $table->timestamp('ultimaConexion');
            $table->string('ip',255);
            $table->integer('direccion_id')->unsigned();
            $table->foreign('direccion_id')->references('id')->on('direccions')->onDelete('cascade');           
            $table->integer('datosbanck_id')->unsigned();
            $table->foreign('datosbanck_id')->references('id')->on('datos_bancks')->onDelete('cascade');
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
        Schema::dropIfExists('clientes');
    }
}
