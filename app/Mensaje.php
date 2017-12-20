<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mensaje extends Model {

	
	public function cliente()
    {
        return $this->belongsTo('ResortTraffic\Cliente');
    }

    public function respuetasDefinidas()
    {
        return $this->hasMany('ResortTraffic\RespuestasDefinidas');
    }

    protected $fillable = ['de', 'para', 'asunto', 'cuerpo', 'fecha', 'rutaPlantilla', 'rutaAdjunto'];

    public function toArray()
    {
        return [
            'id'=>$this->id,
            'de'=>$this->de,                
            'para'=>$this->para,
            'asunto'=>$this->asunto,                
            'cuerpo'=>$this->cuerpo,
            'fecha'=>$this->fecha, 
            'rutaPlantilla'=>$this->rutaPlantilla,     
            'rutaAdjunto'=>$this->rutaAdjunto,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }
}
