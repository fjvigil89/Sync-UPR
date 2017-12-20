<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paquete extends Model {

	
    public function requisitos()
    {
        return $this->belongsToMany('ResortTraffic\Requisitos','paquete_requisitos','paquete_id','requisito_id')->withTimestamps();
    }
    public function documentosSolicitar()
    {
        return $this->belongsToMany('ResortTraffic\DocumentosSolicitar','paquete_documentos','paquete_id','documentos_id')->withTimestamps();
    }

    //un paquete puede pertenecer a muchos hoteles, muchos a muchos con hotel
    public function hoteles()
    {
        return $this->belongsToMany("ResortTraffic\Hotel",'hotel_paquetes')->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsToMany('ResortTraffic\Cliente','reservas','paquete_id','cliente_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['id','nombre', 'tipo', 'precio', 'moneda','maximoAdulto','maximoNino','cantidadDias','cantidadNoches','costoAdicional','costosPersonaAdicional','costosXcancelacion','costosXaplazar','costosXaplaza2','costosXaplaza3','destacado','activo','disponible'];


    public function toArray()
    {
        return [

            'id'=>$this->id,
            'nombre'=>$this->nombre,
            'tipo'=>$this->tipo,
            'precio'=>$this->precio,
            'moneda'=>$this->moneda,
            'maximoAdulto'=>$this->maximoAdulto,
            'maximoNino'=>$this->maximoNino,
            'cantidadDias'=>$this->cantidadDias,
            'cantidadNoches'=>$this->cantidadNoches,
            'costoAdicional'=>$this->costoAdicional,
            'costosPersonaAdicional'=>$this->costosPersonaAdicional,
            'costosXcancelacion'=>$this->costosXcancelacion,            
            'costosXaplazar'=>$this->costosXaplazar, 
            'costosXaplaza2'=>$this->costosXaplaza2,
            'costosXaplaza3'=>$this->costosXaplaza3,
            'activo'=>$this->activo, 
            'destacado'=>$this->destacado, 
            'disponible'=>$this->disponible,  
            'requisitos'=>$this->requisitos,       
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),            
            'documentosSolicitar' =>$this->documentosSolicitar,
            'hoteles'=> $this->hoteles
           
           
        ];
    }

    public function delete()
    {
        
        //eliminamos la información de la tabla hotel_paquete con detach
        //que hace referencia al usuario
        if(count($this->requisitos) > 0){
            $this->requisitos()->detach();
        }

        if(count($this->documentosSolicitar) > 0){
            $this->documentosSolicitar()->detach();
        }
        
        if(count($this->hoteles) > 0){
            $this->hoteles()->detach();
        }
        //eliminamos al usuario
        return parent::delete();
    }   
}
