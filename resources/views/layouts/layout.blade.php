<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Sync UPR</title>

    <link rel="icon" href="./images/favicon.ico">    

    <!-- css hechos con webpack-->        
    {!!Html::style('css/app.css')!!}
    {!!Html::style('css/sync.css')!!}
    
     <!-- js hechos con webpack-->    
     
    
    

  </head>
  <body>

     
           <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="{{ url('/') }}">Sync-UPR</a>
            </div>
            <ul class="nav navbar-nav">          
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Usuarios
                <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="{{ url('todos_users') }}">Todos los Usuarios </a></li>
                  <li><a href="{{ url('docente') }}">Docentes </a></li>
                  <li><a href="{{ url('no_docente') }}">No Docentes </a></li>
                  <li><a href="{{ url('estudiantes') }}">Estudiantes</a></li>
                  <li><a href="{{ url('adiestrados') }}">Adiestrados</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Servicios
                <span class="caret"></span></a>
                <ul class="dropdown-menu">                          
                  <li><a href="{{ url('internet_profes') }}">Internet Profesores </a></li>
                  <li><a href="{{ url('internet_no_docente') }}">Internet No Docentes </a></li>
                  <li><a href="{{ url('internet_est') }}">Internet Estudiantes </a></li>
                  <li><a href="{{ url('ras') }}">Ras </a></li>
                  <li><a href="{{ url('docentes_ras') }}">Docentes Ras </a></li>
                  <li><a href="{{ url('nodocentes_ras') }}">No Docentes Ras </a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Kuotas
                <span class="caret"></span></a>
                <ul class="dropdown-menu">      
                  <li><a href="{{ url('rector') }}">Rectores </a></li>
                  <li><a href="{{ url('doctor') }}">Doctores </a></li>
                  <li><a href="{{ url('master') }}">Master </a></li>
                  <li><a href="{{ url('cuadro') }}">Cuadros </a></li>
                </ul>
              </li>           
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                   <form class="navbar-form navbar-left" action="{{route ('busqueda')}}" method="post">
                      {{ csrf_field() }}
                      <div class="input-group">
                        <input name="search" type="text" class="form-control" placeholder="Search">
                        <div class="input-group-btn">
                          <button class="btn btn-default" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                          </button>
                        </div>
                      </div>
                    </form> 
              </li>
              @guest
                  <li><a href="{{ route('login') }}">Login</a></li>              
              @else
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                      {{ Auth::user()->name }} <span class="caret"></span>
                  </a>

                  <ul class="dropdown-menu">
                      <li>
                          <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                              Logout
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                          </form>
                      </li>
                  </ul>
              </li>
              @endguest
            </ul>
          </div>
        </nav> 
        
     
         
     <div class="container" >  
      
      
        @yield('content')
        
             
        @yield('script')
     </div>     

     {!!Html::script('js/sync.js')!!}
        

         
  </body>
</html>