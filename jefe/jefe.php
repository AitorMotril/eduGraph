<?php
  include_once '../config/config.php';
  include_once '../funciones.php';
  check_install();
  protege("jefe" || "administrador");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Jefe de estudios | eduGraph!</title>
  <base href='<?php echo $urlbase;?>' target='_self'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" href="img/iconv1.png" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
  
<!-- Cabecera con la imagen de logo y el lema de la página -->
<div class="container-fluid clearfix" id="toplogo"></div>

<!-- Menú superior de navegación fijo -->
<nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="77" id="nav01"></nav>

<!-- Cabecera de la página y texto -->
<div class="container-fluid">
  <h3 class="bg-3">Jefe de estudios</h3>
  <div class="row">
    
    <div class="col-md-2">
      <div class="list-group" id="sidebar">
        <a href='jefe/notas.php' class="list-group-item active">Principal jefe de estudios</a>
        <a href='jefe/notas.php' class="list-group-item">Subir notas</a>
        <a href="jefe/graficos.php" class="list-group-item">Crear gráficos</a>
        <a href="jefe/asignaturas.php" class="list-group-item">Gestión de asignaturas</a>
      </div>
    </div>
    
    <div class="col-md-10">
      <h4>Página principal del jefe de estudios</h4> 
      <ul>
        <li>Subir notas, mediante archivos CSV de Séneca, o añadir notas manualmente, así como consultar las notas de los alumnos.</li>
        <li>Creación y visualización de gráficos, por asignaturas, por alumnos, por dimensiones competenciales... Y comparativas.</li>
        <li>Gestionar las asignaturas de la base de datos, añadir nombres completos y asignarlas a dimensiones competenciales.</li>
      </ul>
    </div>
    
  </div>
</div>
    
<!-- Pie de página -->
<div class="container-fluid bg-4 text-center" id='foot01'></div>
<script src="script/javascript.js"></script>
</body>
</html>