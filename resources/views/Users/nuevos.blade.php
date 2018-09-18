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
  @stop   
            
