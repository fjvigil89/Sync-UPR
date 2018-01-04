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

    public function departamento()
    {
        return $this->belongsTo('Api\Departamento');
    }

    protected $fillable = ['username', 'apellidos', 'rol', 'activo', 'instancias'];

    public function toArray()
    {
        return [
        	'id'=>$this->id,
            'username'=>$this->username,  
            'name'=>$this->user->name,              
            'apellidos'=>$this->apellidos,
            'email'=>$this->user->email, 
            'rol'=>$this->rol,                
            'activo'=>$this->activo,
            'instancias'=> $this->instancias,
            'user'=>$this->user,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }
}
