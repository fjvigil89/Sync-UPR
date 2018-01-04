<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Session;
use Redirect;
use Api\Departamento;
use Api\Usuario;
class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $departamento = Departamento::all();        
        return response()->json(
            $departamento
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
        try{

            
            $departamento = Departamento::create($request->all());
            $departamento->cuentasCorreo()->associate($request->email);
            $usuarios= $this->multiexplode(array(","),$request->departamento_usuario);
            for ($i=0; $i <count($usuarios)-1 ; $i++) { 
                    # code...
                    if($usuarios[$i]!= "")
                    {   
                        $user = Usuario::find((int)$usuarios[$i]);
                        $user->departamento()->associate($departamento->id);
                        $user->save();                        
                    }

                }

            $departamento->save();
            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Departamento:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }

    public function multiexplode ($delimiters,$string) 
    {
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
         try{
            $departamento = Departamento::find($id);
            if (!$departamento) {
                return response("No existe el Departamento", 404);
            }            
            return response()->json(
                $departamento
                        ); 
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede Mostrar el Departamento:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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

                    $departamento = Departamento::find($id);
                    $usuario= $request->departamento_usuario;

                    if($usuario != "")
                    {
                        
                        $user = Usuario::find((int)$usuario);
                        $user->departamento()->associate($departamento->id);
                        $user->save(); 
                    }
                    
                    $usuarios= $request->departamento_usuario_add;            
                    for ($i=0; $i <count($usuarios)-1 ; $i++) { 
                            # code...
                            if($usuarios[$i]!= "")
                            {   
                                $user = Usuario::find((int)$usuarios[$i]);
                                $user->departamento()->associate($departamento->id);
                                $user->save();                        
                            }

                        }

                    $nouser== $request->usuario_datach;
                    if($nouser != "")
                    {
                        
                        $user = Usuario::find((int)$nouser);
                        $user->departamento_id=null;
                        $user->save(); 
                    }

                    $departamento->save();
                    return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
                } 

            $departamento = Departamento::find($id);
            $departamento->fill($request->all());
            $departamento->cuentasCorreo()->associate($request->email);
            $usuarios= $request->departamento_usuario;

            
            for ($i=0; $i <count($usuarios)-1 ; $i++) { 
                    # code...
                    
                    if($usuarios[$i]!= "")
                    {                           
                        $user = Usuario::find($usuarios[$i]);
                        
                        $user->departamento()->associate($departamento->id);
                        $user->save();                        
                    }

                    

                }
            
            $departamento->save();

            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
         
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Departamento:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
        //
         try{
            $departamento = Departamento::find($id);
            if (!$departamento) {
                return response("No existe el Departamento", 404);
            }            
            $departamento->delete();
            return response()->json($departamento, 200);   
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede Mostrar el Departamento:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
