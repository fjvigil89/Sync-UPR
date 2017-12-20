<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Usuario extends Model {

	
    public function user()
    {
        return $this->belongsTo('ResortTraffic\User');
    }

    public function cliente()
    {
        return $this->belongsToMany('ResortTraffic\Cliente','cliente_usuarios','usuario_id','cliente_id');
    }

    protected $fillable = ['username', 'apellidos', 'rol','activo'];

    public function toArray()
    {
        return [
            'username'=>$this->username,                
            'apellidos'=>$this->apellidos,
            'rol'=>$this->rol,                
            'activo'=>$this->activo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')

            
        ];
    }

}
