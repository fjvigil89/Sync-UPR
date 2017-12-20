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
        return $hotel;
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
        $direccion= new Direccion;
        $direccion->pais= $request->pais;
        $direccion->ciudad= $request->idciudad;
        $direccion->codigoPostal= $request->codpostal;
        $direccion->idPais= $request->idpais;
        $direccion->calle= $request->idcalle;
        $direccion->longitud= $request->longitud;
        $direccion->latitud= $request->latitud;
        $direccion->save();

        $hotel = new Hotel;
        $hotel->direccion_id=$direccion->id;
        $hotel->nombre=$request->nombhotel;
        $hotel->smallName=$request->smallname;
        $hotel->descripcion=$request->descripcion;      
        $hotel->rating=$request->rating;
        $hotel->activo=true;        
        $hotel->save();     

        

        
        if($request->hasFile('ruta'))
        {
            
            foreach($request->file('ruta') as $media)
            {
                    $galeria=new Galeria;
                    $galeria->ruta = $media->getClientOriginalName();
                    $galeria->hotel()->associate($hotel);
                    \Storage::disk('local_galeria')->put($galeria->ruta,  \File::get($media));              
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
        


        Session::flash('messageok','El Hotel ha sido creado correctamente');    
        return Redirect::to('hoteles');
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
          $hotel = Hotel::find($id);
        //$collection = Collection::make($hotel);   

        return response()->json(
            $hotel->toArray()
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
        $id=$request->idhotel;      
        $hotel = Hotel::find($id);
        $direccion= Direccion::find($hotel->direccion_id);
        $direccion->pais= $request->pais;
        $direccion->ciudad= $request->idciudad;
        $direccion->codigoPostal= $request->codpostal;
        $direccion->idPais= $request->idpais;
        $direccion->calle= $request->idcalle;
        $direccion->longitud= $request->latitud;
        $direccion->latitud= $request->longitud;
        $direccion->save();
        
        //$hotel->direccion_id=$direccion->id;
        $hotel->nombre=$request->nombhotel;
        $hotel->smallName=$request->smallname;
        $hotel->descripcion=$request->descripcion;      
        $hotel->rating=$request->rating;
        $hotel->activo=true;    
        $hotel->save();     

        

        
        if($request->hasFile('ruta'))
        {
            
            foreach($request->file('ruta') as $media)
            {
                    $galeria=new Galeria;
                    $galeria->ruta = $media->getClientOriginalName();
                    $galeria->hotel()->associate($hotel);
                    \Storage::disk('local_galeria')->put($galeria->ruta,  \File::get($media));              
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
        

        Session::flash('messageok','El Hotel ha sido actualizado correctamente');   

        return Redirect::to('hoteles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        $hotel->delete();
        
        foreach ($hotel->galeria as $galeria) {        
            $galeria->delete();
            \Storage::delete($galeria->ruta);              
        }

        return response()->json(['message'=>'borrado']);
    }
}
