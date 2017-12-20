<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model {

	//un servicio puede pertenecer a muchos hoteles, muchos a muchos con hotel
	public function hoteles()
	{
		return $this->belongsToMany("ResortTraffic\Hotel");
	}	

	protected $fillable = ['id','nombre', 'logo', 'disponible'];

	public function toArray()
    {
        return [
        	'id'=> $this->id,
            'nombre' => $this->nombre,
            'logo'=> $this->logo,
            'disponible'=> $this->disponible
            
        ];
    }

}
