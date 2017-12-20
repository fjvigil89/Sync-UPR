<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Carbon\Carbon;
use Api\Reserva;
use Api\Cliente;
use Api\Paquete;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $reserva=Reserva::all();
        
        return response()->json(
            $reserva
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         $cliente = Cliente::find($request->clienteid);
        $cliente->ultimaConexion = Carbon::now();       
        $cliente->ip = $_SERVER['REMOTE_ADDR']; 
        $cliente->save();                
        
        $paquete = Paquete::find($request->paqueteid);        
        $cliente->paquete()->attach($paquete->id,['cantAdulto' => $request->cantAdultos,
                                                 'cantidadMenores'=>$request->cantNinos,
                                                 'fechaLlegada'=> Carbon::createFromFormat('d/m/Y', $request->llegada),
                                                 'fechaSalida'=> Carbon::createFromFormat('d/m/Y', $request->salida),
                                                 'operacion'=>1,
                                                 'estacion_id'=>2

                                                 ]);

        Session::flash('messageok','La Reserva ha sido creada correctamente');
        return Redirect::to('clientes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $reserva=Reserva::find($id);
        
        return response()->json(
            $reserva
            );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
          //
        $reserva=Reserva::find($id);
        
        return response()->json(
            $reserva->toArray()
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
