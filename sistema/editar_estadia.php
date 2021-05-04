<?php 
session_start();
if($_SESSION['rol'] != 1)
{
	header("location: ./");
}
date_default_timezone_set("America/Bogota");
include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['fecha_ingreso']))
	{
		$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
	}else{
		
		$idalj 			    	= $_POST['idalj'];
		$idhabitacion 			= $_POST['idhabitacion'];
		$idpersona 				= $_POST['idpersona'];
		$fecha_ingreso			= date("Y-m-d");
		$hora_ingreso			= date("H:i");
		$fecha_salida  			= $_POST['fecha_salida'];
		$hora_salida   			= $_POST['hora_salida'];
		$cant_personas   		= $_POST['cant_personas'];
		$cant_noches   			= $_POST['cant_noches'];
		$estado_pago   			= $_POST['estado_pago'];
		$medio_pago   			= $_POST['medio_pago'];
		$precio   				= $_POST['precio'];
		$estado_habitacion		= "Ocupado";
		
		$sql_update = mysqli_query($conection,"UPDATE alojamiento SET idhabitacion = $idhabitacion, idpersona = $idpersona,fecha_ingreso = '$fecha_ingreso', hora_ingreso = '$hora_ingreso',fecha_salida = '$fecha_salida',precio = '$precio',cant_personas = '$cant_personas',cant_noches = '$cant_noches',estado_pago = '$estado_pago',hora_salida = '$hora_salida',medio_pago = '$medio_pago' WHERE idalojamiento = $idalj");

		$query_update = mysqli_query($conection,"UPDATE habitaciones SET condicion = '$estado_habitacion' WHERE idhabitacion = $idhabitacion");

		if($sql_update){
			$alert='<p class="msg_save">Registro actualizado correctamente.</p>';
		}else{
			$alert='<p class="msg_error">Error al actualizar el registro.</p>';
		}

	}
}			


//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: recepcion.php");
	mysqli_close($conection);
}else{
	$id_habitacion = $_REQUEST['id'];
	

	$query_habitacion = mysqli_query($conection,"SELECT h.idhabitacion,h.nombre_habitacion,h.detalles,h.condicion,c.idcategoria,c.nombre_categoria	FROM  habitaciones h INNER JOIN
		categorias c ON h.idcategoria = c.idcategoria
		WHERE h.idhabitacion = $id_habitacion AND h.estado = 1");
	$result_habitacion = mysqli_num_rows($query_habitacion);
			//mysqli_close($conection);

	if ($result_habitacion > 0 ) {
		$data_habitacion = mysqli_fetch_array($query_habitacion);
				//print_r($data_orden);
	}
	else{
		header("location: recepcion.php");
	}
}

if (empty($_REQUEST['idalj'])) {
	header("location: recepcion.php");
	mysqli_close($conection);
}else{
	$id_alojamiento = $_REQUEST['idalj'];
	

	$query_alojamiento = mysqli_query($conection,"SELECT a.idalojamiento,a.idhabitacion,a.idpersona,a.fecha_ingreso,a.hora_ingreso,a.fecha_salida,a.hora_salida,a.precio,a.cant_noches,a.cant_personas,a.estado_pago,a.medio_pago,h.nombre_habitacion,p.cedula,p.nombre,p.telefono,p.direccion,p.correo,p.razon_social,p.entidad_financiera,p.tipo_cuenta,p.numero_cuenta FROM  alojamiento a INNER JOIN
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
		header("location: recepcion.php");
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Alojamiento</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Actualizar Alojamiento</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<div class="tile">
					<table class="table">
						<thead>
							<tr>
								<h4>Datos de la Habitación</h4>
							</tr>	
						</thead>			
						<tr>
							<td>
								<label for="nombre"><font color="#0982C3"><b>Nombre</b></font></label>
							</td>
							<td>
								<?php echo $data_habitacion['nombre_habitacion']; ?>
								
							</td>
							<td>
								<label for="categoria"><font color="#0982C3"><b>Categoria</b></font></label>
							</td>
							<td>
								<?php echo $data_habitacion['nombre_categoria'] ?>
							</td>
							
						</tr>
						<tr>	


							<td>
								<label for="detalles"><font color="#0982C3"><b>Detalles</b></font></label>
							</td>

							<td>
								<?php echo $data_habitacion['detalles']; ?>

							</td>
							<td>
								<label for="estado"><font color="#0982C3"><b>Estado</b></font></label>
							</td>
							<td>
								<?php echo $data_habitacion['condicion']; ?>

							</td>
						</tr>
					</table>	
				</div>
				
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="tile">
				
				
				<h4>Datos del Cliente</h4>
				<hr>
				<div class="form-row">

					<table width="600px">
						<tbody>
							<tr>
								<td width="130px">
									<label class="col-form-label"><font size="2">N° de Identificación</font></label>
								</td>
								<td width="155px">
									<input class="form-control" type="text" name="nit_cliente" id="nit_cliente" value="<?php $data_alojamiento['cedula'] ?>">
								</td>
								<td width="70px">
									<label class="col-form-label"><font size="2">Email</font></label>
								</td>
								<td>
									<input class="form-control" type="text" name="cor_cliente" id="cor_cliente" value="<?php $data_alojamiento['correo'] ?>" disabled>
								</td>
							</tr>
						</tbody>
					</table>

					<table width="600px">
						<tbody>		
							<tr>	
								<td width="130px">
									<label class="col-form-label"><font size="2">Nombres</font></label>

								</td>
								<td>
									<input type="text" class="form-control" name="nom_cliente" id="nom_cliente" value="<?php echo $data_alojamiento['nombre'] ?>" required>
								</td>
								<td width="148px">
									<!--<a href="registro_persona.php" class="nav-link"><i class="fa fa-plus"></i> Nuevo Cliente</a>-->
									<a href="#" class="nav-link add_cliente"><font size="2"><i class="fa fa-plus"></i> Nuevo Cliente</font></a>
								</td>

							</tr>
						</tbody>
					</table>
					<table width="600px">
						<tbody>
							<tr>
								<td width="130px">
									<label class="col-form-label"><font size="2">Teléfono</font></label>
								</td>
								<td width="150px">
									<input type="text" class="form-control" name="tel_cliente" id="tel_cliente" value="<?php echo $data_alojamiento['telefono'] ?>" disabled required>
								</td>
								<td>
									<label class="col-form-label"><font size="2">Dirección</font></label>
								</td>
								<td>
									<input type="text" class="form-control" name="dir_cliente" id="dir_cliente" value="<?php echo $data_alojamiento['direccion'] ?>" disabled>
								</td>
							</tr>
						</tbody>
					</table>
					<table width="600px">
						<tbody>
							<tr>
								<td width="130px">
									<label class="col-form-label"><font size="2">Razón Social</font></label>
								</td>
								<td >
									<input type="text" class="form-control" name="razon_cliente" id="razon_cliente" value="<?php echo $data_alojamiento['razon_social'] ?>" disabled>
								</td>

							</tr>
						</tbody>
					</table>
				</div>	
			</form>
		</div>
	</div>
	<div class="col-md-6">
		<div class="tile">
			<div class="form-row">
				<form action="" method="post">
					<input type="hidden" id="idalj" name="idalj" value="<?php echo $data ['idalojamiento']; ?>">
					<input type="hidden" id="idhabitacion" name="idhabitacion" value="<?php echo $data_habitacion['idhabitacion']; ?>">
					<input type="hidden" id="idpersona" name="idpersona" value="">
					<h4>Datos del Alojamiento</h4>
					<hr>
					<div class="input-group">
						<div class="input-group-prepend">
							<!--<label class="col-form-label"><font size="2">Fecha de salida</font></label>-->
							<span class="input-group-text"><font size="2">Fecha de ingreso</font></span>
							<input class="form-control col-md-4 textright" type="text" name="fecha_ingreso" id="fecha_ingreso" value="<?php echo date("d/m/Y");?>" readonly>
							
							<!--<label class="col-form-label"><font size="2">Hora de salida</font></label>-->
							<span class="input-group-text "><font size="2">Hora de ingreso</font></span>
							<input class="form-control col-md-3 textright" type="text" name="hora_ingreso" id="hora_ingreso" value="<?php echo date("H:i");?>" readonly>
						</div>
					</div><br>
					<div class="input-group">		
						
						<div class="input-group-prepend">	
							<!--<label class="col-form-label"><font size="2">Precio $.</font></label>-->
							<span class="input-group-text"><font size="2">Precio $.</font></span>
							<input class="form-control col-md-4" type="number" name="precio" id="precio" value="<?php echo $data_habitacion['precio'] ?>">
							
							<!--<label class="col-form-label"><font size="2">Cant. noches</font></label>-->
							<span class="input-group-text"><font size="2">Cant. noches</font></span>
							<input class="form-control col-md-2" type="number" name="cant_noches" id="cant_noches" value="<?php echo $data_habitacion['precio'] ?>" min="1">
							
							<!--<label class="col-form-label"><font size="2">Cant. personas</font></label>-->
							
						</div>										
					</div><br>		
					
									<!--<div>
											
											<label><font color="green" size="4"><b>Total a Pagar: </b></font></label>
											<output id="precio"></output>
											<input type="text" name="ptotal" id="ptotal" value="$ptotal" readonly>	
										</div>-->
										
										<div class="input-group">					
											<div class="input-group-prepend">	
												<span class="input-group-text"><font size="2">Cant. personas</font></span>	
												<input class="form-control col-md-2" type="text" name="cant_personas" id="cant_personas">

												<span class="input-group-text"><font size="2">Estado de Pago</span>	
													
													<select class="form-control" id="estado_pago">
														<option>Cancelado</option>
														<option>Falta Cancelar</option>
													</select>
													
												</div>
											</div><br>		
											<div class="input-group">		
												<div class="input-group-prepend">			
													<span class="input-group-text"><font size="2">Medio de Pago</font></span>
													
													<select class="form-control" id="medio_pago">
														<option>Efectivo</option>
														<option>Tarjeta de Crédito</option>
														<option>Tarjeta de Dédito</option>
														<option>Depósito o Transferencia</option>
													</select>
												</div>
											</div><br>		
											<div class="input-group">
												<div class="input-group-prepend">
													<!--<label class="col-form-label"><font size="2">Fecha de salida</font></label>-->
													<span class="input-group-text"><font size="2">Fecha de salida</font></span>
													<input class="form-control col-md-4" type="date" name="fecha_salida" id="fecha_salida">
													
													<!--<label class="col-form-label"><font size="2">Hora de salida</font></label>-->
													<span class="input-group-text"><font size="2">Hora de salida</font></span>
													<input class="form-control col-md-3" type="time" name="hora_salida" id="hora_salida">
												</div>
											</div><br>
											


											
											<div class="tile-footer">
												<center>
													<button type="submit" id="register" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i> Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
												</center>
											</div>
											
										</div>
									</div>
								</div>	
							</form>
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
						<script type="text/javascript">
							$(function(){
								$('#register').click(function(e){
									var valid = this.form.checkValidity();

									if(valid){
										var idhabitacion            = $('#idhabitacion').val();
										var idpersona 				= $('#idpersona').val();
										var fecha_ingreso			= $('#fecha_ingreso').val();
										var hora_ingreso 			= $('#hora_ingreso').val();
										var fecha_salida 			= $('#fecha_salida').val();
										var hora_salida 			= $('#hora_salida').val();
										var cant_personas 			= $('#cant_personas').val();
										var cant_noches 			= $('#cant_noches').val();
										var medio_pago 				= $('#medio_pago').val();
										var estado_pago 			= $('#estado_pago').val();
										var precio 					= $('#precio').val();
										


										e.preventDefault();	

										$.ajax({
											type: 'POST',
											url: 'registrar_estadia.php',
											data: {idhabitacion:idhabitacion,idpersona: idpersona,fecha_ingreso: fecha_ingreso,hora_ingreso
												: hora_ingreso,fecha_salida: fecha_salida,hora_salida:hora_salida,cant_personas:cant_personas,cant_noches,medio_pago: medio_pago,estado_pago:estado_pago,precio:precio},
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
						<script type="text/javascript">
							$(function() {
								$("#nom_cliente").autocomplete({
									source: "personas.php",
									minLength: 2,
									select: function(event, ui) {
										event.preventDefault();
										$('#nom_cliente').val(ui.item.nombre);
										$('#nit_cliente').val(ui.item.cedula);
										$('#tel_cliente').val(ui.item.telefono);
										$('#dir_cliente').val(ui.item.direccion);
										$('#cor_cliente').val(ui.item.correo);
										$('#razon_cliente').val(ui.item.razon_social);
										$('#idpersona').val(ui.item.idpersona);
									}
								});
							});
						</script>
						<script>
							function calc() {
			/*var a = $('#precio_uni').val();
			var b = $('#cant_noches').val();*/
			var a = document.getElementById("precio_uni").value;
			var b = document.getElementById("cant_noches").value;

			var precio = document.getElementById("precio");
			precio.value = parseInt(a) * parseInt(b);
			
		}
	</script>
</body>
</html>