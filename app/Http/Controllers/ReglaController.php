<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Api\Reglas;
use Api\Condicion;
use Api\Estacion;
use Api\Acciones;
use Log;
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
         return response()->json($regla,200);
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
        try{
            $regla = Reglas::create($request->all());           
            
            $condicionescant= $request->condicionescant;
            if ($condicionescant >0 ) {
                for ($i=0; $i < $condicionescant ; $i++) { 

                    $condicion=new Condicion;                
                    $condicion->nombre= $request->Input('condicion_estados'.$i);
                    $condicion->tipo= $request->Input('condicion_es'.$i);
                    $condicion->regla()->associate($regla);
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

            return response()->json(['status'=>true, 'message'=>'Regla agregada correctamente'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar una Regla:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $regla = Reglas::find($id);
            if (!$regla) {
                return response("No existe la regla", 404);
            }            
            return response()->json($regla, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede mostrar la Regla:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        } 
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
        try{

            if ($request->isMethod('patch')) 
            {

                $regla = Reglas::find($id); 
                $regla->activo= $request->activo;                
                $regla->save();
                return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
            }  
            $regla = Reglas::find($id);           
            $regla->fill($request->all());  
            
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

            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar una Regla:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        try{
            $regla = Reglas::find($id);
            if (!$regla) {
                return response("No existe la regla", 404);
            } 
            $regla->delete();                       
            return response("La Regla ha sido Eliminada", 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar la Regla:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
