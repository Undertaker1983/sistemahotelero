<?php 
session_start();
date_default_timezone_set("America/Bogota");
include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['total_pago']))
	{
		$alert='<p class="msg_error">El campo fecha es obligatorio.</p>';
	}else{
		
		$idalojamiento 			= $_POST['idalojamiento'];
		$usuario_id    			= $_SESSION['idUser'];
		$fecha_emision			= $_POST['fecha_emision'];
		
		//$numero_comprobante   	= $_POST['numero_comprobante'];
		$total_pago   			= $_POST['total_pago'];
				
		//echo "Total =".$ptotal.;
		$query_insert = mysqli_query($conection,"INSERT INTO pago(idalojamiento,idusuario,fecha_emision,total_pago) VALUES('$idalojamiento','$usuario_id','$fecha_emision','$total_pago')");

		$query_updalojamiento = mysqli_query($conection,"UPDATE alojamiento SET estado_pago = 'Cancelado' WHERE idalojamiento = $idalojamiento");
		$query_update = mysqli_query($conection,"UPDATE habitaciones SET condicion = 'Limpieza' WHERE idhabitacion = $idhabitacion");

		if($query_insert){ 
			header("location: proceso_checkout.php");
			echo 'Guardado correctamente';				
			//$alert='<p class="msg_save">Orden registrado correctamente.</p>';
		}else{
			/*$alert='<p class="msg_error">Error al registrar habitación.</p>';*/
			echo 'Error al registrar.';
		}
	}
}		


//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: lista_checkout.php");
	mysqli_close($conection);
}else{
	$id_alojamiento = $_REQUEST['id'];
	

	$query_alojamiento = mysqli_query($conection,"SELECT a.idalojamiento,a.idhabitacion,a.idpersona,a.fecha_ingreso,a.hora_ingreso,a.fecha_salida,a.hora_salida,a.precio,a.anticipo,a.cant_noches,a.cant_personas,a.estado_pago,a.medio_pago,a.estado,h.idhabitacion,h.nombre_habitacion,h.condicion,p.nombre,p.cedula,p.telefono,c.precio_consumo,c.cantidad,pr.descripcion,pr.precio as punitario		FROM  alojamiento a INNER JOIN
		habitaciones h ON a.idhabitacion = h.idhabitacion INNER JOIN
		personas p ON a.idpersona = p.idpersona INNER JOIN
		consumo c ON c.idalojamiento = a.idalojamiento  INNER JOIN
		producto pr ON c.idproducto = pr.codproducto
		WHERE a.idalojamiento = $id_alojamiento AND a.estado = 1");

	
	$result_alojamiento = mysqli_num_rows($query_alojamiento);
	
			//mysqli_close($conection);

	if ($result_alojamiento > 0 ) {
		$data_alojamiento = mysqli_fetch_array($query_alojamiento);
		
				//print_r($data_orden);
	}
	else{
		header("location: lista_checkout.php");
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Proceso Check Out</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Proceso Check Out</h1>
		</div>
		<div class="row">
			<div class="col-md-4">
				
				<div class="tile">
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
							<form action="" method="post">
									<input type="hidden" value="<?php echo date("Y/m/d");?>" name="fecha_emision" readonly>
									<input type="hidden" id="idalojamiento" name="idalojamiento" value="<?php echo $data_alojamiento['idalojamiento']; ?>">
							<table class="table table-hover" id="tablacheck">
								<thead class="thead-light">
								<tr>
									<th colspan="7" style="border-right-width:3px;border-right-style:solid";>Costo del Alojamiento</th>
									
								</tr>
								</thead>	
								<tr>
									<td>
										Costo calculado
									</td>
									<td>
										Dinero anticipado
									</td>
									<td></td>
									<td></td>
									
									<td style="border-right-width:3px;border-right-style:solid";>
										Total
									</td>							
								</tr>
								
								<?php
									$precio 			= round($data_alojamiento['precio'],2);
									$anticipo   		= round($data_alojamiento['anticipo'],2);
									$total_hospedaje	= number_format($precio - $anticipo, 2);
									$consumo    		= round($data_alojamiento['precio_consumo'],2);
									$total      		= number_format($total_hospedaje + $consumo, 2);
									$total_factura		= number_format($precio + $consumo, 2);
								?>

								<tr>
									<td>
										$. <?php echo $data_alojamiento['precio']; ?>
									</td>
									<td>
										$. <?php echo $data_alojamiento['anticipo']; ?>
									</td>
									<td></td>
									<td></td>
									
									<td style="border-right-width:3px;border-right-style:solid";>
										<input class="form-control col-md-3" type="text" name="total_hospedaje" value="<?php echo $total_hospedaje; ?>" readonly>
									</td>
								</tr>
								<thead class="thead-light">
								<tr>
									<th colspan="7" style="border-right-width:3px;border-right-style:solid">Servicio a la Habitación</th>
									
								</tr>
								</thead>	
								<tr>
									<td>
										Descripción
									</td>
									<td>
										Precio Unitario
									</td>
									<td>
										Cantidad
									</td>
									<td >
										Total
									</td>
																
								</tr style="border-right-width:3px;border-right-style:solid";>
								<thead>
								
								<tr>
									<td>
										<?php echo $data_alojamiento['descripcion']; ?>																			
									</td>
									<td>
										<?php echo $data_alojamiento['punitario']; ?>
									</td>
									<td>
										<?php echo $data_alojamiento['cantidad']; ?>
									</td>
									<td>
										<?php echo $data_alojamiento['precio_consumo']; ?>
									</td>								
								</tr>

								<tr>
									<td></td>
									<td></td>
									<td></td>
									
									<td colspan="5" class="col-md-4" style="border-right-width:3px;border-right-style:solid";><h5>Total a Pagar $.</h5></td>
									<td>
										<input class="form-control col-md-3" type="text" name="total" id="total" value="<?php echo $total; ?>" readonly>
										<input type="hidden" name="total_pago" id="total_pago" value="<?php echo $total_factura; ?>">

									</td>	
								</tr>
								
								</thead>
							</table>
																										
											<div class="tile-footer">
												<center>
													<button type="submit" id="register" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-print"></i> Imprimir Factura</button>&nbsp;&nbsp;&nbsp;<button type="submit" id="#" class="btn btn-warning"><i class="fa fa-fw fa-lg fa-print"></i> Imprimir Ticket</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
												</center>
											</div>
											
										</div>
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
	<!-- Data table plugin
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tablacheck').DataTable();</script>-->
	<!-- The javascript plugin to display page loading on top-->
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
		<<script type="text/javascript">
			$(function(){
				$('#register').click(function(e){
					var valid = this.form.checkValidity();

					if(valid){
						var idalojamiento            = $('#idalojamiento').val();
						var fecha_emision			= $('#fecha_emision').val();
						
						//var numero_comprobante 		= $('#numero_comprobante').val();
						var total_pago 				= $('#total_pago').val();



						e.preventDefault();	

						$.ajax({
							type: 'POST',
							url: 'proceso_checkout.php',
							data: {idalojamiento:idalojamiento,fecha_emision: fecha_emision,total_pago:total_pago},
								success: function(data){
									Swal.fire({
										icon: 'success',
										title: 'Guardando...',
										text: 'Datos registrados correctamente',
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