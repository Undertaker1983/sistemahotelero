<?php 
session_start();
include "../conexion.php";	

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Vista General Recepción</title>
</head>

<?php 
include "../conexion.php";
include "includes/nav_admin.php";

?>
<main class="app-content" >
	<div class="app-title">
		
		<h1><i class="fa fa-th-list"></i> Vista General Recepción</h1>
		
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="form-row">
					
							<?php 
							
							$query = mysqli_query($conection,"SELECT h.idhabitacion,h.nombre_habitacion,h.detalles,h.condicion,c.idcategoria,c.nombre_categoria	FROM  habitaciones h INNER JOIN
								categorias c ON h.idcategoria = c.idcategoria
								WHERE h.estado = 1 ORDER BY h.idhabitacion");
//personas p ON h.idpersona = p.idpersona INNER JOIN
							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									if ($data["condicion"] == 'Disponible') {
										$condicion = 'primary';
										$icondicion = 'icon fa fa-bed fa-3x';
										$act_condicion = '';
										
										
									}
									if ($data["condicion"] == 'Limpieza') {
										$condicion = 'info';
										$icondicion = 'icon fa fa-spinner fa-3x';
										/*$iestado	= 'icon fa fa-spinner';*/
										$act_condicion = 'limp_habitacion';
										$href	= '#';
									}
									if ($data["condicion"] == 'Ocupado') {
										$condicion = 'danger';
										$icondicion = 'icon fa fa-hourglass-start fa-3x';
										$act_condicion = '';
										$href	= '#';
									}
									if ($data["condicion"] == 'Mantenimiento') {
										$condicion = 'warning';
										$icondicion = 'icon fa fa-wrench fa-3x';
										$act_condicion = 'fin_mantenimiento';
										$href = '#';
									}
									?>
									
									<div class="col-md-3">
										<?php
										if ($data["condicion"] == 'Ocupado') {
										  ?>
										
										  <a id="btn_hab" class="simple inactive; ?>" habitacion_id="<?php echo $data["idhabitacion"]; ?>" href="lista_checkout.php">
										<div class="widget-small <?php echo $condicion;?>"><i class="<?php echo $icondicion; ?>"></i>
										
											<div class="info">
												
												<h3><?php echo $data["nombre_habitacion"]; ?></h3>
												<h4><?php echo $data["condicion"]; ?></h4>	
												
											</div>
										</div>
										</a>

										<?php }
										else
											{ ?>	
										<a id="btn_hab" class="simple <?php echo $act_condicion; ?>" habitacion_id="<?php echo $data["idhabitacion"]; ?>" href="registrar_estadia.php?id=<?php echo $data["idhabitacion"]; ?>">
										<div class="widget-small <?php echo $condicion;?>"><i class="<?php echo $icondicion; ?>"></i>
										
											<div class="info">
												
												<h3><?php echo $data["nombre_habitacion"]; ?></h3>
												<h4><?php echo $data["condicion"]; ?></h4>	
												
											</div>
										</div>
										</a>
										<?php  
										}
						  				?>
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
<!-- Data table plugin-->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tablahab').DataTable();</script>
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