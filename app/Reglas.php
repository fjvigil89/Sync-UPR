<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reglas extends Model {

	public function accion()
    {
        return $this->belongsTo('ResortTraffic\Acciones','id','regla_id');
    }

    public function condiciones()
    {
        return $this->belongsTo('ResortTraffic\Condicion','id','regla_id');
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
            'nombre'=>$this->nombre,                
            'descripcion'=>$this->descripcion,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'accion'=>$this->accion,
            'condiciones' =>$this->condiciones

            
        ];
    }
    
}
