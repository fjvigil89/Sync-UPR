<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Servicio extends Model
{
    //
    //un servicio puede pertenecer a muchos hoteles, muchos a muchos con hotel
	public function hoteles()
	{
		return $this->belongsToMany("Api\Hotel");
	}	

	protected $fillable = ['nombre', 'logo', 'disponible'];

	public function toArray()
    {
        return [
        	'id'=> $this->id,
            'nombre' => $this->nombre,
            'logo'=> $this->logo,
            'disponible'=> $this->disponible,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            
        ];
    }
}
