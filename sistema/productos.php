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
	$fetch = mysqli_query($conection,"SELECT * FROM producto where descripcion like '%" . mysqli_real_escape_string($conection,($_GET['term'])) . "%' LIMIT 0 ,20"); 
	
	/* Recuperar y almacenar en conjunto los resultados de la consulta.*/
	while ($row = mysqli_fetch_array($fetch)) {
		/*$nom_cliente=$row['nombre'];
		$precio=number_format($row['precio_venta'],2,".","");
		$row_array['value'] = $row['codigo_producto']." | ".$row['nombre_producto'];*/
		$codproducto=$row['codproducto'];
		$row_array['value'] = $row['descripcion'];
		$row_array['descripcion']=$row['descripcion'];
		$row_array['codproducto']=$row['codproducto'];
		$row_array['existencia']=$row['existencia'];
		$row_array['precio']=$row['precio'];
		/*$row_array['telefono']=$row['telefono'];
		$row_array['correo']=$row['correo'];*/
		/*$row_array['precio']=$precio;*/
		array_push($return_arr,$row_array);
    }
}

/* Cierra la conexión. */
mysqli_close($conection);

/* Codifica el resultado del array en JSON. */
echo json_encode($return_arr);

}
?>