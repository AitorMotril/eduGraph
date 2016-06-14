<?php 
  include_once 'config/config.php';
  include_once 'funciones.php';
  check_install();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>eduGraph! | Gestión de notas y estadísticas</title>
  <base href='<?php echo $urlbase;?>' target='_self'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" href="img/iconv1.png" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body id="page-top">
  
<!-- Cabecera con la imagen de logo y el lema de la página -->
<div class="container-fluid clearfix" id="toplogo"></div>

<!-- Menú superior de navegación fijo -->
<nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="77" id="nav01"></nav>

<!-- Cabecera de la página y texto -->
<div class="container-fluid">
  <h3 class="bg-3">eduGraph - Inicio</h3>
  
  <!-- Mensaje de error de usuario -->
  <div class="container-fluid text-center">
    <?php 
      error_form();
    ?>
  </div>
  
  <p>
    eduGraph! es una aplicación web para institutos y centros educativos, que permite la gestión de estadísticas y datos de los alumnos del centro.
    Permite realizar gráficos altamente visuales e informes en formato pdf para el tratamiento por los profesionales educativos. Está adaptada para funcionar tanto en ordenadores de trabajo como en teléfonos móviles y pantallas pequeñas.
  </p>
</div>

<!-- Carrusel de imágenes -->
<div class="container-fluid">
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active"> 
      <img src="pChart/grafico1.php?Seed=0.9&dibujo=drawBarChart&leyenda1=Test4" alt="New York">
      <div class="carousel-caption">
        <h3>Grafico 1</h3>
        <p>Falta leer array de la base de datos.</p>
      </div>
    </div>

    <div class="item">
      <img src="pChart/grafico2.php?Seed=0.9" alt="Chicago">
      <div class="carousel-caption">
        <h3>Grafico 2</h3>
        <p>Poder cambiar tambien el color por GET?</p>
      </div>
    </div>
    <div class="item">
      <img src="pChart/grafico4.php?Seed=0.9" alt="Chicago">
      <div class="carousel-caption">
        <h3>Grafico 4</h3>
        <p>Poder cambiar tambien el color por GET?</p>
      </div>
    </div>
    
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>

<!-- Formulario de inicio de sesión
<div class="container text-center" id="login">
  <form class="form-inline" role="form" name="autenticador" method="POST" action="autenticador.php" onsubmit="return validar();">
    <div class="form-group">
      <label for="login">Usuario:</label>
      <input type="text" class="form-control" name="loginb" id="login" placeholder="Introduce tu usuario">
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" name="passwordb" id="password" placeholder="Introduce tu contraseña">
    </div>
    <button type="submit" class="btn btn-default" name="entrar" value="entrar">Iniciar sesión</button>
  </form>
</div>
</div>
<br>
-->
  
<!-- Pie de página -->
<div class="container-fluid bg-4 text-center" id='foot01'></div>
<script src="script/javascript.js"></script>
</body>
</html>
