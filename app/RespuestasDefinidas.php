<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RespuestasDefinidas extends Model {



	public function documentosAdjuntos()    {
    	
        return $this->belongsToMany('ResortTraffic\DocumentosAdjuntos','respuesta_adjuntos','respuestasdefinidas_id','documentosadjuntos_id');
	}

    public function estacion()
    {
        return $this->belongsTo('ResortTraffic\Estacion');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'asunto','descripcion', 'contenido', 'activo'];

    public function toArray()
    {
        return [
            'nombre'=>$this->nombre,                
            'asunto'=>$this->asunto,
            'descripcion'=>$this->descripcion,                
            'contenido'=>$this->contenido,
            'activo'=>$this->activo,
            'estacion'=>$this->estacion->toArray(),
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            
            

            
        ];
    }

	public function delete()
    {
        
        //eliminamos la información de la tabla hotel_paquete con detach
        //que hace referencia al usuario
        if(count($this->documentosAdjuntos) > 0){
            $this->documentosAdjuntos()->detach();
        }

  
        //eliminamos al usuario
        return parent::delete();
    } 
}
