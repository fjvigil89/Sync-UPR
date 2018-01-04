<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentasCorreosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_correos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->uniqed();                
            $table->string('password');
            $table->string('tipo');
            $table->string('servidor_entrante');
            $table->integer('puerto_entrante');            
            $table->boolean('anular_puerto_entrante')->default(false);            
            $table->boolean('usar_ssl_entrante')->default(false);
            $table->string('servidor_saliente');
            $table->integer('puerto_saliente');
            $table->boolean('anular_puerto_saliente')->default(false);
            $table->boolean('usar_ssl_saliente')->default(false);

            
            $table->integer('areaMensajeria_id')->nullable()->unsigned();
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
        Schema::dropIfExists('cuentas_correos');
    }
}
