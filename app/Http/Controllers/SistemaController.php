<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Api\DocumentosSolicitar;
use Api\Requisitos;
use Api\Usuario;
use Api\DocumentosAdjuntos;
use Api\RespuestasDefinidas;
use Api\Reglas;

class SistemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user   =Usuario::all();    
        $documentoSolicitar =DocumentosSolicitar::all();    
        $requisitos =Requisitos::all(); 
        $documentosAdjuntos =DocumentosAdjuntos::all(); 
        
        $respuestas =RespuestasDefinidas::all();
        $reglas=Reglas::all();  
        
        return view('resorttraffic.sistema',[
            'users'=>$user,
            'documentos_solicitar'=>$documentoSolicitar,
            'requisitos'=>$requisitos,
            'documentos_adjuntos'=>$documentosAdjuntos,
            'respuestas_definidas'=>$respuestas,
            'reglas'=>$reglas
            ]);
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
        //
    }
}
