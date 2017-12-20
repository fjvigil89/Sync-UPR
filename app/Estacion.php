<?php

namespace Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Estacion extends Model
{
    //
    public function condicion()
    {
        return $this->hasOne('Api\Condicion');

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

            //"created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            //'condicion'=>$this->condicion->toArray()
            
        ];
    }
}
