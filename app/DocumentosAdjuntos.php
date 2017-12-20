<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class DocumentosAdjuntos extends Model {

	protected $fillable=['nombre', 'disponible'];

	public function respuestasDefinidas()
    {
        
        return $this->belongsToMany('ResortTraffic\RespuestasDefinidas','respuesta_adjuntos','documentosadjuntos_id','respuestasdefinidas_id')
                    ->withTimestamps();   	
         
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
