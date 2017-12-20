<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Condicion extends Model
{
    //
    public function estacion()
    {
        return $this->belongsTo('Api\Estacion');
    }

	public function regla()
    {
        return $this->belongsTo('Api\Reglas');
    }

          /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'tipo'];

    public function toArray()
    {
        return [
            'id'=>$this->id,  
            'nombre'=>$this->nombre,                
            'tipo'=>$this->tipo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'estacion'=>$this->estacion,
            //'regla'=>$this->regla

            
        ];
    }
}
