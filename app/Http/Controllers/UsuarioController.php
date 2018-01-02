<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Api\User;
use Api\Usuario;
use Session;
use Redirect;
use Hash;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuario = Usuario::all();
        return response()->json(
            $usuario
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Usuario::all();
        return response()->json(
            $usuario
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
        $usuario = new Usuario;
        $usuario->username=$request->input('username');
        $usuario->apellidos =$request->input('apellidos');
        $usuario->rol   =$request->input('rol');
        

        $user   =   new User;
        $user->name =   $request->input('nombre');
        $user->email    =   $request->input('email');
        $user->password =   Hash::make($request->input('password'));
        $user->remember_token = Hash::make(csrf_token());       
        $user->save();
        $usuario->user()->associate($user);     
        $usuario->save();

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
        $usuario = Usuario::find($id);
        return response()->json(
            $usuario->toArray()
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
        $usuario = Usuario::find($id);
        return response()->json(
            $usuario->toArray()
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
        $usuario = Usuario::find($id);      
        
        $usuario->username=$request->input('username');
        $usuario->apellidos =$request->input('apellidos');
        $usuario->rol=$request->input('rol'); 
        $user   =User::find($usuario->user_id);
        
        $user->name =   $request->input('nombre');
        $user->email    =   $request->input('email');
        $user->password =   bcrypt( $request->input('password') );
        $user->save();      
        $usuario->save();

        //Session::flash('message','Usuario actualizado exitosamente');
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
        $usuario=Usuario::find($id);    
            
        User::destroy($usuario->user_id);   
        
        $usuario->delete(); 
        return response()->json(['message'=>'borrado']);
    }
}
