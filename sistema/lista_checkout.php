<?php 
session_start();
include "../conexion.php";	

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Lista CheckOut</title>
</head>

<?php 
include "../conexion.php";
include "includes/nav_admin.php";

?>
<main class="app-content" >
	<div class="app-title">
		
		<h1><i class="fa fa-th-list"></i> Lista CheckOut</h1>
		<!--<a href="#" class="btn btn-primary add_habitacion">Nueva Habitación</a>-->
				
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					
							<?php
							$ocupado = "Ocupado";
							
							$query = mysqli_query($conection,"SELECT a.idalojamiento,a.idhabitacion,a.idpersona,a.cant_noches,a.cant_personas,a.e_alojamiento,a.estado_pago,a.medio_pago,a.estado,h.nombre_habitacion,h.condicion	FROM  alojamiento a INNER JOIN
								habitaciones h ON a.idhabitacion = h.idhabitacion 
								WHERE a.e_alojamiento = 'hospedado' AND h.condicion = 'Ocupado'");


							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									if ($data["estado_pago"] == 'Falta Cancelar') {
										$condicion = 'danger';
										$icondicion = 'icon fa fa-ban fa-3x';
										
									}
									if ($data["estado_pago"] == 'Cancelado') {
										$condicion = 'primary';
										$icondicion = 'icon fa fa-money fa-3x';
										//$act_condicion = 'fin_mantenimiento';
										//$href = '#';
									}
									?>
									<div class="col-md-3">
										<a class="simple" href="proceso_checkout.php?id=<?php echo $data["idalojamiento"] ?>">
										<div class="widget-small <?php echo $condicion?>"><i class="<?php echo $icondicion; ?>"></i>
											<div class="info">
												<!--<a href="lista_productos.php"></a>-->
												<h4><?php echo $data["nombre_habitacion"]; ?>
												<p><?php echo $data["estado_pago"]; ?></p>	
												</h4>
												
											</div>
										</div>
										</a>
									</div>

													
									
									<?php 
								}

							}
							?>
							
						
					
				</div>
			</div>
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
<!-- Page specific javascripts
<script src="js/sweetalert2.all.min.js"></script>-->
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>

<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="js/sweetalert2.all.min.js"></script>

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