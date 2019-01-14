@extends('layouts.layout')
 @section('content')
      

<div class="row">

 <form class="navbar-form navbar-left" action="{{route ('createtrabajadores')}}" method="post">
                      {{ csrf_field() }}
  <div class="col-md-6">
   <label>Crear un Trabajador</label>                   
  <div class="input-group">
    <input name="employeenumber" type="text" class="form-control" placeholder="worker number">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search"></i>
      </button>
    </div>
  </div>
</div>

</form> 


<form class="navbar-form navbar-left" action="{{route ('createstudent')}}" method="post">
                      {{ csrf_field() }}
  <div class="col-md-6">
   <label>Crear un Estudiante</label>                   
  <div class="input-group">
    <input name="employeenumber" type="text" class="form-control" placeholder="student number">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search"></i>
      </button>
    </div>
  </div>
</div>

</form> 

</div>

<div class="row">

<form class=" nav navbar-form navbar-left" action="{{route ('createuserpostgrado')}}" method="post">
                      {{ csrf_field() }}
  <div class="col-md-6">
    <label>OU dentro Postgrado</label>                   
      <div class="input-group">        
        <input name="NombrePostgrado" type="text" class="form-control" placeholder="course name">
      </div>
   <label>Crear un Estudiante Postgrado (separado por coma)</label>                   
      <div class="input-group">
        <textarea rows="10" cols="100" name="Nombre" class="form-control" placeholder="full name">
          </textarea>
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </div>
      </div>
  </div>
</form> 

</div>

  @stop   
            
