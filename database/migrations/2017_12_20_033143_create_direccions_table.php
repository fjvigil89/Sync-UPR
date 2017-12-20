<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDireccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direccions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pais');
            $table->string('ciudad');                       
            $table->integer('codigoPostal');
            $table->string('idPais');                       
            $table->string('calle');
            $table->string('longitud');                     
            $table->string('latitud');
            $table->string('estado');                       
            $table->string('municipio');
            $table->string('colonia');                      
            $table->string('numeroEx');
            $table->string('numeroInt');
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
        Schema::dropIfExists('direccions');
    }
}
