<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosBancksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_bancks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('metodoPago');
            $table->string('resolucion');
            $table->string('cuenta');           
            $table->timestamp('fechaIngreso');
            $table->timestamp('fechaTransaccion');
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
        Schema::dropIfExists('datos_bancks');
    }
}
