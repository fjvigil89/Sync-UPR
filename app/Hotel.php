<?php

namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Hotel extends Model
{
	public function servicios()
    {
    	return $this->belongsToMany('ResortTraffic\Servicio','hotel_servicios')->withPivot('destacado');
	}

    public function galeria()
    {
        return $this->hasMany('ResortTraffic\Galeria');

    }

    public function direccion()
    {
        return $this->belongsTo('ResortTraffic\Direccion');
    }

    public function paquetes()
    {
        return $this->belongsToMany('ResortTraffic\Paquete','hotel_paquetes')->withTimestamps();
                    
    }

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'smallName', 'descripcion', 'rating'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'nombre' => $this->nombre,
            'smallName'=> $this->smallName,
            'descripcion' => $this->descripcion,
            'rating'=> $this->rating,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            "direccion" => $this->direccion,            
            "servicios" => $this->servicios,
            "galeria" =>$this->galeria
        ];
    }
    public function delete()
    {
        //eliminamos los posts 
        if(count($this->galeria) > 0){
            foreach($this->galeria as $post)
            {
                $post->delete();
            }
        }

        //eliminamos el dni
        if($this->direccion){
            $this->direccion->delete();
        }

        //eliminamos la información de la tabla hotel_servicio con detach
        //que hace referencia al usuario
        if(count($this->servicio) > 0){
            $this->servicio()->detach();
        }
        
        //eliminamos la información de la tabla hotel_paquete con detach
        //que hace referencia al usuario
        if(count($this->paquete) > 0){
            $this->paquete()->detach();
        }
        //eliminamos al usuario
        return parent::delete();
    }

}
