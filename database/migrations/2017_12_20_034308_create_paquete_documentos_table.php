<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaqueteDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquete_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->default(true);
            $table->integer('documentos_id')->unsigned();
            $table->foreign('documentos_id')->references('id')->on('documentos_solicitars')->onDelete('cascade');
            $table->integer('paquete_id')->unsigned();  
            $table->foreign('paquete_id')->references('id')->on('paquetes')->onDelete('cascade');            
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
        Schema::dropIfExists('paquete_documentos');
    }
}
