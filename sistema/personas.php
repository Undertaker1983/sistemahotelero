<?php
session_start();
include "../conexion.php";
if (isset($_GET['term'])){
/*if ($_GET['term']){*/	
	# conectare la base de datos
    /*$con=@mysqli_connect("localhost", "root", "", "test");*/

	
$return_arr = array();
/* Si la conexión a la base de datos , ejecuta instrucción SQL. */
if ($conection)
{
	$fetch = mysqli_query($conection,"SELECT * FROM personas where nombre like '%" . mysqli_real_escape_string($conection,($_GET['term'])) . "%' LIMIT 0 ,20"); 
	
	/* Recuperar y almacenar en conjunto los resultados de la consulta.*/
	while ($row = mysqli_fetch_array($fetch)) {
		/*$nom_cliente=$row['nombre'];
		$precio=number_format($row['precio_venta'],2,".","");
		$row_array['value'] = $row['codigo_producto']." | ".$row['nombre_producto'];*/
		$idpersona=$row['idpersona'];
		$row_array['value'] = $row['nombre'];
		$row_array['nombre']=$row['nombre'];
		$row_array['idpersona']=$row['idpersona'];
		$row_array['cedula']=$row['cedula'];
		$row_array['direccion']=$row['direccion'];
		$row_array['telefono']=$row['telefono'];
		$row_array['correo']=$row['correo'];
		/*$row_array['precio']=$precio;*/
		$row_array['razon_social']=$row['razon_social'];
		array_push($return_arr,$row_array);
    }
}

/* Cierra la conexión. */
mysqli_close($conection);

/* Codifica el resultado del array en JSON. */
echo json_encode($return_arr);

}
?>