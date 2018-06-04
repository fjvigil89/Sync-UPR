@extends('layouts.layout')

@section('content')
  
 <form action="{{route('apilogin')}}" method="POST">
  {{ csrf_field() }}
<div class="table-responsive" >	
	<div class="row">        
        <div class="col-md-2">
            <label> Usuario</label>
              <div>
                  <input type="text"  name="user" placeholder="Usuario">
              </div>
        </div>

        <div class="col-md-2">
            <label> Password</label>
              <div>
                  <input type="password" name="password" placeholder="Password">
              </div>
        </div>
        <div class="col-md-2">
            <label> attrib</label>
              <div>
                  <input type="text" name="attrib" placeholder="Atributos">
              </div>
        </div>
        <div class="col-md-2">            
            <label>   </label>
              <div>
                  <input type="submit" placeholder="Enviar">
              </div>
        </div>
    </div>

</div>
</form>
@stop   