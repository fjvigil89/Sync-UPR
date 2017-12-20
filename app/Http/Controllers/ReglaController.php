<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Api\Reglas;
use Api\Condicion;
use Api\Estacion;
use Api\Acciones;
class ReglaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $regla = Reglas::all();

        return response()->json(
            $regla
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
        $regla = new Reglas;
        $regla->nombre = $request->nombre;        
        $regla->descripcion= $request->descripcion;     
        $regla->activo= true;  
        $regla->save();

        $condicionescant= $request->condicionescant;
        if ($condicionescant >0 ) {
            for ($i=0; $i < $condicionescant ; $i++) { 

                if ($request->Input('estacion'.$i)== 0) {
                    //hacer un random
                    
                    $estacion=Estacion::find(random_int(1,5));
                }
                else
                    $estacion=Estacion::find($request->Input('estacion'.$i));                                          
                

                $condicion=new Condicion;                
                $condicion->nombre= $request->Input('condicion_estados'.$i);
                $condicion->tipo= $request->Input('condicion_es'.$i);
                $condicion->regla()->associate($regla);
                $condicion->estacion()->associate($estacion);
                $condicion->save();

                
               
            }
        }   

        $accionescant= $request->accionescant;
        if ($accionescant >0 ) {
            for ($i=0; $i < $accionescant ; $i++) { 
                $acicones=new Acciones;
                $acicones->nombre= $request->Input('accion_nombre'.$i);
                $acicones->asignacion= $request->Input('accion_asignacion'.$i);                
                $acicones->regla()->associate($regla);

                $acicones->save();

            }
        }

        


        return Redirect::to('sistema');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $regla = Reglas::find($id);
        return response()->json(
            $regla->toArray()
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
        $regla = Reglas::find($id);
        $regla->nombre = $request->nombre;        
        $regla->descripcion= $request->descripcion;        

        $regla->save();


        return Redirect::to('sistema');
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
        $regla = Reglas::find($id);
        $regla->delete();
        return response()->json(['message'=>'borrado']);
    }
}
