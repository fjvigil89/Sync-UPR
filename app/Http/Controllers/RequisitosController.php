<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use Log; 
use Api\Requisitos;
class RequisitosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doc= Requisitos::all();
        return response()->json($doc,200);
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
            Requisitos::create($request->all());           
            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $requisitos = Requisitos::find($id);
            if (!$requisitos) {
                return response("No existe el Documento", 404);
            }            
            return response()->json($requisitos, 200);
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
        
        try{
            if ($request->isMethod('patch')) 
            {

                $req = Requisitos::find($id);
                $req->activo= $request->activo;
                $req->save();
                return response()->json(['status'=>true, 'message'=>'Switch ejecutado correctamente'], 200);
            }
            $req = Requisitos::find($id);
            
            if (!$req) {
                return response("No existe el Documento", 404);
            } 
            
            $req->fill($request->all());            
            $req->save();            
            
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
            $req = Requisitos::find($id);
            if (!$req) {
                return response("No existe el Documento", 404);
            } 
            $req->delete();                       
            return response()->json($req, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar el Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
