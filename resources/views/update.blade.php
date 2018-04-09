@extends('layouts.layout')

@section('content')
  
<div class="table-responsive">
	<span><p>Cantidad de procesados: {{  $update }} de {{ $total }}</p></span>	
	<span><p>Tiempo de Ejecución: {{  $time }}</p></span>	
	<span><p>Cantidad de NO procesados: {{  $noUpdate }}</p></span>
	<div class="row">        
        <div class="col-md-6">
            <label> Con Errores para Syncronizar</label>
              <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>usuario</th>
                        <th>#-Trabajador</th>
                        <th>#-CI</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="empleados_upr">
                	@foreach($array as $item)
                	<tr>
                        <td>{{ $item['givenname'][0] }}</td>
                        <td>{{ $item['sn'][0] }}</td>
                        <td>{{ $item['samaccountname'][0] }}</td>
                        <td>{{ $item['employeenumber'][0] }}</td>
                        <td>{{ $item['employeeid'][0] }}</td>                        
                    </tr>
                	@endforeach
                </tbody>


              </table>
        </div>

        <div class="col-md-6">
            <label> Syncronizados</label>
              <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>usuario</th>
                        <th>#-Trabajador</th>
                        <th>#-CI</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id="empleados_upr">
                    @foreach($arrayProcesados as $item)
                    <tr>
                        <td>{{ $item['givenname'][0] }}</td>
                        <td>{{ $item['sn'][0] }}</td>
                        <td>{{ $item['samaccountname'][0] }}</td>
                        <td>{{ $item['employeenumber'][0] }}</td>
                        <td>{{ $item['employeeid'][0] }}</td>                        
                    </tr>
                    @endforeach
                </tbody>


              </table>
        </div>
    </div>
</div>

@stop   