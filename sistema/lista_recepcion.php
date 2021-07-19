<?php 
session_start();
include "../conexion.php";	

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Registrados</title>
</head>

<?php 
include "../conexion.php";
include "includes/nav_admin.php";

?>

<main class="app-content" >

	<div class="app-title">
		
		<h1><i class="fa fa-th-list"></i> Alojamiento Registrados</h1>
		<!--<a href="#" class="btn btn-primary add_habitacion">Nueva Habitación</a>-->

	</div>

	<div>
		<h5>Buscar por Fecha</h5>
		<form action="buscar_registrados.php" method="get" class="form_search_date">
			
			<label class="control-label">De: </label>	
			&nbsp;&nbsp;<input type="date" name="fecha_de" id="fecha_de" required>
			&nbsp;&nbsp;<label class="control-label">A </label>
			&nbsp;&nbsp;<input type="date" name="fecha_a" id="fecha_a" required>
			<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tablahab">
							<thead class="thead-dark">
								<tr>
									<th width="40px">Hab.</th>
									<th width="220px">Huesped</th>
									<th width="80px">F. Ingreso</th>
									<th width="85px">H. Ingreso</th>
									<!--<th width="60px"># Noches</th>
										<th width="60px"># Pers.</th>-->
										<th width="80px">F. Salida</th>
										<th width="80px">H. Salida</th>
										<th width="80px">E. Pago</th>
										<th width="70px">Estado</th>
										<th width="90px">Opciones</th>
									</tr>
								</thead>
								<?php 

								$query = mysqli_query($conection,"SELECT a.idalojamiento,a.idhabitacion,a.idpersona,a.fecha_ingreso,a.hora_ingreso,a.fecha_salida,a.hora_salida,a.precio,a.cant_noches,a.cant_personas,a.estado_pago,a.medio_pago,a.e_alojamiento,h.nombre_habitacion,p.nombre	FROM  alojamiento a INNER JOIN
									habitaciones h ON a.idhabitacion = h.idhabitacion INNER JOIN
									personas p ON a.idpersona = p.idpersona 
									WHERE a.estado = 1 ORDER BY a.idalojamiento DESC, a.fecha_ingreso DESC");

								mysqli_close($conection);

								$result = mysqli_num_rows($query);
								if($result > 0){

									while ($data = mysqli_fetch_array($query)) {
										if ($data["estado_pago"] == 'Cancelado') {
											$condicion = '<span class="badge badge-primary">CANCELADO</span>';
										}
										if ($data["estado_pago"] == 'Falta Cancelar') {
											$condicion = '<span class="badge badge-danger"><font size="2">FALTA CANCELAR</font></span>';
										}
										if ($data["e_alojamiento"] == 'terminado') {
											$est = '<span class="badge badge-secondary">TERMINADO</span>';
										}
										if ($data["e_alojamiento"] == 'hospedado') {
											$est = '<span class="badge badge-info">HOSPEDADO</span>';
										}
										?>

										<tr class="row<?php echo $data["idalojamiento"]; ?>">
											<td><?php echo $data["nombre_habitacion"]; ?></td>
											<td><?php echo $data["nombre"]; ?></td>
											<td><?php echo $data["fecha_ingreso"]; ?></td>
											<td><?php echo $data["hora_ingreso"]; ?></td>
										<!--<td><?php echo $data["cant_noches"]; ?></td>
											<td><?php echo $data["cant_personas"]; ?></td>-->
											<td><?php echo $data["fecha_salida"]; ?></td>
											<td><?php echo $data["hora_salida"]; ?></td>
											<td><?php echo $condicion; ?></td>
											<td><?php echo $est; ?></td>
											<td>
												<div class="btn-group">
													<a class="btn btn-warning"  title="Imprimir" href="editar_estadia.php?idalj=<?php echo $data['idalojamiento']; ?>"><i class="fa fa-print"></i></a>
													<a class="btn btn-info"  title="Editar" href="editar_estadia.php?idalj=<?php echo $data['idalojamiento']; ?>"><i class="fa fa-edit"></i></a>
													<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>
														<a class="btn btn-danger del_alojamiento" estadia_id="<?php echo $data["idalojamiento"]; ?>" title="Eliminar" href="#"><i class="fa fa-trash"></i></a>

														<?php
													} 

													?>
												</div>
											</td>

										</tr>


										<?php 
									}

								}
								?>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<font size="2"></font>

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

	<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
	<!-- Data table plugin-->
	<script type="text/javascript" src="js/plugins/datatables.min.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>

	<!-- para usar botones en datatables JS -->  

	<script src="js/plugins/JSZip-2.5.0/jszip.min.js"></script>    
	<script src="js/plugins/pdfmake-0.1.36/pdfmake.min.js"></script>    
	<script src="js/plugins/pdfmake-0.1.36/vfs_fonts.js"></script>
	<script src="js/plugins/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>  
	<script src="js/plugins/Buttons-1.7.0/js/buttons.html5.min.js"></script>
	<script src="js/plugins/Buttons-1.7.0/js/buttons.print.min.js"></script>


	<!-- The javascript plugin to display page loading on top-->
	<script src="js/plugins/pace.min.js"></script>
	<!-- Page specific javascripts-->
	<script src="js/sweetalert2.all.min.js"></script>

	<script>
		$('#tablahab').DataTable({
			language: {

				"emptyTable": "No hay información",
				"info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
				"infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
				"infoFiltered": "(Filtrado de _MAX_ total entradas)",
				"infoPostFix": "",
				"thousands": ",",
				"lengthMenu": "Mostrar _MENU_ Entradas",
				"loadingRecords": "Cargando...",
				"processing": "Procesando...",
				"search": "Buscar:",
				"zeroRecords": "Sin resultados encontrados",
				"paginate": {
					"first": "Primero",
					"last": "Ultimo",
					"next": "Siguiente",
					"previous": "Anterior"
				}
			},
  //para usar los botones   
  responsive: "true",
  dom: "Bfrtilp",

  buttons:[ 
  {
  	extend:    'excelHtml5',
  	text:      '<i class="fa fa-file-excel-o"></i> ',
  	titleAttr: 'Exportar a Excel',
  	className: 'btn btn-success',
  	excelStyles: {                      // Add an excelStyles definition
                    template: "blue_medium",					
  },
  },
  {
  	extend:    'pdfHtml5',
  	text:      '<i class="fa fa-file-pdf-o"></i> ',
  	titleAttr: 'Exportar a PDF',
  	className: 'btn btn-danger'
  },
  {
  	extend:    'print',
  	text:      '<i class="fa fa-print"></i> ',
  	titleAttr: 'Imprimir',
  	className: 'btn btn-info'
  },
  ],	        

});
</script>

<!-- Page specific javascripts-->
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


</body>
</html>
