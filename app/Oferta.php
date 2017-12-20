<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model {

	//
    public function paquetes()
    {
        return $this->hasMany('ResortTraffic\Paquete');
    }

    public function clientes()
    {
        return $this->hasMany('ResortTraffic\Cliente');
    }    

    public function reserva()
    {
        return $this->hasOne('ResortTraffic\Reserva');
    }

}
