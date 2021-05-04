  <?php 
 session_start();

 include "../conexion.php";  
 if ($_POST['action'] == 'delHabitacion') 
 {  
  if (empty($_POST['habitacion_id']) || !is_numeric($_POST['habitacion_id'])){
    echo 'error'; 
  }else{

    $idhabitacion = $_POST['habitacion_id'];
    $query_delete = mysqli_query($conection,"UPDATE habitaciones SET estado = 0 WHERE idhabitacion = $idhabitacion ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  
?>
