<?php 
	session_start();
	if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) 
	{
		header("location: /");
	}

	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre_proveedor']) || empty($_POST['telefono']))
		{
			$alert='<p class="msg_error">Los campos Nombre y Telefono son obligatorios.</p>';
		}else{

			$nombre_proveedor 				= $_POST['nombre_proveedor'];
			$ruc 							= $_POST['ruc'];
			$telefono   					= $_POST['telefono'];
			$direccion  					= $_POST['direccion'];
			$email  						= $_POST['email'];
			$nombre_representante			= $_POST['nombre_representante'];
			$entidad_financiera 			= $_POST['entidad_financiera'];
			$tipo_cuenta 					= $_POST['tipo_cuenta'];
			$numero_cuenta 					= $_POST['numero_cuenta'];
			$usuario_id    					= $_SESSION['idUser'];
			
			$result = 0;

			if (is_numeric($ruc)) {
				$query = mysqli_query($conection,"SELECT * FROM proveedor WHERE ruc = '$ruc'");
				$result = mysqli_fetch_array($query);
			}

			if($result > 0){
				$alert='<p class="msg_error">Número de identificación ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conection,"INSERT INTO proveedor(nombre_proveedor,direccion,ruc,telefono,email,nombre_representante, entidad_financiera, tipo_cuenta, numero_cuenta, usuario_id) VALUES('$nombre_proveedor','$direccion','$ruc','$telefono','$email','$nombre_representante','$entidad_financiera','$tipo_cuenta','$numero_cuenta','$usuario_id')");
				if($query_insert){
					$alert='<p class="msg_save">Proveedor registrado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar el proveedor.</p>';
				}

			}	
						
			}
			mysqli_close($conection);

		}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Proveedores</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register_proveedor">
			<div class="form_encabezado">
			<label class="regtitulo"><i class="fas fa-truck"></i> Registrar Proveedor</label>
			<!--<hr>-->
			</div>

			<form action="" method="post">
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<label for="nombre_proveedor">Nombre: </label>
			<input type="text" name="nombre_proveedor" placeholder="Nombre del Proveedor" />
			<label for="ruc">Numero de Identificación/RUC: </label>
			<input type="text" name="ruc" placeholder="Numero de Identificación" />
			<label for="direccion">Dirección: </label>
			<input type="text" name="direccion" placeholder="Dirección" />
			<label for="telefono">Teléfono: </label>
			<input type="text" name="telefono" placeholder="Teléfono" />
			<label for="email">Correo Electronico: </label>
			<input type="email" name="email" placeholder="Correo Electronico" />
			<label for="nombre_representante">Nombre del Representante: </label>
			<input type="text" name="nombre_representante" placeholder="Nombre del Representante" />	
			<label for="entidad_financiera">Entidad Financiera: </label>
			<select name="entidad_financiera">
				<option value="0">--Entidad Financiera--</option>
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
			<input type="text" name="numero_cuenta" placeholder="Número de Cuenta" />


				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar Proveedor</button>

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>