<?php 
session_start();

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['nombre_habitacion']))
	{
		/*$notiError';
		$alert='swal("Atencion","Todos los campos son obligatorios","error")';
		echo 'Todos los campos son obligatorios';*/
		$alert='<p class="msg_error">El campo nombre es obligatorio.</p>';
	}else{

		$idpiso 			= $_POST['idpiso'];
		$idcategoria 		= $_POST['idcategoria'];
		$nombre_habitacion	= $_POST['nombre_habitacion'];
		$detalles  			= $_POST['detalles'];
		$precio   			= $_POST['precio'];

		$query_insert = mysqli_query($conection,"INSERT INTO habitaciones(idpiso,idcategoria,nombre_habitacion,detalles,precio) VALUES('$idpiso','$idcategoria','$nombre_habitacion','$detalles','$precio')");

		if($query_insert){ 
			header("location: lista_habitaciones.php");
			echo 'Habitación guardada correctamente';				
			//$alert='<p class="msg_save">Orden registrado correctamente.</p>';
		}else{
			/*$alert='<p class="msg_error">Error al registrar habitación.</p>';*/
			echo 'Error al registrar habitación.';
		}
	}
}			
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
					<!--<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>-->	
					<div class="form-row">
						<div class="form-group col-md-8">
							<label for="nombre" class="col-form-label">Nombre</label>
							<input type="text" class="form-control" name="nombre_habitacion" id="nombre_habitacion" placeholder="Nombre Habitacion" required>
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
							<input type="text" name="precio" id="precio" class="form-control" placeholder="Precio" required>
						</div>

						<div class="form-group col-md-12">
							<label for="detalles" class="col-form-label">Detalles</label>
							<textarea name="detalles" id="detalles" class="form-control" rows="2" placeholder="Detalles"></textarea>
						</div>
					</div>
					<div class="tile-footer">
						<center>
							<button class="btn btn-primary" type="submit" id="register"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
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
	<script type="text/javascript">
		$(function(){
			$('#register').click(function(e){
				var valid = this.form.checkValidity();

				if(valid){
					var nombre_habitacion 		= $('#nombre_habitacion').val();
					var idpiso					= $('#idpiso').val();
					var idcategoria 			= $('#idcategoria').val();
					var detalles 				= $('#detalles').val();
					var precio 					= $('#precio').val();
					

					e.preventDefault();	

					$.ajax({
						type: 'POST',
						url: 'registro_habitacion.php',
						data: {nombre_habitacion: nombre_habitacion,idpiso: idpiso,idcategoria: idcategoria,detalles: detalles,precio: precio},
						success: function(data){
							Swal.fire({
								icon: 'success',
								title: 'Guardando...',
								text: 'Datos guardados correctamente',
								showConfirmButton: true,
									/*'title': 'Successful',
									'text': data,
									'type': 'success'*/
								});
							
						},
						error: function(data){
							Swal.fire({
								title: 'Error',
								text: 'Error al guardar el registro.',
								type: 'error'
							});
						}
					});

					
				}else{
					
				}

				



			});		

			
		});	
	</script>
</body>
</html>