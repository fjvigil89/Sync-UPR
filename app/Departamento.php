<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Departamento extends Model
{
    //
    public function Usuario(){
		return $this->hasMany('Api\Usuario');
	}
    public function cuentasCorreo()
    {
        return $this->belongsTo('Api\CuentasCorreo','cuentascorreo_id');
    }
    
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'nombre'=>$this->nombre,
            'usuarios'=>$this->Usuario,  
            'cuentasCorreo'=>$this->cuentasCorreo,                      
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            

        ];
    }
}
