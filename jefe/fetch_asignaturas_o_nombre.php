<?php
include_once '../config/config.php';
include_once '../funciones.php';
   
           
   if(isset($_POST['get_option']))
   {
           
//     $conn = mysqli_connect($servername, $username, $password, $dbname);

      

     $curso_id = $_POST['get_option'];
     
     listar_asignaturas($curso_id, true);
//     $sql = "SELECT id_asignatura FROM asignaturas" . $curso_id;
//     $result = mysqli_query($conn, $sql);
//
//     while($row=mysqli_fetch_array($result))
//     {
//       echo $row['id_asignatura'];
//       echo "<option>".$row['id_asignatura']."</option>";
//     }
   
     exit;
   }

?>