<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondicionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condicions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('tipo');  
            $table->string('cumple');       
            $table->integer('regla_id')->unsigned();
            $table->foreign('regla_id')->references('id')->on('reglas');
                                    
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
        Schema::dropIfExists('condicions');
    }
}
