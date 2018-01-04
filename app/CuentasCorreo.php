<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class CuentasCorreo extends Model
{
    //
    public function departamento()
    {
        return $this->belongsTo('Api\Departamento','cuentascorreo_id');
    }

    public function areaMensajeria(){
		return $this->belongsTo('Api\AreaMensajeria', 'areaMensajeria_id');
	}
         /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password','tipo','servidor_entrante', 'puerto_entrante','anular_puerto_entrante','usar_ssl_entrante','servidor_saliente', 'puerto_saliente','anular_puerto_saliente','usar_ssl_saliente'];

    public function toArray()
    {
        
        return [
            'id'=>$this->id,  
            'email'=>$this->email,                
            'password'=>$this->password,
            'tipo'=>$this->tipo,
            'servidor_entrante'=>$this->servidor_entrante,
            'puerto_entrante'=>$this->puerto_entrante,  
            'anular_puerto_entrante'=>$this->anular_puerto_entrante,                
            'usar_ssl_entrante'=>$this->usar_ssl_entrante,
            'servidor_saliente'=>$this->servidor_saliente,
            'puerto_saliente'=>$this->puerto_saliente,  
            'anular_puerto_saliente'=>$this->anular_puerto_saliente,                
            'usar_ssl_saliente'=>$this->usar_ssl_saliente,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'areaMensajeria'=>$this->areaMensajeria,
                      
        ];
    }
    public function delete()
    {
        
        return parent::delete();
    } 
}
