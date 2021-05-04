<?php 
	
	session_start();
	if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
	{
		header("location: ./");
	}
	
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre_proveedor']) || empty($_POST['telefono']))
		{
			$alert='<p class="msg_error">Los campos nombre y telefono son obligatorios.</p>';
		}else{

			$idproveedor					= $_POST['id'];
			$nombre_proveedor 				= $_POST['nombre_proveedor'];
			$ruc 							= $_POST['ruc'];
			$telefono   					= $_POST['telefono'];
			$direccion  					= $_POST['direccion'];
			$email  						= $_POST['email'];
			$nombre_representante			= $_POST['nombre_representante'];
			$entidad_financiera 			= $_POST['entidad_financiera'];
			$tipo_cuenta 					= $_POST['tipo_cuenta'];
			$numero_cuenta 					= $_POST['numero_cuenta'];
			
			
			
				$sql_update = mysqli_query($conection,"UPDATE proveedor
															SET ruc = '$ruc',nombre_proveedor = '$nombre_proveedor',telefono='$telefono',direccion='$direccion', email='$email',nombre_representante='$nombre_representante', entidad_financiera='$entidad_financiera', tipo_cuenta='$tipo_cuenta', numero_cuenta='$numero_cuenta' WHERE idproveedor= $idproveedor ");
				

				if($sql_update){
					$alert='<p class="msg_save">Proveedor actualizado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al actualizar el proveedor.</p>';
				

			}


		}
		

	}

	//Mostrar Datos
	if(empty($_REQUEST['id']))
	{
		header('Location: lista_proveedor.php');
		mysqli_close($conection);
	}
	$idproveedor = $_REQUEST['id'];

	$sql= mysqli_query($conection,"SELECT *
									FROM proveedor
									WHERE idproveedor= $idproveedor AND status = 1");
	mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if($result_sql == 0){
		header('Location: lista_proveedor.php');
	}else{
		
		while ($data = mysqli_fetch_array($sql)) {
			
			$idproveedor  			= $data['idproveedor'];
			$ruc  					= $data['ruc'];
			$nombre_proveedor  		= $data['nombre_proveedor'];
			$telefono 				= $data['telefono'];
			$direccion  			= $data['direccion'];
			$email  				= $data['email'];
			$nombre_representante  	= $data['nombre_representante'];
			$entidad_financiera  	= $data['entidad_financiera'];
			$tipo_cuenta  			= $data['tipo_cuenta'];
			$numero_cuenta  		= $data['numero_cuenta'];
			
			}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<div class="form_encabezado">
			<label class="regtitulo"><i class="fas fa-edit"></i> Actualizar Proveedor</label>
			</div>
			
		<form action="" method="post">
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<input type="hidden" name="id" value="<?php echo $idproveedor;?>">
			<label for="nombre_proveedor">Nombre: </label>
			<input type="text" name="nombre_proveedor" placeholder="Nombre del Proveedor" value="<?php echo $nombre_proveedor; ?>" />
			<label for="ruc">Numero de Identificación/RUC: </label>
			<input type="text" name="ruc" placeholder="Numero de Identificación" value="<?php echo $ruc; ?>" />
			<label for="direccion">Dirección: </label>
			<input type="text" name="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>"/>
			<label for="telefono">Teléfono: </label>
			<input type="text" name="telefono" placeholder="Teléfono" value="<?php echo $telefono; ?>" />
			<label for="email">Correo Electronico: </label>
			<input type="email" name="email" placeholder="Correo Electronico" value="<?php echo $email; ?>"/>
			<label for="nombre_representante">Nombre del Representante: </label>
			<input type="text" name="nombre_representante" placeholder="Nombre del Representante" value="<?php echo $nombre_representante; ?>"/>	
			<label for="entidad_financiera">Entidad Financiera: </label>
			<select name="entidad_financiera" >
				<option value="<?php echo $entidad_financiera; ?>"></option>
				<option value="Banco del Austro">Banco del Austro</option>
				<option value="Banco Guayaquil">Banco Guayaquil</option>
				<option value="Banco del Pacifico">Banco del Pacifico</option>
				<option value="Banco Pichincha">Banco Pichincha</option>
				<option value="Banco Internacional">Banco Internacional</option>
				<option value="Coop Jep">Coop Jep</option>
				<option value="Coop Nueva Huancavilca">Coop Nueva Huancavilca</option>
			</select>
			<label for="tipo_cuenta">Tipo de Cuenta: </label>
			<select name="tipo_cuenta">
			<option value="0">--Tipo de Cuenta--</option>
			<option value="Ahorros">Ahorros</option>
			<option value="Corriente">Corriente</option>
			</select>
			<label for="numero_cuenta">Número de Cuenta: </label>
			<input type="text" name="numero_cuenta" placeholder="Número de Cuenta" value="<?php echo $numero_cuenta; ?>"/>
				
				<button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Proveedor</button>

			</form>

		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>