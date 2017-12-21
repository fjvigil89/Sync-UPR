<?php

namespace Api\Http\Controllers;

use Illuminate\Support\facades\Log as Log; 
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
    public function index()
    {
        //
        $doc= DocumentosSolicitar::all();
        return $doc;
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
        try{
            $doc = DocumentosSolicitar::create($request->all());           
            
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
        $doc = DocumentosSolicitar::find($id);
        return redirect()->json(
            $doc->toArray()
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
        $doc = DocumentosSolicitar::find($id);
        $doc->nombre=$request->input('nombre');
        $doc->descripcion =$request->input('descripcion');        

        $doc->save();
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
        $doc = DocumentosSolicitar::find($id);
        $doc->delete();
        return response()->json(['message'=>'borrado']);
    }
}
