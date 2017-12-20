<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestaAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuesta_adjuntos', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->default(true);
            $table->integer('respuestasdefinidas_id')->unsigned();
            $table->foreign('respuestasdefinidas_id')->references('id')->on('respuestas_definidas')->onDelete('cascade');
            $table->integer('documentosadjuntos_id')->unsigned();            
            $table->foreign('documentosadjuntos_id')->references('id')->on('documentos_adjuntos');
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
        Schema::dropIfExists('respuesta_adjuntos');
    }
}
