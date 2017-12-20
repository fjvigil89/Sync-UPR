<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DatosBanck extends Model {

    public function cliente()
    {
        return $this->belongsTo('ResortTraffic\Cliente');
    }

	protected $fillable = ['metodoPago', 'resolucion', 'cuenta', 'fechaIngreso', 'fechaTransaccion'];

    public function toArray()
    {
        return [
        	'id'=> $this->id,
            'metodoPago'=> $this->metodoPago,
            'resolucion'=>$this->resolucion,                
            'cuenta'=>$this->cuenta,
            'fechaIngreso'=>$this->fechaIngreso,                
            'fechaTransaccion'=>$this->fechaTransaccion, 
            "created_at" => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y')      
            

                       
        ];
    } 
}
