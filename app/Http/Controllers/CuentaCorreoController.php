<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Session;
use Redirect;
use Api\CuentasCorreo;
class CuentaCorreoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cuentas = CuentasCorreo::all();

        return response()->json(
            $cuentas
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

                $email = CuentasCorreo::create($request->all());
                return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Email:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
                    $email = CuentasCorreo::find($id);

                    $area= $request->areas_mensajeria;    

                    $a=Array();
                    for ($i=0; $i <count($area)-1 ; $i++) { 
                            # code...
                            if($area[$i]!= "")
                            {   
                               array_push($a, (int)$area[$i]); 
                            }

                        }
                    $email->areaMensajeria()->associate($a[0]);
                    $email->save();                   
                    return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
                }        
                $email = CuentasCorreo::find($id);
                $email->fill($request->all());
                $email->save();
                return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar un Email:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
    }
}
