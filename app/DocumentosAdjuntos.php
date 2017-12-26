<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DocumentosAdjuntos extends Model
{
    //
    protected $fillable=['nombre', 'activo','descripcion'];

	public function respuestasDefinidas()
    {
        
        return $this->belongsToMany('Api\RespuestasDefinidas','respuesta_adjuntos','documentosadjuntos_id','respuestasdefinidas_id')
                    ->withTimestamps();   	
         
	}

 	public function toArray()
    {
        return [
            'id'=>$this->id,                
            'nombre'=>$this->nombre,
            'activo'=>$this->activo,   
            'descripcion'=>$this->descripcion,                        
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }

	public function delete()
    {
        
        //eliminamos la información de la tabla hotel_paquete con detach
        //que hace referencia al usuario
        if(count($this->respuestasDefinidas) > 0){
            $this->respuestasDefinidas()->detach();
        }

  
        //eliminamos al usuario
        return parent::delete();
    } 
}
