<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Notas extends Model {

	public function cliente()
    {
        return $this->belongsTo('ResortTraffic\Cliente');
    }

    protected $fillable = ['contenido', 'fecha', 'destacada'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'contenido'=>$this->contenido,                
            'fecha'=>$this->fecha,                
            'destacada'=>$this->destacada,

            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),            
            'cliente'=>$this->cliente
                       
        ];
    } 

}
