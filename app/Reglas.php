<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Reglas extends Model
{
    //
    public function accion()
    {
        return $this->belongsTo('Api\Acciones','id','regla_id');
    }

    public function condiciones()
    {
        return $this->belongsTo('Api\Condicion','id','regla_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

    public function toArray()
    {
        return [
        	'id'=>$this->id,                
            'nombre'=>$this->nombre,                
            'descripcion'=>$this->descripcion,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'accion'=>$this->accion,
            'condiciones' =>$this->condiciones

            
        ];
    }
    
}
