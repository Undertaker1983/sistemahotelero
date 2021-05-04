<?php
if (isset($_GET['term'])){

include("../../conexion.php");
$return_arr = array();
/* If connection to database, run sql statement. */
if ($conection)
{
	
	$fetch = mysqli_query($con,"SELECT * FROM personas where nombre like '%" . mysqli_real_escape_string($conection,($_GET['term'])) . "%' LIMIT 0 ,50"); 
	
	/* Retrieve and store in array the results of the query.*/
	while ($row = mysqli_fetch_array($fetch)) {
		$id_cliente=$row['idpersona'];
		$row_array['value'] = $row['nombre'];
		$row_array['idpersona']=$id_cliente;
		$row_array['nombre']=$row['nombre'];
		$row_array['telefono']=$row['telefono'];
		$row_array['correo']=$row['correo'];
		array_push($return_arr,$row_array);
    }
	
}
/* Free connection resources. */
mysqli_close($conection);
/* Toss back results as json encoded array. */
echo json_encode($return_arr);
}
?>