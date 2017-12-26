<?php

namespace Api\Http\Controllers;

use Log; 
use Illuminate\Http\Request;
use Session;
use Redirect;
use Api\DocumentosSolicitar;

class DocumentoSolicitarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()    {
        //
        $doc= DocumentosSolicitar::all();
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
            DocumentosSolicitar::create($request->all());           
            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

            Session::flash('messageok','Documento agregado correctamente'); 
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Documneto:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            Session::flash('messageerror','El Documento a Solicitar NO ha sido creado');
            return response("El Documento a Solicitar NO ha sido creado", 500);
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
            $doc = DocumentosSolicitar::find($id);
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
            $doc = DocumentosSolicitar::find($id);
            
            if (!$doc) {
                return response("No existe el Documento", 404);
            } 
            
            $doc->fill($request->all());            
            $doc->save();    
            Session::flash('messageok','Documento a Solicitar agregado correctamente');        
            
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
            $doc = DocumentosSolicitar::find($id);
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
