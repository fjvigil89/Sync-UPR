<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Log;
use Api\RespuestasDefinidas;
use Api\DocumentosAdjuntos;
use Api\Respuestas_Adjuntos;
use Api\Estacion;
class RespuestasDefinidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $resp= RespuestasDefinidas::all();  

        return response()->json($resp,200);
    }

 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{
            $estacion = Estacion::find($request->disponible);            
            $respuestasDefinidas = RespuestasDefinidas::create($request->all());           
            $respuestasDefinidas->estacion()->associate($estacion);

             $aux= $this->multiexplode(array(","),$request->adjuntos);


            for ($i=0; $i <count($aux)-1 ; $i++) { 
                # code...
                if($aux[$i]!= "")
                    $respuestasDefinidas->documentosAdjuntos()->attach($aux[$i]);

            }
            $respuestasDefinidas->save();
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
        
        
    }

    public function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
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
            $resp = RespuestasDefinidas::find($id);
            if (!$resp) {
                return response("No existe el Documento", 404);
            }            
            return response()->json($resp, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede Mostrar el Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            if ($request->isMethod('patch')) 
            {

                $respuestasDefinidas = RespuestasDefinidas::find($id); 
                $respuestasDefinidas->activo= $request->activo;
                $respuestasDefinidas->save();
                return response()->json(['status'=>true, 'message'=>'Switch ejecutado correctamente'], 200);
            }
            $estacion = Estacion::find($request->disponible);            
            
            $respuestasDefinidas = RespuestasDefinidas::find($id);           
            
            if (!$respuestasDefinidas) {
                return response("No existe la Respuesta", 404);
            } 
            
            $respuestasDefinidas->fill($request->all());  
            $respuestasDefinidas->estacion()->associate($estacion); 

            

            $aux= $this->multiexplode(array(","),$request->adjuntos);


            $arraAdjuno=Array();
            for ($i=0; $i <count($aux)-1 ; $i++) { 
                # code...
                if($aux[$i]!= "")
                    var_dump($arraAdjuno,$aux[$i]);                    

            }
            if (!empty($arraAdjuno)) {
                $respuestasDefinidas->documentosAdjuntos()->sync([$aux[$i]]);
            }            

            $respuestasDefinidas->save();
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede actualizar el Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $resp = RespuestasDefinidas::find($id);
            if (!$resp) {
                return response("No existe la Respuesta", 404);
            } 
            $resp->delete();                       
            return response()->json($resp, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar la Respuesta:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
