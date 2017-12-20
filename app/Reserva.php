<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Reserva extends Model
{
    //
    public function estacion()
    {
        return $this->belongsTo('Api\Estacion');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cantAdulto', 'cantidadMenores','fechaLlegada', 'fechaSalida', 'operacion'];

    public function toArray()
    {
        return [
        	'id'=>$this->id,
            'cantAdulto'=>$this->cantAdulto,                
            'cantidadMenores'=>$this->cantidadMenores,
            'fechaLlegada'=>$this->fechaLlegada,                
            'fechaSalida'=>$this->fechaSalida,
            'operacion'=>$this->operacion,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'estacion'=>$this->estacion

            
        ];
    }
}
