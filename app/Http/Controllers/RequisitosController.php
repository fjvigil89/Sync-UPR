<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;

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
        //
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
         $requisito = new Requisitos;
        $requisito->nombre=$request->input('nombre');
        $requisito->descripcion =$request->input('descripcion');
        $requisito->activo= false;

        $requisito->save();

        //Session::flash('message','Usuario creado exitosamente');
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
        $req = Requisitos::find($id);
        return response()->json(
            $req->toArray()
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
        $req = Requisitos::find($id);
        $req->nombre=$request->input('nombre');
        $req->descripcion =$request->input('descripcion');        

        $req->save();
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
        $requisito = Requisitos::find($id);
        $requisito->delete();
        return response()->json(['message'=>'borrado']);
    }
}
