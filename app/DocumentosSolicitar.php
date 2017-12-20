<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DocumentosSolicitar extends Model
{
    //
    //un Documento puede pertenecer a muchos paquete, muchos a muchos con paquete
    public function paquetes()
    {
        return $this->belongsToMany("Api\Paquete",'paquete_documentos','paquete_id','documentos_id')->withTimestamps();
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','nombre', 'descripcion','activo'];
    
    public function toArray()
    {
        return [  
            'id'=>$this->id,          
            'nombre'=>$this->nombre,
            'descripcion'=>$this->descripcion,
            'activo'=>$this->activo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            

            
        ];
    }

	public function delete()
    {
        
        //eliminamos la información de la tabla hotel_paquete con detach
        //que hace referencia al usuario
        if(count($this->paquetes) > 0){
            $this->paquetes()->detach();
        }

  
        //eliminamos al usuario
        return parent::delete();
    } 
}
