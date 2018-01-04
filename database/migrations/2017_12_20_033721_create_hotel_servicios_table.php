<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_servicios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id')->unsigned();
            //$table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->integer('servicio_id')->unsigned();
            //$table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->boolean('destacado')->defaul(true);
            $table->boolean('disponible')->defaul(true);
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
        Schema::dropIfExists('hotel_servicios');
    }
}
