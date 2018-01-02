<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Acciones extends Model
{
    public function regla()
    {
        return $this->belongsTo('Api\Reglas');
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'asignacion'];

    public function toArray()
    {
        return [
            'id'=>$this->id,  
            'nombre'=>$this->nombre,                
            'asignacion'=>$this->asignacion,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),            
        ];
    }
    public function delete()
    {
        
        return parent::delete();
    } 
}
