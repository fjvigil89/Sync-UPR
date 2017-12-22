<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Cliente extends Model
{
    //
    public function direccion()
    {
        return $this->belongsTo('Api\Direccion');
    }
    public function mensajes()
    {
        return $this->hasMany('Api\Mensaje');
    }

    public function notas()
    {
        return $this->hasMany('Api\Notas');
    }

    public function telefono()
    {
        return $this->hasMany('Api\Telefono');
    }

    public function user()
    {
    	return $this->belongsToMany('Api\Usuario','cliente_usuarios','cliente_id','usuario_id');
	}

   public function datosbanck()
    {
        return $this->belongsTo('Api\DatosBanck');
    }

    public function paquete()
    {
        return $this->belongsToMany('Api\Paquete','reservas','cliente_id', 'paquete_id')->withPivot('fechaLlegada','created_at','id','fechaSalida','cantAdulto','cantidadMenores');
    }
    public function estadooperativo()
    {
        return $this->belongsTo('Api\EstadoOperativo');
    }

    protected $fillable = ['nombre', 'apellido1', 'apellido2', 'genero', 'email','ultimaConexion','ip'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'nombre'=>$this->nombre,                
            'apellido1'=>$this->apellido1,
            'apellido2'=>$this->apellido2,                
            'genero'=>$this->genero,
            'email'=>$this->email, 
            'ultimaConexion'=>$this->ultimaConexion,
            'ip'=>$this->ip,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'direccion'=>$this->direccion->toArray(),     
            'mensajes'=>$this->mensajes->toArray(),          
            'notas'=>$this->notas->toArray(),          
            'telefono'=>$this->telefono->toArray(),          
            'usuario'=>$this->user->toArray(),          
            //'datosbanck'=>$this->datosbanck->toArray(),          
            //'estadooperativo'=>$this->estadooperativo->toArray(),

            

            
        ];

    }
}
