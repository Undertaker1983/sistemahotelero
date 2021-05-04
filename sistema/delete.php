<?php 
session_start();

include "../conexion.php";

if(!empty($_POST))
{	
	if(empty($_POST['idpiso']))
	{
		header("location: lista_pisos.php");
		mysqli_close($conection);
	}	
	$idpiso = $_POST['idpiso'];
	$query_delete = mysqli_query($conection,"UPDATE pisos SET estado = 0 WHERE idpiso = $idpiso");

	mysqli_close($conection);
	if($query_delete){
		header("location: lista_pisos.php");
	}else{
		echo "Error al eliminar";
	}

}
?>