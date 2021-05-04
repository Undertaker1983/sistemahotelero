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
	<title>Ventas</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";

?>

<main class="app-content">
	<div class="app-title">
		
		<h1><i class="fa fa-th-list"></i> Ventas Registradas</h1>
		<a href="nueva_venta.php" class="btn btn-primary btnNewVenta">Nueva Venta</a>				
	</div>		

	<div>
		<h5>Buscar por Fecha</h5>
		<form action="buscar_venta.php" method="get" class="form_search_date">
			<label>De:</label>
			<input type="date" name="fecha_de" id="fecha_de" required>
			<label>A</label>
			<input type="date" name="fecha_a" id="fecha_a" required>
			<button type="submit" class="btn btn-primary btn_new"><i class="fa fa-search"></i></button>
		</form>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">

						<table class="table table-hover table-bordered" id="tablaclientes">
							<thead class="thead-dark">
								<tr>
									<th>N°.</th>
									<th width="130px">Fecha/Hora</th>
									<th>Cliente</th>
									<th>Vendedor</th>
									<th width="100px">Estado</th>
									<th width="120px">Total Factura</th>
									<th width="90px">Opciones</th>
								</tr>
							</thead>
							<?php 
							$query = mysqli_query($conection,"SELECT f.nofactura,f.fecha,f.totalfactura,f.codpersona,f.status,u.nombre as vendedor,
								p.nombre as cliente
								FROM factura f 
								INNER JOIN usuario u 
								ON f.usuario = u.idusuario
								INNER JOIN personas p
								ON f.codpersona = p.idpersona
								WHERE f.status != 10 
								ORDER BY f.fecha");

							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									if ($data["status"] == 1) {
										$estado = '<span class="pagada">Pagada</span>';

									}else{
										$estado = '<span class="anulada">Anulada</span>';
									}
									?>
									<tr id="row_<?php echo $data["nofactura"]; ?>">

										<td><span class="n_ventas"><?php echo $data["nofactura"]; ?></span></td>
										<td><?php echo $data["fecha"]; ?></td>
										<td><?php echo $data["cliente"]; ?></td>
										<td><?php echo $data['vendedor'] ?></td>
										<td class="estado textcenter"><?php echo $estado; ?></td>
										<td class="textright totalfactura"><span>$.</span><?php echo $data["totalfactura"]; ?></td>

										<td>
											
												<div class="btn-group" >
													<button class="btn btn-warning view_factura" title="Ver" type="button" cl="<?php echo $data["codpersona"]; ?>" f="<?php echo $data['nofactura']; ?>"><i class="fa fa-eye"></i></button>
												


												<?php
												if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
													if ($data["status"] == 1) {

														?>			
													
															<button class="btn btn-danger anular_factura" title="Anular" fac="<?php echo $data["nofactura"]; ?>"><i class="fa fa-ban"></i></button>
														
													<?php }else{ ?>	
														
															<button type="button" class="btn btn-danger inactive"><i class="fas fa-ban"></i></button>
														

													<?php  }
												}
												?>
												</div>
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
<!-- Data table plugin-->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tablaclientes').DataTable({
  "language": {
    
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
  }
});
</script>		
<!-- Google analytics script-->
<script type="text/javascript">
	if(document.location.hostname == 'pratikborsadiya.in') {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-72504830-1', 'auto');
		ga('send', 'pageview');
	}
</script>
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
</body>
</html>