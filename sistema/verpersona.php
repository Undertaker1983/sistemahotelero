<?php 
session_start();
include "../conexion.php";

if(empty($_REQUEST['id']))
{
	header('Location: lista_clientes.php');
	mysqli_close($conection);
}
$idcliente = $_REQUEST['id'];

$sql= mysqli_query($conection,"SELECT *
	FROM personas
	WHERE idpersona= $idcliente AND status = 1");
mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if($result_sql == 0){
	header('Location: lista_personas.php');
}else{
	$option = '';
	while ($data = mysqli_fetch_array($sql)) {

		$idcliente  = $data['idpersona'];
		$cedula  	= $data['cedula'];
		$nombre  	= $data['nombre'];
		$telefono 	= $data['telefono'];
		$direccion  = $data['direccion'];
		$correo  	= $data['correo'];

	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Ver Cliente</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php";
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-address-card"></i>  Datos Generales</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="tile">
					<div>	
						<h1><?php echo strtoupper($nombre); ?></h1>
					</div>
					<div>
						<div>
							<label>Nombre: </label><h4><?php echo strtoupper($nombre); ?></h4>
						</div>
						<div>
							<label>Nº de Identificación: </label><h4><?php echo strtoupper($cedula); ?></h4>	
						</div>
						<div>
							<label>Teléfono: </label><h4><?php echo strtoupper($telefono); ?></h4>	
						</div>
						<div>
							<label>Dirección: </label><h4><?php echo strtoupper($direccion); ?></h4>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include "includes/footer_admin.php"; ?>
</body>
</html>