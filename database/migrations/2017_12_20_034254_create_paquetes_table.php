<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaquetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->increments('id');
            $table->string("nombre",50);
            $table->string("tipo",50);
            $table->integer("precio");
            $table->string("moneda",3);
            $table->integer("maximoAdulto");
            $table->integer("maximoNino");
            $table->integer("cantidadDias");
            $table->integer("cantidadNoches");
            $table->integer("costoAdicional");
            $table->integer("costosPersonaAdicional");
            $table->integer("costosXcancelacion");
            $table->integer("costosXaplazar");
            $table->integer("costosXaplaza2");
            $table->integer("costosXaplaza3");
            $table->integer("rating");
            $table->boolean("destacado")->default(false);
            $table->boolean("activo")->default(false);
            $table->text("disponible");
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
        Schema::dropIfExists('paquetes');
    }
}
