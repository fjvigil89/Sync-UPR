<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Direccion extends Model
{
    //
    //una direccion pertenece a un Hotel
	public function hotel() 
	{ 
		return $this->hasOne('Api\Hotel'); 
	}

	//una direccion pertenece a un usuario
	public function cliente() 
	{ 
		return $this->hasOne('Api\Cliente'); 
	}

    protected $fillable = ['pais', 'ciudad', 'codigoPostal', 'idPais', 'calle','longitud','latitud', 'estado', 'municipio', 'colonia', 'numeroEx', 'numeroInt'];

    public function toArray()
    {
        return [
            'id'=>$this->id,  
            'pais'=>$this->pais,                
            'ciudad'=>$this->ciudad,
            'codigoPostal'=>$this->codigoPostal,                
            'idPais'=>$this->idPais,
            'calle'=>$this->calle, 
            'longitud'=>$this->longitud,     
            'latitud'=>$this->latitud,          
            'estado'=>$this->estado,          
            'municipio'=>$this->municipio,          
            'colonia'=>$this->colonia,          
            'numeroEx'=>$this->numeroEx,          
            'numeroInt'=>$this->numeroInt,               
            
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }
}
