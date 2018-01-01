<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;

use Api\Servicio;
use Api\Hotel;
use Api\HotelPaquete;
use Api\Paquete;
use Api\Direccion;
use Api\Galeria;
use Api\Requisitos;
use Api\DocumentosSolicitar;
use Log;
use Session;
use Redirect;
class HotelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $hotel=Hotel::all();        
        return response()->json($hotel,200);
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
                
                $direccion= Direccion::create($request->all());                    
                $hotel = Hotel::create($request->all()); 
                
                $hotel->direccion_id=$direccion->id;
                $hotel->marca()->associate($request->idmarca);                
                if($request->has('ruta'))
                {
                    
                    foreach($request->ruta as $media)
                    {
                            $galeria=new Galeria;
                            $galeria->ruta = $media;
                            $galeria->hotel()->associate($hotel->id);                            
                            $galeria->save();                   
                        
                    }
                    
                }
                

                $disponible= $this->multiexplode(array(","),$request->servicios_disponibles);
                $destacado= $this->multiexplode(array(","),$request->servicios_destacados);  
                
                
                $igual=true;         
                for ($i=0; $i < count($disponible)-1 ; $i++) {         
                    for ($j=0; $j < count($destacado)-1 ; $j++) { 
                        if($disponible[$i]==$destacado[$j] )
                        {    
                            $igual=false;
                            break;               
                        }
                    }
                    if(!$igual)
                    {
                      $hotel->servicios()->attach($disponible[$i],['destacado' => true, 'disponible'=>true]);
                    }
                    else{
                      $hotel->servicios()->attach($disponible[$i],['destacado' => false, 'disponible'=>true]);
                    }
                    
                }
                $diff=true;
                for ($i=0; $i < count($destacado)-1 ; $i++) {         
                    for ($j=0; $j < count($disponible)-1 ; $j++) { 
                        if($destacado[$i]==$disponible[$j] )
                        {   
                            $diff=false;         
                            break;
                        }               
                    }
                    if($diff)
                    {
                        $hotel->servicios()->attach($destacado[$i],['destacado' => true, 'disponible'=>false]);
                    }            
                }
        
            
            $hotel->save();
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar el Hotel:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }

    public function multiexplode ($delimiters,$string) {
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
        try{
            $hotel = Hotel::find($id);
            if (!$hotel) {
                return response("No existe el hotel", 404);
            }            
            return response()->json($hotel, 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede mostrar el Hotel :{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            if ($request->isMethod('patch')) 
            {
                  
                $hotel = Hotel::find($id);
                $hotel->activo= $request->activo;                
                $hotel->save();
                return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);
            }              
            $hotel = Hotel::find($id);
            $direccion= Direccion::find($hotel->direccion->id);           

            $direccion->fill($request->all()); 
            $hotel->fill($request->all()); 
        
              if($request->has('ruta'))
                {
                    
                    foreach($request->ruta as $media)
                    {
                            $galeria=new Galeria;
                            $galeria->ruta = $media;
                            $galeria->hotel()->associate($hotel->id);                            
                            $galeria->save();                   
                        
                    }
                    
                }

            $disponible= $this->multiexplode(array(","),$request->servicios_disponibles);
            $destacado= $this->multiexplode(array(","),$request->servicios_destacados);  
            
        
            $igual=true;         
            for ($i=0; $i < count($disponible)-1 ; $i++) {         
                for ($j=0; $j < count($destacado)-1 ; $j++) { 
                    if($disponible[$i]==$destacado[$j] )
                    {    
                        $igual=false;
                        break;               
                    }
                }
                if(!$igual)
                {
                  $hotel->servicios()->sync([$disponible[$i],['destacado' => true, 'disponible'=>true]]);
                }
                else{
                  $hotel->servicios()->sync([$disponible[$i],['destacado' => false, 'disponible'=>true]]);
                }
                
            }
            $diff=true;
            for ($i=0; $i < count($destacado)-1 ; $i++) {         
                for ($j=0; $j < count($disponible)-1 ; $j++) { 
                    if($destacado[$i]==$disponible[$j] )
                    {   
                        $diff=false;         
                        break;
                    }               
                }
                if($diff)
                {
                    $hotel->servicios()->sync([$destacado[$i],['destacado' => true, 'disponible'=>false]]);
                }            
            }
            $direccion->save();
            $hotel->save();
            return response()->json(['status'=>true, 'message'=>'Muchas Gracias'], 200);

        }
        catch(\Exception $e)
        {
            Log::critical("No se puede agregar el Hotel:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
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
            $hotel = Hotel::find($id);
            if (!$hotel) {
                return response("No existe el hotel", 404);
            } 
            $hotel->delete(); 

           foreach ($hotel->galeria as $galeria) {        
            $galeria->delete();
            //\Storage::delete($galeria->ruta);              
            }   

            return response("El Hotel ha sido Eliminado", 200);
        }
        catch(\Exception $e)
        {
            Log::critical("No se puede eliminar el Hotel:{$e->getCode()}, {$e->getLine()}, {$e->getMessage()} ");
            return response("Alguna cosa esta mal", 500);
        }
    }

    public function hotelall($id)
    {

        $hotel = Hotel::find($id);
        $servicios = $hotel->servicios;
        $serviciostodos = Servicio::all();

        
        

        $info = array();

        foreach ($servicios as $ser) {
            $arr = array();
            array_push($arr,$hotel->descripcion);
            array_push($arr,$hotel->galeria);
            array_push($arr,$ser->id);
            array_push($arr, $ser->pivot->destacado);
            array_push($arr,$ser->nombre);
            array_push($arr,$serviciostodos);
            array_push($arr,$hotel->direccion->latitud);
            array_push($arr,$hotel->direccion->longitud);
            
            array_push($info,$arr);
        }


        return response()->json($info,200);


    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function hotelAllPaquete($id)
    {
        
        $paquete = HotelPaquete::where('hotel_id','=',$id)->get();
        $arrayPaquete = array();
        foreach ($paquete as $paq) {
            array_push($arrayPaquete, Paquete::find($paq->paquete_id));
        }
        

        return response()->json(
            $arrayPaquete
            ); 
    }
}
