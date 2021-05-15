<?php 
session_start();
if($_SESSION['rol'] != 1)
{
	header("location: ./");
}

include "../conexion.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Registro Usuario</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>
<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-users"></i> Registro Usuario</h1>
	</div>
	<div class="col-md-8">

		<div class="tarjeta col-md-6">	
			<form action="" method="post">
				<!--<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>-->	
				
				<div class="form-group row">
					<label class="control-label col-md-3" for="nombre">Nombre</label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre completo">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="correo">Correo electrónico</label>
					<div class="col-md-8">
						<input type="email" class="form-control" name="correo" id="correo" placeholder="Correo electrónico">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="usuario">Usuario</label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="clave">Clave</label>
					<div class="col-md-8">
						<input type="password" class="form-control" name="clave" id="clave" placeholder="Clave de acceso">
					</div>
				</div>

				<div class="form-group row">	
					<label class="control-label col-md-3" for="rol">Tipo Usuario</label>

					<?php 

					$query_rol = mysqli_query($conection,"SELECT * FROM rol");
					//mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

					?>
					<div class="col-md-8">
						<select name="rol" class="form-control" id="rol">
							<?php 
							if($result_rol > 0)
							{
								while ($rol = mysqli_fetch_array($query_rol)) {
									?>
									<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
									<?php 

								}

							}
							?>
						</select>
					</div>
					
				</div>
				<div class="tile-footer">
					<center>
						<button type="submit" class="btn btn-primary" id="register"><i class="fa fa-save"></i> Guardar Usuario</button>
					</center>
				</div>
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
	if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol']))
	{
		?>
		
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'Todos los campos son obligatorios',
									type: 'error',
								});
		</script>
		
		<?php 
		
	}else{

		$nombre = $_POST['nombre'];
		$email  = $_POST['correo'];
		$user   = $_POST['usuario'];
		$clave  = md5($_POST['clave']);
		$rol    = $_POST['rol'];


		$query = mysqli_query($conection,"SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ");
			//mysqli_close($conection);
		$result = mysqli_fetch_array($query);

		if($result > 0){
			?>
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'warning',
									title: 'Duplicado',
									text: 'El correo o el usuario ya existe.',
									
								});
		</script>
		
		<?php 
			
		}else{

			$query_insert = mysqli_query($conection,"INSERT INTO usuario(nombre,correo,usuario,clave,rol)
				VALUES('$nombre','$email','$user','$clave','$rol')");
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

}



?>