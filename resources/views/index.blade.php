<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cambio de Contraseña UPR</title>

    <link rel="icon" href="./images/favicon.ico">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>
    
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-offset-5 col-md-3">
            <img src="./images/password.svg" class="img-responsive" alt="Responsive image">            
          </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-offset-5 col-md-3">
              <h1>Cambie su contraseña</h1>
          </div>
        </div>

        <br />

        <div class="row">
          <div class="col-md-offset-1 col-md-7">
            <div class="alert alert-danger" role="alert">
              <h2>Leer IMPORTANTE!!!</h2>
              <ol>                
                  <li>Tenga en cuenta que después de esta acción tendrá que usar su nueva contraseña para acceder a todos los servicios de la UPR. </li>

                  <li>Políticas para la nueva contraseña</li>
                  <li>La contraseña debe tener al menos 7 caracteres.</li>
                  <li>El sistema guarda las últimas 8 contraseñas y no podrán ser usadas de nuevo.</li>
                  <li>La contraseña debe ser construida usando números, mayúsculas, minúsculas y caracteres especiales.</li>
                  <li>La contraseña NUNCA puede contener partes de su nombre o de su usuario.</li>

                  <li>Solo se podrá cambiar la contraseña una vez en el día.</li>
                  <li>La contraseña funciona por solo 6 meses, antes de ese tiempo usted tendrá que cambiarla.</li>
                  
                  <li>Nota Importante: La Dirección de Informatización NUNCA pide contraseña a nuestros usuarios. </li>
              </ol>
            </div>
          </div>
        
          <div class="col-offset-1 col-md-3">
            <form action="{{ url('change') }}" method="get">
              <div class="form-group">
                <label for="user">Usuario</label>
                <input type="text" class="form-control" placeholder="Usuario" aria-describedby="user" name="username">
              </div>
              <div class="form-group">
                <label for="password">Contraseña actual</label>
                <input type="password" class="form-control" placeholder="Contraseña actual" aria-describedby="password" name="passwd">
              </div>
              <div class="form-group">
                <label for="newPassword">Nueva contraseña</label>
                <input type="password" class="form-control" placeholder="Nueva contraseña" aria-describedby="password" name="newpasswd" id="password">
              </div>
              <div class="form-group">
                <label for="repeatNewPassword">Repetir nueva contraseña</label>
                <input type="password" class="form-control" placeholder="Repetir nueva contraseña" aria-describedby="password" id="repetir_password" name="repetir_password">  
              </div>
              <button type="submit" class="btn btn-primary">Cambiar</button>
            </form>
          </div>       
        </div>
      </div>   

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">      
        var password = document.getElementById("password");
        var confirm_password = document.getElementById("repetir_password");

          $(document).on('change', '#password', function(){          
            validatePassword();
          })
          $(document).on('keyup', '#repetir_password', function(){          
            validatePassword(); 
          })

          function validatePassword(){
            if(password.value != confirm_password.value) {
              confirm_password.setCustomValidity("Las contraseñas no coinciden");
            } else {
              confirm_password.setCustomValidity('');
            }
          }


    </script>
  </body>
</html>