<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;

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
        $documento= DocumentosAdjuntos::all();  

        return response()->json(
            $documento
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
        $documento= DocumentosAdjuntos::where('disponible','=', 1)->get();   

        return response()->json(
            $documento->toArray()
            );
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
        $estacion = Estacion::find($request->disponible);
            
            $respuestasDefinidas = new RespuestasDefinidas;
            $respuestasDefinidas->nombre = $request->nombre;
            $respuestasDefinidas->asunto= $request->asunto;
            $respuestasDefinidas->descripcion= $request->descripcion;
            $respuestasDefinidas->contenido= $request->contenido;
            $respuestasDefinidas->estacion()->associate($estacion); 

            $respuestasDefinidas->save();
            

            $aux= $this->multiexplode(array(","),$request->adjuntos);



            for ($i=0; $i <count($aux)-1 ; $i++) { 
                # code...
                if($aux[$i]!= "")
                    $respuestasDefinidas->documentosAdjuntos()->attach($aux[$i]);

            }
            Session::flash('messageok','La respuesta ha sido agregada correctamente');
        

        return Redirect::to('sistema');
        
        
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
        //
          $repDef= RespuestasDefinidas::find($id);
        return response()->json(
            $repDef->toArray()
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
        $array = Array();
        $respuesta = RespuestasDefinidas::find($id);

        array_push($array, $respuesta->id);
        array_push($array, $respuesta->nombre);
        array_push($array, $respuesta->asunto);
        array_push($array, $respuesta->descripcion);
        array_push($array, $respuesta->contenido);
        array_push($array, $respuesta->disponible);
        $cantidad=$respuesta->documentosAdjuntos()->count();
        $documento= DocumentosAdjuntos::where('disponible','=', 1)->get();  
        $docuArray=Array();
        $idRelacion=null;
        $aux=null;

        
        for ($j=0; $j < count($documento) ; $j++) {            
            for ($i=0; $i < $cantidad ; $i++) {               
                if($documento[$j]->id == $respuesta->documentosAdjuntos[$i]->id)
                {
                    
                    array_push($docuArray,"<input class='adjunto' name='adjunto' type='checkbox' checked value='".$respuesta->documentosAdjuntos[$i]->id."' id='".$respuesta->documentosAdjuntos[$i]->id."'>".$respuesta->documentosAdjuntos[$i]->nombre."</br>");
                    $idRelacion.=$respuesta->documentosAdjuntos[$i]->id.',';                    
                } 
            }            
        }
        
       //esto se resuerve con el metodo contain de un arreglo   
        //dd($docuArray);

         
        
        for ($i=0; $i < count($docuArray); $i++) { 
           //en la posicion 6 porque ya enterior mente se habia introducido  otros valores      
            $array[6][$i] = $docuArray[$i];
            
        }
        
        array_push($array, $idRelacion);

        //en la posision 8 esta la cantidad de iteraciones que hay       
        array_push($array, count($docuArray));       

        
        
         
        //return response()->json($array);
        header('Content-Type: application/json');
        return json_encode($array,JSON_FORCE_OBJECT);
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
        $estacion = Estacion::find($request->disponible);
            
            $respuestasDefinidas = RespuestasDefinidas::find($id);
            $respuestasDefinidas->nombre = $request->nombre;
            $respuestasDefinidas->asunto= $request->asunto;
            $respuestasDefinidas->descripcion= $request->descripcion;
            $respuestasDefinidas->contenido= $request->contenido;
            $respuestasDefinidas->estacion()->associate($estacion); 

            $respuestasDefinidas->save();
            

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

        
        Session::flash('messageok','La respuesta ha sido editada correctamente');       

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
        $resp = RespuestasDefinidas::find($id);
        $resp->delete();
        Session::flash('message','La respuesta ha sido eliminada correctamente');
        return response()->json(['message'=>'borrado']);
    }
}
