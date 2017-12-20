<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Api\Cliente;
use Api\Direccion;
use Api\Usuario;
use Api\Telefono;
use Api\Paquete;
use Api\Reserva;
use Session;
use Redirect;
use Carbon\Carbon;
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cliente = Cliente::all();       
        
        return $cliente;
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
        $direccion = new Direccion;
        $direccion->pais = $request->pais;
        $direccion->estado = $request->estado;
        $direccion->municipio = $request->municipio;
        $direccion->ciudad = $request->ciudad;
        $direccion->colonia  = $request->colonia;
        $direccion->calle = $request->calle;
        $direccion->numeroEx = $request->exterior;
        $direccion->numeroInt = $request->interior;
        $direccion->codigoPostal = $request->codigo;
        $direccion->save(); 

        $cliente = new Cliente;
        $cliente->nombre = $request->nombre;
        $cliente->apellido1 = $request->apellido1;
        $cliente->apellido2 = $request->apellido2;
        $cliente->genero = $request->genero;
        $cliente->email = $request->email;      
        
        $cliente->ultimaConexion = Carbon::now();       
        $cliente->ip = $_SERVER['REMOTE_ADDR']; 

        $cliente->direccion()->associate($direccion);               
        $cliente->save();
        
        
        for ($i=1; $i <3 ; $i++) 
        {       
            
            
            if($request->Input('telefono_tipo'.$i)!= null)
            {           
                $telefono= new Telefono;
                $telefono->tipo = $request->Input('telefono_tipo'.$i);      
                $telefono->pais = $request->Input('telefono_pais'.$i);          
                $telefono->area = $request->Input('telefono_area'.$i);          
                $telefono->numero = $request->Input('telefono_numero'.$i);

                $telefono->cliente()->associate($cliente);
                $telefono->save();
            }           
            
        }
        
        


        $usuario = Usuario::find(Auth::user()->id);
        $usuario->cliente()->attach($cliente->id);
        
        
        

        Session::flash('messageok','El cliente ha sido creado correctamente');
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
        $cliente = Cliente::find($id);

        $paquetes = $cliente->paquete;
        


        $info = array();
        
        $reservat = array();

        foreach ($paquetes as $paq) 
        {
            $reserva = array();

            $person = $paq->maximoAdulto + $paq->maximoNino;
            $personreserva = $paq->pivot->cantAdulto + $paq->pivot->cantidadMenores;

            $monto = $paq->precio;
            
            if($person < $personreserva && $paq->costosPersonaAdicional > 0)
            {
                $monto += ($personreserva-$person)*($paq->costosPersonaAdicional);
            }
            
            $cllegada = new Carbon($paq->pivot->fechaLlegada);
            $csalida = new Carbon($paq->pivot->fechaSalida);
            $dias = $csalida->diffInDays($cllegada);

            if($dias > $paq->cantidadDias && $paq->costoAdicional > 0)
            {
                $monto += ($dias - $paq->cantidadDias) * $paq->costoAdicional;
            }
            
            $cllegada = Carbon::createFromFormat('Y-m-d H:i:s', $paq->pivot->fechaLlegada)->format('d-m-Y');
            $ccreate = Carbon::createFromFormat('Y-m-d H:i:s', $paq->pivot->created_at)->format('d-m-Y');
            $salida = Carbon::createFromFormat('Y-m-d H:i:s', $paq->pivot->fechaSalida)->format('d-m-Y');
                        
            array_push($reserva, $paq->pivot->id);
            array_push($reserva, $ccreate); 
            array_push($reserva, $cllegada);
            array_push($reserva, $paq->hoteles[0]->direccion->ciudad);
            array_push($reserva, $paq->hoteles[0]->nombre);
            array_push($reserva, $paq->nombre);
            array_push($reserva, $monto);
            array_push($reserva, $dias);
            array_push($reserva, $salida);
            array_push($reserva, $paq->pivot->cantAdulto);
            array_push($reserva, $paq->pivot->cantidadMenores);
            array_push($reserva, $paq->id);     


            array_push($reservat, $reserva);

        }

        
        
        
        array_push($info,$cliente);

        array_push($info, $reservat);



        return response()->json($info,200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);              

        return response()->json(
            $cliente->toArray()
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
        $cliente = Cliente::find($id);
        $cliente->delete();
        return response()->json(['message'=>'borrado']);
    }
}
