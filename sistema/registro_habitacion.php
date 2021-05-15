<?php 
session_start();

include "../conexion.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Registrar Habitaciones</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>

	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Registrar Habitación</h1>
		</div>
		<div class="row">
			
			<div class="tarjeta col-md-5">
				<form action="" method="post">
					
					<div class="form-row">
						<div class="form-group col-md-8">
							<label for="nombre" class="col-form-label">Nombre</label>
							<input type="text" class="form-control" name="nombre_habitacion" id="nombre_habitacion" placeholder="Nombre Habitacion">
						</div>
						<div class="form-group col-md-4">
							<label for="piso" class="col-form-label">Piso</label>
							<?php 
							$query_pisos = mysqli_query($conection, "SELECT * FROM pisos WHERE estado = 1 ORDER BY nombre_piso DESC");
							$result_pisos = mysqli_num_rows($query_pisos);
					 //mysqli_close($conection);	

							?>

							<select name="idpiso" id="idpiso" class="form-control">
								<?php 
								if ($result_pisos > 0) {
									while ($pisos = mysqli_fetch_array($query_pisos)) {
										?>
										<option value="<?php echo $pisos['idpiso']; ?>"><?php echo $pisos['nombre_piso']; ?></option>
										<?php 

									}
								}
								?>

							</select>
						</div>
						<div class="form-group col-md-7">	
							<label for="categoria" class="col-form-label">Categoria</label>

							<?php 
							$query_categorias = mysqli_query($conection, "SELECT * FROM categorias WHERE estado = 1 ORDER BY nombre_categoria DESC");
							$result_categorias = mysqli_num_rows($query_categorias);
					 //mysqli_close($conection);	

							?>

							<select name="idcategoria" id="idcategoria" class="form-control">
								<?php 
								if ($result_categorias > 0) {
									while ($categorias = mysqli_fetch_array($query_categorias)) {
										?>
										<option value="<?php echo $categorias['idcategoria']; ?>"><?php echo $categorias['nombre_categoria']; ?></option>
										<?php 

									}
								}
								?>

							</select>
						</div>
						
						<div class="form-group col-md-5">
							<label for="precio" class="col-form-label">Precio</label>
							<input type="text" name="precio" id="precio" class="form-control" placeholder="Precio" >
						</div>

						<div class="form-group col-md-12">
							<label for="detalles" class="col-form-label">Detalles</label>
							<textarea name="detalles" id="detalles" class="form-control" rows="2" placeholder="Detalles"></textarea>
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
	if(empty($_POST['nombre_habitacion']))
	{
		?>
		
		
		<script type="text/javascript">
			Swal.fire({
				icon : 'error',
				title: 'Error',
				text: 'Los campos nombre y precio son obligatorio',
				type: 'error',
			});
		</script>
		
		<?php 

	}else{

		$idpiso 			= $_POST['idpiso'];
		$idcategoria 		= $_POST['idcategoria'];
		$nombre_habitacion	= $_POST['nombre_habitacion'];
		$detalles  			= $_POST['detalles'];
		$precio   			= $_POST['precio'];

		$query_insert = mysqli_query($conection,"INSERT INTO habitaciones(idpiso,idcategoria,nombre_habitacion,detalles,precio) VALUES('$idpiso','$idcategoria','$nombre_habitacion','$detalles','$precio')");

		if($query_insert){ 
			//header("location: lista_habitaciones.php");
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
?>