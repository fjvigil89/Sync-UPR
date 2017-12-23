<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Api\Cliente;
use Api\Direccion;
use Api\Usuario;
use Api\Telefono;
use Api\Paquete;
use Api\Reserva;
use Session;
use Redirect;
use Carbon\Carbon;
use Log;
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cliente = Cliente::all();               
        return $cliente;

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
              $direccion = Direccion::create($request->all());           
              $cliente = Cliente::create($request->all()); 

              $cliente->direccion()->associate($direccion); 
              $cliente->save();  

            for ($i=1; $i <3 ; $i++) 
            {       
            
            
                if($request->Input('telefono_tipo'.$i)!= null)
                {           
                    $telefono= new Telefono([
                        'tipo' => $request->Input('telefono_tipo'.$i),      
                        'pais' => $request->Input('telefono_pais'.$i),          
                        'area' => $request->Input('telefono_area'.$i),          
                        'numero' => $request->Input('telefono_numero'.$i),
                        ]);

                        $telefono->cliente()->associate($cliente);
                        $telefono->save();
                    
                }           
            
            } 


            //$usuario = Usuario::find(Auth::user()->id);
            //$usuario->cliente()->attach($cliente->id);
            
               
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar el Cliente:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $cliente = Cliente::find($id);
            if (!$cliente) {
                return response("No existe el Cliente", 404);
            }            
            return response()->json($cliente, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede mostrar el Cliente:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $cliente = Cliente::find($id);
            
            if (!$cliente) {
                return response("No existe el Documento", 404);
            } 
            $direccion = Direccion::find($cliente->direccion->id);
            if (!$direccion) {
                return response("No existe el Documento", 404);
            } 

            $direccion->fill($request->all());            
            $direccion->save();  

            $cliente->fill($request->all());            
            $cliente->save();  


            $telefono= Telefono::where('cliente_id','=',$cliente->id)->get();
            

            
            if($telefono)
            {   
                $j=1;
                for ($i=0; $i <count($telefono) ; $i++) 
                {            
                    $telefono[$i]['tipo']=$request->Input('telefono_tipo'.$j);
                    $telefono[$i]['pais']=$request->Input('telefono_pais'.$j);
                    $telefono[$i]['area']=$request->Input('telefono_area'.$j);
                    $telefono[$i]['numero']=$request->Input('telefono_numero'.$j);
                    
                    $telefono[$i]->save();

                    $j+=1;

                                
                }

                if(count($telefono)<2)
                {
                    if($request->Input('telefono_tipo2')!= null)
                    {           
                        $telefono= new Telefono([
                            'tipo' => $request->Input('telefono_tipo2'),      
                            'pais' => $request->Input('telefono_pais2'),          
                            'area' => $request->Input('telefono_area2'),          
                            'numero' => $request->Input('telefono_numero2'),
                            ]);

                            $telefono->cliente()->associate($cliente);
                            $telefono->save();
                        
                    }   
                }
            } 




            //$usuario = Usuario::find(Auth::user()->id);
            //$usuario->cliente()->attach($cliente->id);
                      
            
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {

            Log::critical("No se puede actualizar el Cliente:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $cliente = Cliente::find($id);
            if (!$cliente) {
                return response("No existe el Cliente", 404);
            } 
            $cliente->delete();                       
            return response("El Cliente ha sido Eliminado", 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar el Cliente:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }
}
