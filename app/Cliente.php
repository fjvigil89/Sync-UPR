<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cliente extends Model {

   public function direccion()
    {
        return $this->belongsTo('ResortTraffic\Direccion');
    }
    public function mensajes()
    {
        return $this->hasMany('ResortTraffic\Mensaje');
    }

    public function notas()
    {
        return $this->hasMany('ResortTraffic\Notas');
    }

    public function telefono()
    {
        return $this->hasMany('ResortTraffic\Telefono');
    }

    public function user()
    {
    	return $this->belongsToMany('ResortTraffic\Usuario','cliente_usuarios','cliente_id','usuario_id');
	}

   public function datosbanck()
    {
        return $this->belongsTo('ResortTraffic\DatosBanck');
    }

    public function paquete()
    {
        return $this->belongsToMany('ResortTraffic\Paquete','reservas','cliente_id', 'paquete_id')->withPivot('fechaLlegada','created_at','id','fechaSalida','cantAdulto','cantidadMenores');
    }
    public function estadooperativo()
    {
        return $this->belongsTo('ResortTraffic\EstadoOperativo');
    }

    protected $fillable = ['nombre', 'apellido1', 'apellido2', 'genero', 'email'];

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
//Información de Cliente en la linea 1025 del blade.
//Operaciones en curso lineas 202
//1414 agregar oferta

