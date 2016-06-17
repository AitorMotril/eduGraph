<?php
include_once 'config/config.php';

/* Funcion activar_curso($file)
 * A partir de leer un archivo csv con los datos de un alumno, activa un curso
 * args: $file archivo abierto, $cursoActivar identificador del curso con el que
 * se concatenarán los nombres de las tablas, $nombreCursoActivar, nombre más
 * descriptivo del curso
 * return: escribir el curso y su nombre activados en cursoActivo.php o 
 * en cursoPrueba.php si se está activando el curso de prueba */
function activar_curso($file, $cursoActivar, $nombreCursoActivar) {
  
  global $servername, $username, $password, $dbname;
  $datos = fgetcsv($file, 0, ',', '"');
  $arrlength = count($datos);
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  
  // Variable de activación del curso, salta a cero si hay algún error
  $activar = 1;
  
  $sql = "INSERT IGNORE INTO cursos VALUES(" . "'$cursoActivar'" . ", " . "'$nombreCursoActivar'" . ");";
  
  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al insertar el curso: " . mysqli_error($conn) . "<br>";
  }
  
  // Crear tabla de alumnos
  $sql = "CREATE TABLE IF NOT EXISTS alumnos" . $cursoActivar . " (";

  for ($x = 0; $x < $arrlength; $x++) {
    $var = $datos[$x];
    $var = sanear_string($var);
    
    if ($x != ($arrlength - 1)) {
      if ($var === "N_Id_Escolar") { //fijar la clave primaria
        $sql .= $var . " VARCHAR(40)" . " PRIMARY KEY, ";
      } else {
        $sql .= $var . " VARCHAR(40)" . ", ";
      }
    } else { //añadir el código de ENGINE al último registro
      $sql .= $var . " VARCHAR(40)" . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    }
    
  }
  
  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al crear la tabla alumnos: " . mysqli_error($conn) . "<br>";
  }
  
  // Crear tabla de cabeceras, tabla especial con una sola fila,
  // que asocia los nombres completos de los campos de Séneca, al valor saneado
  $sql = "CREATE TABLE IF NOT EXISTS cabecera" . $cursoActivar . " (";

  for ($x = 0; $x < $arrlength; $x++) {
    $var = $datos[$x];
    $var = sanear_string($var);
    if ($x != ($arrlength - 1)) {
      $sql .= $var . " VARCHAR(40)" . ", ";
    } else {
      $sql .= $var . " VARCHAR(40)" . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    }
  }

  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al crear la tabla cabecera: " . mysqli_error($conn) . "<br>";
  }

  // Crear tabla de asignaturas
  $sql = "CREATE TABLE IF NOT EXISTS asignaturas" . $cursoActivar . " ( "
          . "id_asignatura VARCHAR(10) NOT NULL PRIMARY KEY, "
          . "nombre_completo VARCHAR(255), "
          . "area_competencial VARCHAR(255)) ENGINE=InnoDB;";

  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al crear la tabla asignaturas: " . mysqli_error($conn) . "<br>";
  }
  
  // Crear tabla de evaluaciones
  $sql = "CREATE TABLE IF NOT EXISTS evaluaciones" . $cursoActivar . " ( "
          . "id_evaluacion VARCHAR(10) NOT NULL PRIMARY KEY, "
          . "nombre_evaluacion VARCHAR(255)) ENGINE=InnoDB;";

  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al crear la tabla evaluaciones: " . mysqli_error($conn) . "<br>";
  }

  // Crear tabla de notas
  $sql = "CREATE TABLE IF NOT EXISTS notas" . $cursoActivar . " ( " .
         "N_Id_Escolar VARCHAR(40) NOT NULL,
         id_evaluacion VARCHAR(10) NOT NULL,
         id_asignatura VARCHAR(10) NOT NULL,
         Nota INT,
         FOREIGN KEY (N_Id_Escolar) REFERENCES alumnos" . $cursoActivar . "(N_Id_Escolar), " .
         "FOREIGN KEY (id_asignatura) REFERENCES asignaturas" . $cursoActivar . "(id_asignatura), " .
         "FOREIGN KEY (id_evaluacion) REFERENCES evaluaciones" . $cursoActivar . "(id_evaluacion), " . 
         "PRIMARY KEY (N_Id_Escolar, id_evaluacion, id_asignatura, Nota) ) ENGINE=InnoDB;";

  if (!mysqli_query($conn, $sql)) {
    $activar = 0;
    echo "Error al crear la tabla notas: " . mysqli_error($conn) . "<br>";
  }      

  // Insertar datos sin sanear en la tabla de cabeceras
  $sql = "SELECT COUNT(*) FROM cabecera" . $cursoActivar . ";";
  $result = mysqli_query($conn, $sql);
  $fila = mysqli_fetch_array($result, MYSQLI_NUM);
  
  if (!$fila[0] === 1) { //Comprobar que no haya ya datos
    $sql = "INSERT INTO cabecera" . $cursoActivar . " VALUES(";

    for ($x = 0; $x < $arrlength; $x++) {
      $var = $datos[$x];
      $sql .= "'$var'" . ", ";
    }

     $sql = substr($sql, 0, -2); //quitar espacio y coma sobrantes y añadir ;
     $sql .= ";";

    if (!mysqli_query($conn, $sql)) {
      $activar = 0;
      echo "Error al insertar los datos de las cabeceras: " . $cursoActivar . mysqli_error($conn) . "<br>";
    } 
  
  } // ./ end INSERT INTO cabecera
  
  // Comprobar si la activación se ha realizado con éxito
  if ($activar === 0) {
    echo "El curso " . $nombreCursoActivar . " no se ha activado correctamente" . "<br>";
  } else {
    echo "Curso " . $nombreCursoActivar . " activado correctamente" . "<br>";
    
    if ($cursoActivar != 1) { //si $cursoActivar no es el de prueba
      //crear archivo dentro de conf con los datos del cursoActivo
      $conf = fopen('../config/cursoActivo.php', 'w+');
      fwrite($conf, "<?php\n");
      fwrite($conf, "$" . "cursoActivo" . " = " . $cursoActivar . ";");
      fwrite($conf, "\n" . "$" . "nombreCursoActivo" . " = " . "'$nombreCursoActivar'" . ";");
      fclose($conf);
    } else {
      //si se está activando el curso de prueba, crear archivo cursoPrueba.php
      $conf = fopen('../config/cursoPrueba.php', 'w+');
      fwrite($conf, "<?php\n");
      fwrite($conf, "$" . "cursoPrueba" . " = " . $cursoActivar . ";");
      fwrite($conf, "\n" . "$" . "nombreCursoPrueba" . " = " . "'$nombreCursoActivar'" . ";"); 
      fclose($conf);
    }
    
  } // ./ end comprobación de la activación
  
  
} // ./ end activar_curso($file)


/* Funcion check_curso($display)
 * Verificar si la hay algún curso activado
 * args: $display, opcional, si es true, mostrará como resultado el curso
 * return: si no recibe display o es ! de true, booleano verdad si hay 
 *  algún curso activo, falso si no.
 * si display es true, devuelve el nombre del curso activo, si hay alguno, o 
 * el de prueba si no si está activo */
function check_curso($display) {
  global $cursoActivo, $nombreCursoActivo, $cursoPrueba, $nombreCursoPrueba;
  
  if ($cursoActivo && $nombreCursoActivo) {
    if ($display) {
      $result = $nombreCursoActivo;
    } else {
      $result = true;
    }
  } elseif ($cursoPrueba && $nombreCursoPrueba) {
    if ($display) {
      $result = $nombreCursoPrueba;
    } else {
      $result = true;
    }
  } else {
    if ($display) {
      $result = "Sin curso activado";
    } else {
      $result = false;
    }
  }
  
  return $result;
} // ./ end check_curso($display)


/* Funcion check_install()
 * Verificar si la aplicación está instalada o no
 * args: lee $instalar de config/config.php
 * return: true si está instalada, false si no */
function check_install() {
  global $instalar;
  $result = true;
  if (!$instalar) {
    $result = false;   
  }
  return $result;
}


/*Funcion check_sesion()
 * Comprobar si está iniciada o no la sesión,
 * para cambiar el menú que se muestra en la barra de navegación,
 * a iniciar sesión o a cerrar sesión con jQuery */
function check_sesion() {
  
  session_start();

  if (isset($_SESSION["validar"])) {
    // Si está autenticado, muestra Cerrar Sesión y oculta Iniciar Sesión
    echo "
          <script>
            $(document).ready(function() {
              $('#iniciarSesion').hide();
              $('#cerrarSesion').show();
            }); 
          </script>
         ";
  } else {
    // Si no ha iniciado sesión, muestra Iniciar Sesión y oculta Cerrar Sesión
    echo "
          <script>
            $(document).ready(function() {
              $('#iniciarSesion').show();
              $('#cerrarSesion').hide();
            });
          </script>
         ";
  }
  
} // ./ end check_sesion()


/* Funcion error_inicio_sesion()
 * Comprueba errores en el formulario de inicio de sesión
 * args: recibe por GET en el index valores que devuelve el autenticador.php
 * return: regresa un div con un mensaje de error, distinto según sea el error */
function error_inicio_sesion() {
  
  if ($_GET[error] === "si") {
    if ($_GET[formularioerror] === "si") { //formulario no relleno
      echo "<div class='alert alert-danger'>" .
           "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" .
           "Rellena el formulario.</div>";
    } else {
      echo "<div class='alert alert-danger'>" .
           "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" .
           "Verifica tus datos.</div>";
    }
  }
  if ($_GET[usererror] === "si") { //error de permisos del usuario
    echo "<div class='alert alert-danger'>" .
         "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" .
         "No tienes permiso suficiente para acceder a esa página.</div>";
  }
  
} // ./ end error_inicio_sesion()


/* Funcion leer_alumnos($file)
 * A partir de un fichero csv lee datos de uno o varios alumnos
 * args: $file, un archivo, $curso al que pertenecen los alumnos
 * return: inserta los datos en la base de datos */
function leer_alumno($file, $curso) {
  
  global $servername, $username, $password, $dbname;    
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  $sql = "INSERT IGNORE INTO alumnos" . $curso . " VALUES ";
  $cabecerasEncontradas = FALSE; //fijamos el control de encontrar la cabecera

  while ($datos_alumno = fgetcsv($file, 0, ',', '"')) {  //leer csv

    if (!$cabecerasEncontradas) {
      
      if ($datos_alumno[0] == "Alumno/a") { // Cabecera encontrada
        $cabecerasEncontradas = TRUE;            
      }

    } elseif ($datos_alumno[0] != "") {
      //Cuando el alumno no esté vacío ni sea el registro de cabecera 
      $arrlength = count($datos_alumno);
      $sql .= "(";
      
      for ($x = 0; $x < $arrlength; $x++) {
        $var = $datos_alumno[$x];
        $sql .= "'$var'" . ", ";
      }
      
      $sql = substr($sql, 0, -2); //quitamos la coma y el espacio sobrantes
      $sql .= "),"; // empezamos otro registro  
    }
  } // ./ end bucle while de lectura del csv
  
  $sql = substr($sql, 0, -1);
  $sql .= ";";
  
  if (!mysqli_query($conn, $sql)) {
    echo "Error al insertar el alumno " . mysqli_error($conn) . "<br>";
  }
      
} // ./ end leer_alumno($file)


/* Funcion listar_asignaturas($curso, $multiple)
 * Busca las asignaturas en la BD y las devuelve en un select
 * args: $curso deseado, $multiple, booleano sobre si el select sea o no multiple
 * return: select html con los datos de las asignaturas,
 *  nombre completo si lo tienen, si no el id sólo */
function listar_asignaturas($curso, $multiple) {
  
  global $servername, $username, $password, $dbname;
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  
  //coge el id cuando no tenga nombre completo asignado, y si lo tiene el nombre
  $sql = "SELECT id_asignatura FROM asignaturas" . $curso .
         " WHERE nombre_completo = '' OR nombre_completo IS NULL" .
         " UNION SELECT nombre_completo FROM asignaturas" . $curso .
         " WHERE nombre_completo IS NOT NULL AND nombre_completo <> ''";
        
  $asignaturas = array();
  $result = mysqli_query($conn, $sql);
        
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $asignaturas[] = $row;
  }
         
  if ($multiple) {
    $html = "<select name='asignatura[]' multiple>";
  } else {
    $html = "<select name='asignatura'>";
  }
  
  foreach ($asignaturas as $value) {
    $asignatura = $value['id_asignatura'];
    $html .= "<option value='$asignatura'>" . $value . "</option>";
  }
  
  $html .= "</select>";
  echo $html;        
}


/* Funcion listar_cursos()
 * Busca los cursos que existen en la BD y los devuelve en un select
 * return: select html con los cursos, el nombre descriptivo si lo tienen */
function listar_cursos() {
  
  global $servername, $username, $password, $dbname;
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  
  $sql = "SELECT * FROM cursos";
  $cursos = array();
  $result = mysqli_query($conn, $sql);
  
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $cursos[] = $row;
  }
  
  $html = "<select name='curso' onchange='fetch_select(this.value);'>";
 
  foreach ($cursos as $value) {
    $curso = $value['id_curso'];
    $nombreCurso = $value['nombre_curso'];
    $html .= "<option>" . $curso . "</option>";
    //$html .= "<option value='$curso'>" . $nombreCurso . "</option>";
  }
  
  $html .= "</select>";
  echo $html;
}


/* Funcion listar_graficos()
 * Crea un select con los tipos de gráficos */
function listar_graficos() {
  
  $graficos = array(
      "Spline Chart"=>"drawSplineChart", "Grafico de Barras"=>"drawBarChart",
      "Grafico de Área"=>"drawAreaChart", "Gráfico de líneas"=>"drawLineChart", 
      "Gráfico Spline relleno"=>"drawFilledSplineChart", "Gráfico de Puntos"=>"drawPlotChart",
      "Gráfido de Saltos relleno"=>"drawFilledStepChart", "Gráfido de barras Stacked"=>"drawStackedBarChart",
      "Gráfido de área stacked"=>"drawStackedAreaChart"
  );
  
  $html = "<select name='tipo'>";
  
  foreach ($graficos as $key => $value) {
    $html .= "<option value='$value'>" . $key . "</option>";
  }
  
  $html .= "</select>";
  echo $html;
}


/* Funcion listar_paletas()
 * Crea un select con los tipos de paletas */
function listar_paletas() {
  
  $paletas = array(
      "Defecto"=>"default", "Otoño"=>"autumn", "Ciega"=>"blind", 
      "Anochecer"=>"evening", "Cocina"=>"kitchen", "Armada"=>"navy", 
      "Sombras"=>"shade", "Primavera"=>"spring", "Verano"=>"summer", "Luminosa"=>"light"
  );
  
  $html = "<select name='paleta'>";
  
  foreach ($paletas as $key => $value) {
    $html .= "<option value='$value'>" . $key . "</option>";
  }
  
  $html .= "</select>";
  echo $html;
  
}


/* Funcion listar_alumnos($curso, $grupo, $multiple)
 * Busca los alumnos de un curso y un grupo en la BD y los devuelve en un select
 * args: $curso deseado, $grupo de los alumnos, 
 *  $multiple, booleano sobre si el select sea o no multiple
 * return: select html con los nombres de los alumnos */
function listar_alumnos($curso, $grupo, $multiple) {
  
  global $servername, $username, $password, $dbname;
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  
  $sql = "SELECT Alumnoa, N_Id_Escolar FROM alumnos" . $curso . " WHERE Curso = '" . $grupo . "';";
  $alumnos = array();
  $result = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $alumnos[] = $row;
  }
  
  if ($multiple) {
    $html = "<select name='alumno[]' multiple>";
  } else {
    $html = "<select name='alumno'>";
  } 

  foreach($alumnos as $value) {
    $html .= "<option value='" . $value['N_Id_Escolar'] . "'>" . $value['Alumnoa'] . "</option>";
  }
  
  $html .= "</select>";
  echo $html;
}


/* Funcion notas($file, $trimestre, $curso)
 * a partir de leer un archivo csv, sube notas a la base de datos
 * args: $file archivo, $trimestre de las notas, $curso del que son las notas
 * return: subir las notas a la base de datos, y además insertar en la tabla
 *  de asignaturas todas las que sean nuevas */
function notas($file, $trimestre, $curso) {
  
  global $servername, $username, $password, $dbname;
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  
  $sql_asignaturas = "INSERT IGNORE INTO asignaturas" . $curso . " (id_asignatura) VALUES ";
  $sql_notas = "INSERT IGNORE INTO notas" . $curso . " (N_Id_Escolar, Trimestre, id_asignatura, Nota) VALUES ";
  
  $asignatura = array();
  $cabecerasEncontradas = FALSE; //fijamos el control de encontrar la cabecera
  
  while ($datos = fgetcsv($file)) {  //lectura de lineas del csv
 
    if (!$cabecerasEncontradas) {

      if ($datos[0] === "Alumno/a") { // Cabecera encontrada
        
        $cabecerasEncontradas = TRUE;
        $arrlength = count($datos);

        for ($x = 1; $x < $arrlength; $x++) { // Coger los codigos de las asignaturas
          $var = $datos[$x];
          $asignatura[$x - 1] = $var;
          $sql_asignaturas .=  "('$var'),";
        }

        $sql_asignaturas = substr($sql_asignaturas, 0, -1); // quitamos la coma sobrante
        $sql_asignaturas .= ";";
        
        if (!mysqli_query($conn, $sql_asignaturas)) {
          echo "Error al insertar las asignaturas: " . mysqli_error($conn) . "<br>";
          $fallo = 1;
        }

      } 
    } else { //si cabecerasEncontradas = TRUE

      $arrlength = count($datos);

      for ($x = 0; $x < $arrlength; $x++) {

        $alumno = $datos[0];
        $sql_alumno = "SELECT N_Id_Escolar FROM alumnos" . $curso . 
                      " WHERE Alumnoa = " . "'$alumno'" . ";";

        $result = mysqli_query($conn, $sql_alumno);
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        $alumnoid = $row[0];  

        if (!$alumnoid) {
          echo "El alumno " . $alumno . " no está en la base de datos, " .
               "insertar sus datos primero.";
          $fallo = 1;
        }

        if ($x > 0) {
          $var = $datos[$x];
          $as = $asignatura[$x-1];
          if ($var) { //insertar sólo si hay notas
            $sql_notas .= "('$alumnoid', '$trimestre', '$as', '$var'),";
          }
        }

      } // ./ end bucle for de lectura
    } // ./ end else 
  } // ./ end bucle while
  
  $sql_notas = substr($sql_notas, 0, -1); // quitamos la coma sobrante
  $sql_notas .= ";";
  
  if (!$fallo) {
    if (mysqli_query($conn, $sql_notas)) {
      echo "Las notas se han insertado correctamente o ya existían<br>";
    } else {
      echo "Error al insertar las notas: " . mysqli_error($conn) . "<br>";
    }
  } else {
    echo "Cancelada la inserción de notas por fallo";
  }

} // ./ end notas($file)


/* Funcion protege($rol)
 * Crea la sesion o la propaga y verificar las variables de sesion
 * args: $rol que exista en la base de datos
 * return: lo deja pasar, y si no lo devuelve al inicio con mensaje de error */
function protege($rol) {
  
    global $urlbase;
    session_start();
    if($_SESSION['validar'] != "1" || $_SESSION['rol'] != $rol) {
        header("Location: " . $urlbase . "index.php?usererror=si");
    }
}


/* Funcion sanear_string($string), convierte un string cualquiera en texto "sano" 
* para entrar en la base de datos
* args: $string, return: $string */
function sanear_string($string) {
  
    $string = trim($string);
    
    //quitar todo tipo de acentos de las vocales
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
    // /. quitar acentos
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //reemplazar todos los caracteres "extraños" por espacios vacíos
    $string = str_replace(
        array('\\', "¨", "º", "-", "~", "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/", '(', ')', "?", "'", "¡",
             "¿", "[", "^", "<code>", "]", "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":", "."),
        '',
        $string
    );
    
    $string = str_replace(" ", "_", $string);
    return $string;
    
} // ./ end sanear_string($string)
?>