<?php 
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Nuevo Contacto</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>

<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-users"></i> Nuevo Contacto</h1>
		
	</div>
	<div class="form-row">
		
		<div class="tarjeta">
			
			<form action="" method="post">
				
				<div class="form-row">
					<div class="form-group col-md-4">	
						<label class="col-form-label">Tipo de Persona</label>
						<?php 
						$query_tipo = mysqli_query($conection, "SELECT idtipo,tipo FROM tipo_persona WHERE status = 1 ORDER BY tipo ASC");
						$result_tipo = mysqli_num_rows($query_tipo);

						?>
						
						<select name="idtipo" id="idtipo" class="form-control">	
							<?php
							if ($result_tipo > 0) {
								while ($tipo = mysqli_fetch_array($query_tipo)) {
									?>
									<option value="<?php echo $tipo['idtipo']; ?>"><?php echo $tipo['tipo']; ?></option>
									<?php 
								}
							}
							?>
						</select>
					</div>

					<div class="form-group col-md-8">     
						<label for="cedula" class="col-form-label">Nº Identificación (Cédula/Ruc/Pasaporte)</label>
						<input type="text" name="cedula" id="cedula" class="form-control" placeholder="Número de Identificación">
					</div>

					<div class="form-group col-md-8">
						<label for="nombre" class="col-form-label">Nombres y Apellidos</label>
						<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre completo">
					</div>
					
					<div class="form-group col-md-4">
						<label for="telefono" class="col-form-label">Teléfono</label>
						<input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono">
					</div>
					
					<div class="form-group col-md-8">
						<label for="direccion" class="col-form-label">Dirección</label>
						<textarea name="direccion" id="direccion" class="form-control" rows="2"></textarea>
						<!--<input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección">-->
					</div>

					
					<div class="form-group col-md-4">
						<label for="correo" class="col-form-label">Correo electrónico</label>
						<input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico">
					</div>


					<div class="form-group col-md-7">
						<label for="razon" class="col-form-label">Razón Social</label>
						<input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón Social">
					</div>

					<div class="form-group col-md-5">
						<label for="entidad" class="col-form-label">Entidad Financiera</label>
						<input type="text" name="entidad_financiera" id="entidad_financiera" class="form-control" placeholder="Entidad Financiera" >
					</div>


					<div class="form-group col-md-5">
						<label for="tipo_cuenta" class="col-form-label">Tipo de Cuenta</label>
						<select class="form-control" name="tipo_cuenta" id="tipo_cuenta">
							<option value="">--Seleccione un tipo de Cuenta--</option>
							<option value="CORRIENTE">Cuenta Corriente</option>
							<option value="AHORROS">Cuenta de Ahorros</option>
						</select>
					</div>

					<div class="form-group col-md-7">
						<label for="numero_cuenta" class="col-form-label">Número de Cuenta</label>
						<input type="text" name="numero_cuenta" id="numero_cuenta" class="form-control" placeholder="Número de Cuenta" >
					</div>

				</div>	
				<div class="tile-footer">
					<center>
						<button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
					</center>
				</div>
			</form>

		</div>
		
	</div>


</main>
<!--Essential javascripts for application to work-->
<script src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="js/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>	


</body>
</html>

<?php 

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['nombre']))
	{
		?>
		
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'El campo nombre es obligatorio',
									type: 'error',
								});
		</script>
		
		<?php 
	}else{

		$cedula 				= $_POST['cedula'];
		$nombre 				= $_POST['nombre'];
		$idtipo	    			= $_POST['idtipo'];
		$telefono   			= $_POST['telefono'];
		$direccion  			= $_POST['direccion'];
		$correo  				= $_POST['correo'];
		$razon_social 	 	    = $_POST['razon_social'];
		$entidad_financiera	    = $_POST['entidad_financiera'];
		$tipo_cuenta   			= $_POST['tipo_cuenta'];
		$numero_cuenta  		= $_POST['numero_cuenta'];
		$usuario_id    			= $_SESSION['idUser'];

		$result = 0;

		if (is_numeric($cedula)) {
			$query = mysqli_query($conection,"SELECT * FROM personas WHERE cedula = '$cedula'");
			$result = mysqli_fetch_array($query);
		}

		if($result > 0){
			?>
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'warning',
									title: 'Duplicado',
									text: 'Número de identificación ya existe',
									
								});
		</script>
		
		<?php 
		}else{

			$query_insert = mysqli_query($conection,"INSERT INTO personas(cedula,nombre,telefono,direccion,correo,razon_social,entidad_financiera,tipo_cuenta,numero_cuenta,idtipo,usuario_id) VALUES('$cedula','$nombre','$telefono','$direccion','$correo','$razon_social','$entidad_financiera','$tipo_cuenta','$numero_cuenta','$idtipo','$usuario_id')");
			if($query_insert){
				
				?>
			
			<script type="text/javascript">
				Swal.fire({
									icon: 'success',
									title: 'Guardando...',
									text: 'Datos registrados correctamente',
									showConfirmButton: true,
									
								});
			</script>
			
			<?php 
			}else{
				?>
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'Error al registrar producto',
									type: 'error',
								});
		</script>
		
		<?php 
			}

		}	

	}
			//mysqli_close($conection);

}

?>