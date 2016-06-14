<?php
  include_once '../funciones.php';
  $confi = fopen("../config/config.php", "a") or die("Unable to open file!");
  
  if ($instalar == 1) {
    echo "La aplicación ya está instalada y configurada." . "<br>" . 
    "<a href='" . $urlbase . "index.php'>Volver al índice</a>";
  } else {
  
    if (isset($_POST['crear_db'])) {
        $servername = $_POST['db_servidor'];
        $username = $_POST['db_usuario'];
        $password = $_POST['db_clave'];
        $dbname = $_POST['db_nombre'];
        $urlbase = "/" . $_POST['urlbase'] . "/";
        $admin_clave = $_POST['admin_clave'];
        
        // Variable de instalación de la aplicación, salta a cero si hay algún error
        $instalar = 1;

        $conn = mysqli_connect($servername, $username, $password);

        if (!$conn) {
          $instalar = 0;
          echo "Conexión fallada: " . mysqli_connect_error() . "<br>";
        } else {
          // echo "Conexión realizada" . "<br>";
        }

        $sql = "CREATE DATABASE IF NOT EXISTS $dbname;";

        if (mysqli_query($conn, $sql)) {
          // echo "La base de datos se ha creado correctamente o ya existía" . "<br>";
        } else {
          $instalar = 0;
          echo "Error creando la base de datos: " . mysqli_error($conn) . "<br>";
        }

        $sql = "USE $dbname;";

        if (mysqli_query($conn, $sql)) {
          // echo "Base de datos seleccionada correctamente" . "<br>";
        } else {
          $instalar = 0;
          echo "Error al seleccionar la base de datos: " . mysqli_error($conn) . "<br>";
        }

        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
          login VARCHAR(25) NOT NULL PRIMARY KEY,
          password VARCHAR(100) NOT NULL,
          rol SET('administrador', 'jefe') NOT NULL,
          nombre VARCHAR(200) NOT NULL,
          apellidos VARCHAR(200) NOT NULL
        ) ENGINE = InnoDB;";

        if (mysqli_query($conn, $sql)) {
          // echo "La tabla usuarios se ha creado correctamente o ya existía" . "<br>";
        } else {
          $instalar = 0;
          echo "Error al crear la tabla usuarios: " . mysqli_error($conn) . "<br>";
        }

        $sql = "INSERT INTO usuarios(login, password, rol, nombre, apellidos)
                VALUES('administrador', password('$admin_clave'), 'administrador', 'Aitor', 'Igartua');";

        if (mysqli_query($conn, $sql)) {
          // echo "El usuario administrador se ha creado correctamente" . "<br>";
        } else {
          $instalar = 0;
          echo "Error creando el usuario administrador: " . mysqli_error($conn) . "<br>";
        }

        $sql = "INSERT INTO usuarios(login, password, rol, nombre, apellidos)
                VALUES('jefe', password('jefe'), 'jefe', 'Aitor', 'Igartua');";

        if (mysqli_query($conn, $sql)) {
          // echo "El usuario jefe se ha creado correctamente" . "<br>";
        } else {
          $instalar = 0;
          echo "Error creando el usuario jefe: " . mysqli_error($conn) . "<br>";
        }

        if ($instalar == 1) {
          
          fwrite($confi, "\n". "$" . "servername" . " = " . "'$servername'" . ";");
          fwrite($confi, "\n". "$" . "username" . " = " . "'$username'" . ";");
          fwrite($confi, "\n". "$" . "password" . " = " . "'$password'" . ";");
          fwrite($confi, "\n". "$" . "dbname" . " = " . "'$dbname'" . ";");
          fwrite($confi, "\n". "$" . "urlbase" . " = " . "'$urlbase'" . ";");      
          fwrite($confi, "\n". "$" . "instalar" . " = " . $instalar . ";");
          
          echo "La aplicación se ha instalado correctamente.";
          
        } else {
          echo "La instalación no se ha producido correctamente.";
        }
        
        mysqli_close($conn);

        echo "<br><em><a href='$urlbase/index.php'>Ir a la página principal de la aplicación</a></em>";
      
  }
  }
        //unlink("instalar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>eduGraph! Instalación</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" href="../img/iconv1.png" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="jumbotron text-center">
      <img class='pull-left' src='../img/chart.png' alt='eduGraph' />
      <h1>Instalación de <em>edu<span class="graph">Graph!</span></em></h1>
    </div>
    <div class="container">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
                <h3>Base de datos MySQL</h3>
                <div class="form-group">
                    <label class="control-label">Direccion del servidor</label>
                    <input name="db_servidor" class="form-control" type="text" required value="localhost">
                </div>
                <div class="form-group">
                    <label class="control-label">Usuario de la base de datos</label>
                    <input name="db_usuario" class="form-control" type="text" required placeholder="Usuario de la BD"> 
                </div>
                <div class="form-group">
                    <label class="control-label">Contraseña del usuario de la base de datos</label>
                    <input name="db_clave" class="form-control" type="password" required placeholder="Clave del usuario">
                </div>
                <div class="form-group">
                    <label class="control-label">Nombre de la base de datos</label>
                    <input name="db_nombre" class="form-control" type="text" required value="edugraph_db">
                </div>
                <div class="form-group">
                    <label class="control-label">Directorio raíz de la aplicación</label>
                    <input name="urlbase" class="form-control" type="text" required value="Direccion raíz sin /">
                </div>

                <h3>Usuario Administrador de la Web</h3>
                <div class="form-group">
                    <label class="control-label">Usuario</label>
                    <input class="form-control" type="text" disabled value="Administrador">
                </div>
                <div class="form-group">
                    <label class="control-label">Clave</label>
                    <input name="admin_clave" class="form-control" type="password" required placeholder="Clave del Administrador" />
                </div>
                <div class="form-group">
                    <input name="crear_db" class="btn btn-success" type="submit" value="Instalar">
                </div>
            </form>
          </div>
          <div class="col-md-2"></div>
      </div>
    </div>
  </body>
</html>