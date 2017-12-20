<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Api\DocumentosAdjuntos;
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
        return response()->json(
            $doc
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
        $adjunto = new DocumentosAdjuntos;  

        
 
       //indicamos que queremos guardar un nuevo archivo en el disco local
       
        if(empty($request->input('nombre')))
        {
           $adjunto->nombre = $request->file('ruta')->getClientOriginalName();
        }        
        else
            $adjunto->nombre =$request->nombre;                 

        $adjunto->disponible= false;

        \Storage::disk('local')->put($adjunto->nombre,  \File::get($request->file('ruta')));              

        $adjunto->save();
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
        //
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
        $doc = DocumentosAdjuntos::find($id);
        \Storage::delete($doc->nombre);  
        $doc->delete();
                    

        return response()->json(['message'=>'borrado']);
    }
}
