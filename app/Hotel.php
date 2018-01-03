<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Hotel extends Model
{
    //
    public function servicios()
    {
    	return $this->belongsToMany('Api\Servicio','hotel_servicios')->withTimestamps()->withPivot('destacado','disponible');
	}

    public function galeria()
    {
        return $this->hasMany('Api\Galeria');

    }

    public function direccion()
    {
        return $this->belongsTo('Api\Direccion');
    }

    public function marca()
    {
        return $this->belongsTo('Api\Marca');
    }

    public function paquetes()
    {
        return $this->belongsToMany('Api\Paquete','hotel_paquetes')->withTimestamps();
                    
    }

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'smallName', 'descripcion', 'rating'];

    public function toArray()
    {
        $seraux = $this->servicios;

        $arraux = array();

        foreach ($seraux as  $value) {

            array_push($arraux, ["id"=>$value->id,"nombre"=>$value->nombre,"destacado"=>$value->pivot->destacado,"disponible"=>$value->pivot->disponible]);
        }
        return [
            'id'=> $this->id,
            'nombre' => $this->nombre,
            'smallName'=> $this->smallName,
            'descripcion' => $this->descripcion,
            'rating'=> $this->rating,
            'activo'=>$this->activo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            "direccion" => $this->direccion,            
            "servicios" => $arraux,
            "galeria" =>$this->galeria,
            'paquetes'=>$this->paquetes,
            'marca'=>$this->marca

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
