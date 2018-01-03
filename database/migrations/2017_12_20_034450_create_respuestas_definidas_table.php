<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasDefinidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_definidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->string('asunto');
            $table->string('descripcion');  
            $table->string('encabezado');
            $table->string('contenido');
            $table->string('pie');
            $table->integer('estacion_id')->unsigned();            
            $table->foreign('estacion_id')->references('id')->on('estacions')->onDelete('cascade');         
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('respuestas_definidas');
    }
}
