  <?php 
  session_start();

  include "../conexion.php";  
  if(!empty($_POST))
  { 
    if (empty($_POST['id']))
    {
      header("location: lista_habitaciones.php");
      mysqli_close($conection);
    }else{

      $idhabitacion = $_POST['id'];
      $query_delete = mysqli_query($conection,"UPDATE habitaciones SET estado = 0 WHERE idhabitacion = $idhabitacion");
      mysqli_close($conection);
      if($query_delete){
       header("location: lista_habitaciones.php");
     }else{
       echo 'Error al eliminar';
     }
   }  
   }
   ?>
