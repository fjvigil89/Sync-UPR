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

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="/css/indice.css" rel="stylesheet">    
    <link href="css/bootstrap.css" rel="stylesheet">

    <script src="/js/pace.min.js"></script>    
    <script src="/js/app.js"></script>    
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.js"></script>    
    
    

  </head>
  <body width="100%">

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
          <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
      </div>
    </nav> 
    
    @yield('content')
    
         
    @yield('script')
         
  </body>
</html>