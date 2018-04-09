@extends('layouts.layout')

 @section('content')
  
<div class="table-responsive">
	<span><p>Cantidad de usuarios con servicio de {{$reporte}}: {{ $total }}</p></span>	
	<span><p>Tiempo de Ejecución: {{  $time }}</p></span>	
	
	<div class="row">        
        <div class="col-md-12">
            <label> {{ $reporte }}</label>
              <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Plaza</th>
                        <th>Oficina</th>                        
                        <th></th>
                    </tr>
                </thead>

                <tbody id="empleados_upr">
                    @foreach($arrayProcesados as $item)
                    <tr>
                        <td>{{ $item['cn'] }}</td>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $item['physicaldeliveryofficename']}}</td>
                        <td></td>                        
                    </tr>
                    @endforeach
                </tbody>


              </table>
        </div>
    </div>
</div>
@stop   