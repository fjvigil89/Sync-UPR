<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Api\Paquete;
use Api\PaqueteRequisito;
class PaquetesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paq= Paquete::all();
        return response()->json(
            $paq
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
        if($request->has('hotel')){
            $paquete=new Paquete;
            $paquete->nombre=$request->nombre;
            $paquete->tipo=$request->tipo;
            $paquete->precio=$request->precio;
            $paquete->moneda=$request->moneda;
            $paquete->maximoAdulto=$request->maxAdulto;
            $paquete->maximoNino=$request->maxNino;
            $paquete->cantidadDias=$request->dias;
            $paquete->cantidadNoches=$request->noches;
            $paquete->costoAdicional=$request->diaAdd;
            $paquete->costosPersonaAdicional=$request->personAdd;
            $paquete->costosXcancelacion=$request->costoCancelacion;
            if ($request->has('costo_aplazo_1')) {
                $paquete->costosXaplazar=$request->costo_aplazo_1;    
            }
            else{
                $paquete->costosXaplazar=0;
            }
            if ($request->has('costo_aplazo_2')) {
                $paquete->costosXaplaza2=$request->costo_aplazo_2;    
            }
            else{
                $paquete->costosXaplaza2=0;
            }
            if ($request->has('costo_aplazo_3')) {
                $paquete->costosXaplaza3=$request->costo_aplazo_3;    
            }
            else{
                $paquete->costosXaplaza3=0;
            }
                 
            $paquete->rating=3;
            $paquete->activo=0;
            //dd("'".$request->disp_paq_new."'");

            $paquete->disponible=$request->disp_paq_new;
            $paquete->save();
            $paquete->hoteles()->attach($request->hotel);
            

            //relacion con requisitos
            $requisito= $this->multiexplode(array(","),$request->paquete_requisito);

            for ($i=0; $i <count($requisito)-1 ; $i++) { 
                # code...
                if($requisito[$i]!= ""){                
                    $paquete->requisitos()->attach($requisito[$i]);                
                }

            }

            //relacion con documnetos solicitadoc
            $documento= $this->multiexplode(array(","),$request->paquete_documentos);
            for ($i=0; $i <count($documento)-1 ; $i++) { 
                # code...
                if($documento[$i]!= "")
                    $paquete->documentosSolicitar()->attach($documento[$i]);

            }


         Session::flash('messageok','El paquete ha sido creado correctamente'); 
        }
        else
            Session::flash('messageerror','El paquete NO ha sido creado');

        return Redirect::to('hoteles');
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
        $paquete=Paquete::find($id);
        
        return response()->json(
            $paquete->toArray()
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
        if($request->has('hotel')){
            $paquete=Paquete::find($id);
            $paquete->nombre=$request->nombre;
            $paquete->tipo=$request->tipo;
            $paquete->precio=$request->precio;
            $paquete->moneda=$request->moneda;
            $paquete->maximoAdulto=$request->maxAdulto;
            $paquete->maximoNino=$request->maxNino;
            $paquete->cantidadDias=$request->dias;
            $paquete->cantidadNoches=$request->noches;
            $paquete->costoAdicional=$request->diaAdd;
            $paquete->costosPersonaAdicional=$request->personAdd;
            $paquete->costosXcancelacion=$request->costoCancelacion;
            $paquete->costosXaplazar=$request->costo_aplazo_1;
            $paquete->costosXaplaza2=$request->costo_aplazo_2;
            $paquete->costosXaplaza3=$request->costo_aplazo_3;                
            $paquete->rating=3;
            $paquete->activo=0;
            //dd("'".$request->disp_paq_new."'");

            $paquete->disponible=$request->disp_paq_new;
            $paquete->save();
            $paquete->hoteles()->sync([$request->hotel]);
            

            //relacion con requisitos
            $requisito= $this->multiexplode(array(","),$request->paquete_requisito);
            $requArray-Array();
            for ($i=0; $i <count($requisito)-1 ; $i++) { 
                # code...
                if($requisito[$i]!= ""){                
                    var_dump($requArray,$requisito[$i]);
                }

            }
            $paquete->requisitos()->sync([$requArray]);                

            //relacion con documnetos solicitadoc
            $documento= $this->multiexplode(array(","),$request->paquete_documentos);
            $docArray-Array();
            for ($i=0; $i <count($documento)-1 ; $i++) { 
                # code...
                if($documento[$i]!= "")
                {
                    var_dump($docArray,$documento[$i]);
                }
                    
                

            }
            $paquete->documentosSolicitar()->sync([$docArray]);


         Session::flash('messageok','El paquete ha sido creado correctamente'); 
        }
        else
            Session::flash('messageerror','El paquete NO ha sido creado');

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
        //
    }
}
