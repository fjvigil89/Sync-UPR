<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Galeria extends Model
{
    //
    //una galeria pertenece a un Hotel
	public function hotel() 
	{ 
		return $this->belongsTo('Api\Hotel'); 
	}

	protected $fillable = ['hotel_id', 'ruta'];

	public function toArray()
    {
        return [
        	'id'=> $this->id,
            'hotel_id' => $this->hotel_id,
            'ruta'=> $this->ruta,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            
        ];
    }
}
