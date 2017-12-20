<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model {

	//una galeria pertenece a un Hotel
	public function hotel() 
	{ 
		return $this->belongsTo('ResortTraffic\Hotel'); 
	}

	protected $fillable = ['id','hotel_id', 'ruta'];

	public function toArray()
    {
        return [
        	'id'=> $this->id,
            'hotel_id' => $this->hotel_id,
            'ruta'=> $this->ruta
            
        ];
    }


}
