<?php namespace ResortTraffic;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Condicion extends Model {

	public function estacion()
    {
        return $this->belongsTo('ResortTraffic\Estacion');
    }

	public function regla()
    {
        return $this->belongsTo('ResortTraffic\Reglas');
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
            'nombre'=>$this->nombre,                
            'tipo'=>$this->tipo,
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y'),
            'estacion'=>$this->estacion,
            //'regla'=>$this->regla

            
        ];
    }

}
