<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Api\DocumentosAdjuntos;
use Log; 
class DocumentosAdjuntosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        
        $doc= DocumentosAdjuntos::all();
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
            DocumentosAdjuntos::create($request->all());  
            \Storage::disk('local')->put($adjunto->nombre,  \File::get($request->file('ruta')));         
            
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
        try{
            $doc = DocumentosAdjuntos::find($id);
            if (!$doc) {
                return response("No existe el Documento", 404);
            }            
            return response()->json($doc, 200);
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
            $doc = DocumentosAdjuntos::find($id);
            
            if (!$doc) {
                return response("No existe el Documento", 404);
            } 
            
            $doc->fill($request->all());            
            $doc->save();            
            
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
            $doc = DocumentosAdjuntos::find($id);
            if (!$doc) {
                return response("No existe el Documento", 404);
            } 
            $doc->delete();                       
            return response()->json($doc, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar el Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
