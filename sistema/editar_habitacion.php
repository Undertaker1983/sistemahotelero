<?php 
session_start();

include "../conexion.php";

//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: lista_habitaciones.php");
	mysqli_close($conection);
}else{
	$id_habitacion = $_REQUEST['id'];
	

	$query_habitacion = mysqli_query($conection,"SELECT h.idhabitacion,h.nombre_habitacion,h.idpiso,h.idcategoria,h.detalles,h.condicion,h.precio,p.idpiso,p.nombre_piso,c.idcategoria,c.nombre_categoria 
		FROM habitaciones h INNER JOIN
		pisos p ON h.idpiso = p.idpiso INNER JOIN
		categorias c ON h.idcategoria = c.idcategoria
		WHERE h.idhabitacion = $id_habitacion AND h.estado = 1");

	$result_habitacion = mysqli_num_rows($query_habitacion);
			//mysqli_close($conection);

	if ($result_habitacion > 0 ) {
		$data_habitacion = mysqli_fetch_array($query_habitacion);
				//print_r($data_orden);
	}
	else{
		header("location: lista_habitaciones.php");
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Habitación</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Actualizar Habitación</h1>
		</div>
		<div class="row">
			
				<div class="tarjeta col-lg-6">
					
						

						<form action="" method="post">
							<div class="form-row">
								<input type="hidden" id="id" name="id" value="<?php echo $data_habitacion['idhabitacion']; ?>"/>	
								<div class="form-group">
									<label for="nombre" class="col-form-label">Nombre</label>
									<input type="text" class="form-control" id="nombre_habitacion" name="nombre_habitacion" placeholder="Nombre Habitacion" value="<?php echo $data_habitacion['nombre_habitacion']; ?>">
								</div>

								<div class="form-group col-md-4">
									<label for="piso" class="col-form-label">Piso</label>

									<?php 
									$query_pisos = mysqli_query($conection, "SELECT * FROM pisos WHERE estado = 1 ORDER BY nombre_piso DESC");
									$result_pisos = mysqli_num_rows($query_pisos);
					 //mysqli_close($conection);	
									?>
									<select name="idpiso" id="idpiso" class="form-control notItemOne">
										<option value="<?php echo $data_habitacion['idpiso']; ?>" selected><?php echo $data_habitacion['nombre_piso']; ?></option>
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
								<div class="form-group col-md-4">
									<label for="estado" class="col-form-label">Estado</label>
									<select name="condicion" id="condicion" class="form-control notItemOne">
										<option value="<?php echo $data_habitacion['condicion']; ?>" selected><?php echo $data_habitacion['condicion']; ?></option>
										<option value="Disponible">Disponible</option>
										<option value="Ocupado">Ocupado</option>
										<option value="Limpieza">Limpieza</option>
										<option value="Mantenimiento">Mantenimiento</option>

									</select>
								</div>
								<div class="form-group col-md-5">
									<label for="categoria" class="col-form-label">Categoria</label>
									<?php 
									$query_categorias = mysqli_query($conection, "SELECT * FROM categorias WHERE estado = 1 ORDER BY nombre_categoria DESC");
									$result_categorias = mysqli_num_rows($query_categorias);
					 //mysqli_close($conection);	
									?>
									<select name="idcategoria" id="idcategoria" class="form-control notItemOne">
										<option value="<?php echo $data_habitacion['idcategoria']; ?>" selected><?php echo $data_habitacion['nombre_categoria']; ?></option>
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
								
								<div class="form-group col-md-7">
									<label for="precio" class="col-form-label">Precio</label>
									<input type="text" id="precio" name="precio" class="form-control" placeholder="Precio" value="<?php echo $data_habitacion['precio']; ?>">
								</div>

								<div class="form-group col-md-12">
									<label for="detalles" class="col-form-label">Detalles</label>
									<textarea name="detalles" id="detalles" class="form-control" rows="2"><?php echo $data_habitacion['detalles']; ?></textarea>
								</div>
								
							</div>
							<div class="tile-footer">
								<center>
								<button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i> Actualizar Habitación</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
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
session_start();

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['id']) || empty($_POST['nombre_habitacion']))
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
		$idhabitacion 		= $_POST['id'];
		$idpiso 			= $_POST['idpiso'];
		$idcategoria 		= $_POST['idcategoria'];
		$nombre_habitacion	= $_POST['nombre_habitacion'];
		$detalles  			= $_POST['detalles'];
		$precio   			= $_POST['precio'];
		$condicion			= $_POST['condicion'];

		$query_update = mysqli_query($conection,"UPDATE habitaciones SET idpiso = $idpiso, idcategoria = $idcategoria, nombre_habitacion = '$nombre_habitacion', detalles = '$detalles', precio = '$precio', condicion = '$condicion' WHERE idhabitacion = $idhabitacion");

		if($query_update){ 
			?>
			
			<script type="text/javascript">
				Swal.fire({
					icon: 'success',
					title: 'Guardando...',
					text: 'Datos actualizados correctamente',
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
					text: 'Error al actualizar registro',
					type: 'error',
				});
			</script>

			<?php 
		}
	}
}
?>