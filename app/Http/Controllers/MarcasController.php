<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Session;
use Redirect;
use Api\Marca;
class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $marcas=Marca::all();        
        return response()->json($marcas,200);
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
                
                $marca= Marca::create($request->all());                                                    
                $marca->save();


            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar la Marca:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
        //
        try{
            
             $marca= Marca::find($id);
            if (!$marca) {
                return response("No existe la marca", 404);
            }            
            return response()->json($marca, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede mostrar la marca :{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
        //
        try{                
                $marca= Marca::find($id);                                                    
                $marca->fill($request->all()); 
                $marca->save();


            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar la Marca:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
        //
        try{
            $marca= Marca::find($id);
            if (!$marca) {
                return response("No existe la marca", 404);
            }
            $marca->delete();            

            return response("La Marca ha sido Eliminado", 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar la Marca:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
