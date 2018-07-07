@extends('layouts.layout')
 @section('content')
      
 <form class="navbar-form navbar-left" action="{{route ('createtrabajadores')}}" method="post">
                      {{ csrf_field() }}
  <div class="input-group">
    <input name="employeeid" type="text" class="form-control" placeholder="Search">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search"></i>
      </button>
    </div>
  </div>
</form> 
  @stop   
            
