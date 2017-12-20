<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    //
    public function paquetes()
    {
        return $this->hasMany('Api\Paquete');
    }

    public function clientes()
    {
        return $this->hasMany('Api\Cliente');
    }    

    public function reserva()
    {
        return $this->hasOne('Api\Reserva');
    }
}
