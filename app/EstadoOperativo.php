<?php namespace ResortTraffic;

use Illuminate\Database\Eloquent\Model;

class EstadoOperativo extends Model {

    public function reglas()
    {
        return $this->belongsTo('ResortTraffic\Reglas');
    }

   public function operacionCurso()
    {
        return $this->hasOne('ResortTraffic\OperacionCurso');
    }
   public function operacionConcluidas()
    {
        return $this->hasOne('ResortTraffic\OperacionConcluidas');
    }
   public function operacionCanceladas()
    {
        return $this->hasOne('ResortTraffic\OperacionCanceladas');
    }
   public function operacionExpirada()
    {
        return $this->hasOne('ResortTraffic\OperacionExpirada');
    }
   public function operacionReconciliadaDestino()
    {
        return $this->hasOne('ResortTraffic\OperacionreconciliadaDestino');
    }

}
