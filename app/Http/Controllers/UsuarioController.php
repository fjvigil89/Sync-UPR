<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Api\User;
use Api\Usuario;
use Session;
use Redirect;
use Hash;
use Log; 
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{
            $usuario = Usuario::create($request->all());                       

            $user=User::create($request->all());
            
            $usuario->user()->associate($user);     
            $usuario->save();

            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Usuario:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return response("No existe el Usuario", 404);
            }            
            return response()->json($usuario, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede Mostrar el Usuario:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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

                    $usuario = Usuario::find($id);    
                    $usuario->activo= $request->activo;                
                    $usuario->save();
                    return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
                }  
            
            
            $usuario = Usuario::find($id);    
            $usuario->fill($request->all());

            $user=User::find($usuario->user->id);
            $user->fill($request->all());
            $user->save();

            $usuario->user()->associate($user);     
            $usuario->save();

            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Usuario:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return response("No existe el Usuario", 404);
            }
            User::destroy($usuario->user_id);
            $usuario->delete();             
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede Mostrar el Usuario:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
