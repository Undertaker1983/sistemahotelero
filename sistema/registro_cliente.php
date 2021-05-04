<?php 
	session_start();
	
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['telefono']))
		{
			$alert='<p class="msg_error">Los campos Nombre y Telefono son obligatorios.</p>';
		}else{

			$cedula 	= $_POST['cedula'];
			$nombre 	= $_POST['nombre'];
			$idtipo	    = $_POST['idtipo'];
			$telefono   = $_POST['telefono'];
			$direccion  = $_POST['direccion'];
			$correo  	= $_POST['correo'];
			$usuario_id    = $_SESSION['idUser'];
			
			$result = 0;

			if (is_numeric($cedula)) {
				$query = mysqli_query($conection,"SELECT * FROM personas WHERE cedula = '$cedula'");
				$result = mysqli_fetch_array($query);
			}

			if($result > 0){
				$alert='<p class="msg_error">Número de identificación ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conection,"INSERT INTO personas(cedula,idtipo,nombre,telefono,direccion,correo,usuario_id) VALUES('$cedula','$idtipo','$nombre','$telefono','$direccion','$correo','$usuario_id')");
				if($query_insert){
					$alert='<p class="msg_save">Registrado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al registrar.</p>';
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
	<title>Registro Clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<div class="form_encabezado">
			<label class="regtitulo"><i class="fas fa-user-plus"></i> Registrar Personas</label>
			</div>
			<!--<hr>-->
			

			<form  action="" method="post">
				<!--<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
				<input class="formulario__input" type="text" name="cedula" id="cedula">
				<label class="formulario__label" for="cedula">Número de Identificación</label>
				<input class="formulario__input" type="text" name="nombre" id="nombre">
				<label class="formulario__label" for="nombre">Nombre</label>
				<input class="formulario__input" type="text" name="telefono" id="telefono">
				<label class="formulario__label" for="telefono">Teléfono</label>
				<input class="formulario__input" type="text" name="direccion" id="direccion">
				<label class="formulario__label" for="direccion">Dirección</label>
				<input class="formulario__input" type="email" name="correo" id="correo">
				<label class="formulario__label" for="correo">Correo electrónico</label>-->
				<label for="cedula">Número de Identificación</label>
				<input type="text" name="cedula" id="cedula" placeholder="Numero de Identificación">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
				<label for="telefono">Teléfono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Teléfono">
				<label for="direccion">Dirección</label>
				<input type="text" name="direccion" id="direccion" placeholder="Dirección">
				<label for="correo">Correo electrónico</label>
				<input type="email" name="correo" id="correo" placeholder="Correo electrónico">
				
				

				
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar</button>

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>