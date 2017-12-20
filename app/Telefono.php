<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Telefono extends Model
{
    //
    public function cliente()
    {
        return $this->belongsTo('Api\Cliente');
    }
	protected $fillable = ['tipo', 'pais', 'area', 'numero'];

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'tipo'=>$this->tipo,                
            'pais'=>$this->pais,
            'area'=>$this->area,                
            'numero'=>$this->numero,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),            
            //'cliente'=>$this->cliente
                       
        ];
    }
}
