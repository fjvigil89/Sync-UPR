<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Usuario extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('Api\User');
    }

    public function cliente()
    {
        return $this->belongsToMany('Api\Cliente','cliente_usuarios','usuario_id','cliente_id');
    }

    protected $fillable = ['username', 'apellidos', 'rol','activo'];

    public function toArray()
    {
        return [
        	'id'=>$this->id,
            'username'=>$this->username,                
            'apellidos'=>$this->apellidos,
            'rol'=>$this->rol,                
            'activo'=>$this->activo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }
}
