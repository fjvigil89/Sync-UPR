<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class AreaMensajeria extends Model
{
    //
    public function cuentasCorreo()
    {
        return $this->belongsTo('Api\CuentasCorreo', 'areaMensajeria_id');
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
            'id'=>$this->id,  
            'nombre'=>$this->nombre,           
                      
        ];
    }
    public function delete()
    {
        
        return parent::delete();
    } 
}
