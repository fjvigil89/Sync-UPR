<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Marca extends Model
{
	public function hotel(){
		return $this->hasMany('Api\Hotel');
	}
    
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'url', 'logo'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'nombre' => $this->nombre,
            'url'=> $this->url,
            'logo' => $this->logo,            
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            

        ];
    }
}
