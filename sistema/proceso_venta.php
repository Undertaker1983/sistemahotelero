<?php 
session_start();
date_default_timezone_set("America/Bogota");
include "../conexion.php";
/*
if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['idalojamiento']))
	{
		$alert='<p class="msg_error">El campo es obligatorio.</p>';
	}else{
		
		$ialojamiento 			= $_POST['idalojamiento'];
		//$idhabitacion 			= $_POST['idhabitacion'];
		//$usuario_id    			= $_SESSION['idUser'];
		$cantidad				= $_POST['cantidad'];
		$precio   				= $_POST['precio'];
		
		$estado_habitacion		= "Ocupado";
		//echo "Total =".$ptotal.;
		$query_insert = mysqli_query($conection,"INSERT INTO consumo(idalojamiento,idhabitacion,idusuario,cantidad,precio) VALUES('$idalojamiento','$idhabitacion','$usuario_id','$cantidad','$precio')");

		$query_update = mysqli_query($conection,"UPDATE habitaciones SET condicion = '$estado_habitacion' WHERE idhabitacion = $idhabitacion");

		if($query_insert){ 
			header("location: pre_venta.php");
			echo 'Guardado correctamente';				
			//$alert='<p class="msg_save">Orden registrado correctamente.</p>';
		}else{
			/*$alert='<p class="msg_error">Error al registrar habitación.</p>';*/
			/*echo 'Error al registrar.';
		}
	}
}*/			


//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: pre_venta.php");
	mysqli_close($conection);
}else{
	$id_alojamiento = $_REQUEST['id'];
	

	$query_alojamiento = mysqli_query($conection,"SELECT a.idalojamiento,a.idhabitacion,a.idpersona,a.fecha_ingreso,a.hora_ingreso,a.fecha_salida,a.hora_salida,a.precio,a.anticipo,a.cant_noches,a.cant_personas,a.estado_pago,a.medio_pago,a.estado,h.nombre_habitacion,h.condicion,p.nombre,p.cedula,p.telefono		FROM  alojamiento a INNER JOIN
		habitaciones h ON a.idhabitacion = h.idhabitacion INNER JOIN
		personas p ON a.idpersona = p.idpersona
		WHERE a.idalojamiento = $id_alojamiento AND a.estado = 1");
	$result_alojamiento = mysqli_num_rows($query_alojamiento);
			//mysqli_close($conection);

	if ($result_alojamiento > 0 ) {
		$data_alojamiento = mysqli_fetch_array($query_alojamiento);
				//print_r($data_orden);
	}
	else{
		header("location: pre_venta.php");
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Consumo Habitación</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Venta Producto Habitación</h1>
		</div>
		<div class="row">
			<div class="col-md-4">
				
				<div class="tile">
					<form action="" method="post">
						<input type="hidden" id="idalojamiento" name="idalojamiento" value="<?php echo $data_alojamiento['idalojamiento'] ?>" required>
						<table class="table">
							<thead>
								<tr>
									<h4>Datos de la Habitación</h4>
								</tr>	
							</thead>			
							<tr>
								<td>
									<label for="nombre"><font color="#0982C3"><b>Habitación</b></font></label>
								</td>
								<td>
									<?php echo $data_alojamiento['nombre_habitacion']; ?>

								</td>

							</tr>
							<tr>	


								<td>
									<label for="detalles"><font color="#0982C3"><b>Costo del Alojamiento</b></font></label>
								</td>

								<td>
									<?php echo $data_alojamiento['precio']; ?>

								</td>

							</tr>
						</table>	
					</div>

				</div>
				<div class="col-md-4">

					<div class="tile">
						<table class="table">
							<thead>
								<tr>
									<h4>Datos del Cliente</h4>
								</tr>	
							</thead>			
							<tr>
								<td>
									<label for="nombre"><font color="#0982C3"><b>Nombre</b></font></label>
								</td>
								<td>
									<?php echo $data_alojamiento['nombre']; ?>

								</td>

							</tr>
							<tr>	


								<td>
									<label for="detalles"><font color="#0982C3"><b>Identificación</b></font></label>
								</td>

								<td>
									<?php echo $data_alojamiento['cedula']; ?>

								</td>

							</tr>
						</table>	
					</div>

				</div>
				<div class="col-md-4">

					<div class="tile">
						<table class="table">
							<thead>
								<tr>
									<h4>Detalles de salida</h4>
								</tr>	
							</thead>			
							<tr>
								<td>
									<label for="nombre"><font color="#0982C3"><b>Fecha y Hora de entrada</b></font></label>
								</td>
								<td>
									<?php echo $data_alojamiento['fecha_ingreso']; ?> <?php echo $data_alojamiento['hora_ingreso']; ?>

								</td>

							</tr>
							<tr>	


								<td>
									<label for="detalles"><font color="#0982C3"><b>Fecha y Hora de Salida</b></font></label>
								</td>

								<td>
									<?php echo $data_alojamiento['fecha_salida']; ?> <?php echo $data_alojamiento['hora_salida']; ?>
								</td>

							</tr>
						</table>	
					</div>

				</div>

			</div>



			<div class="row">
				<div class="col-md-12">
					<div class="tile">
						<div class="tile-body">
							<div class="table-responsive">	

								<div class="containerTable">
									<table class="table table-hover tbl_venta">
										<thead>
											<tr style="background-color: #EFEEEC">
												<th width="100px"> Código</th>
												<th> Descripción</th>
												<th> Existencia</th>
												<th width="100px"> Cantidad</th>
												<th class="textright"> Precio Unitario</th>
												<th class="textright"> Precio Total</th>
												<th> Acción</th>
											</tr>
											<tr>
												<td><input type="text" class="form-control" name="txt_cod_producto" id="txt_cod_producto" readonly></td>
												<!--<td id="txt_descripcion"></td>-->
												<td><input type="text" class="form-control" name="txt_descripcion" id="txt_descripcion"></td>
												<td id="txt_existencia">-</td>
												<td><input type="text" class="form-control" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
												<td id="txt_precio" class="textright">0.00</td>
												<td id="txt_precio_total" class="textright">0.00</td>
												<td> <a href="#" id="add_product_venta" class="link_add"><i class="fa fa-plus"></i> Agregar</a></td>
											</tr>
											<tr style="background-color: #EFEEEC">
												<th>Código</th>
												<th colspan="2">Descripción</th>
												<th>Cantidad</th>
												<th class="textright">Precio Unitario</th>
												<th class="textright">Precio Total</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody id="detalle_venta">
											<!--contenido ajax-->
										</tbody>
										<tfoot id="detalle_totales">
											<!--contenido ajax-->
										</tfoot>
									</table>
								</div>
								<br>
								
								<div class="">
								<select name="estado_pago" id="estado_pago" class="form-control col-md-2">
												<option value="Pagado">Pagado</option>
												<option value="Falta Cancelar">Falta Cancelar</option>
											</select>
								</div>
								<center>
									
									<div class="tile-footer">
										
										<div id="acciones_venta">
											<a href="#" class="btn btn-secondary" id=""><i class="fa fa-ban"></i> Cancelar</a>&nbsp;&nbsp;&nbsp;
											
											<a href="#" class="btn btn-primary" id="btn_consumo_hab" i class="fa fa-arrow-circle-down"></i> Terminar venta</a>
										</div>
									</div>
								</center>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>			

</main>
<!--Essential javascripts for application to work-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="js/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>	
<div class="modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<p>Modal body text goes here.</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" type="button">Save changes</button>
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			$('#register').click(function(e){
				var valid = this.form.checkValidity();

				if(valid){
					//var idhabitacion            = $('#idhabitacion').val();
					var idalojamiento			= $('#idalojamiento').val();
					var cantidad 				= $('#cantidad').val();
					var precio 					= $('#precio').val();
					var estado_pago 			= $('#estado_pago').val();


					e.preventDefault();	

					$.ajax({
						type: 'POST',
						url: 'proceso_venta.php',
						data: {idalojamiento: idalojamiento,cantidad: cantidad,precio: precio,estado_pago:estado_pago},
						success: function(data){
							Swal.fire({
								icon: 'success',
								title: 'Guardando...',
								text: 'Venta registrada correctamente',
								showConfirmButton: true,
									/*'title': 'Successful',
									'text': data,
									'type': 'success'*/
								});

						},
						error: function(data){
							Swal.fire({
								title: 'Error',
								text: 'Error al guardar venta.',
								type: 'error'
							});
						}
					});


				}else{

				}





			});		


		});	
	</script>
	<script type="text/javascript">
		$(function() {
			$("#txt_descripcion").autocomplete({
				source: "productos.php",
				minLength: 2,
				select: function(event, ui) {
					event.preventDefault();
					$('#txt_descripcion').val(ui.item.descripcion);
					$('#txt_existencia').html(ui.item.existencia);
					$('#txt_precio').html(ui.item.precio);
					$('#txt_cod_producto').val(ui.item.codproducto);
					
				/*$('#cor_cliente').val(ui.item.correo);
				$('#idpersona').val(ui.item.idpersona);*/
				$('#txt_cant_producto').removeAttr('disabled');
			}
		});
		});
	</script>

</body>
</html>