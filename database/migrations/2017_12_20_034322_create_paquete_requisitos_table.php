<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaqueteRequisitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquete_requisitos', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->default(true);
            $table->integer('requisito_id')->unsigned();
            $table->foreign('requisito_id')->references('id')->on('requisitos')->onDelete('cascade');
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
        Schema::dropIfExists('paquete_requisitos');
    }
}
