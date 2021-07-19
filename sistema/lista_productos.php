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
	<title>Productos</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>
<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-cube"></i> Productos Registrados</h1>
		<a href="registro_producto.php" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Registrar Nuevo producto</a>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">		
						<table class="table table-hover table-bordered" id="tablaproductos">
							<thead class="thead-dark">
								<tr>
									<th width="80px">Id.</th>
									<th>Tipo</th>
									<th>Descripción</th>
									<th width="70px">Precio</th>
									<th width="70px">Existencia</th>
									<th>PROVEEDORES
										<!--<?php 
										$query_proveedor = mysqli_query($conection, "SELECT * FROM personas WHERE status = 1 AND idtipo = 2 ORDER BY razon_social ASC");
										$result_proveedor = mysqli_num_rows($query_proveedor);
										?>
										<div class="box">
											<select name="proveedor" id="search_proveedor">
												<option value="" selected>PROVEEDORES</option>
												<?php 
												if ($result_proveedor > 0) {
													while ($proveedor = mysqli_fetch_array($query_proveedor)) {
														?>
														<option value="<?php echo $proveedor['idpersona']; ?>"><?php echo $proveedor['razon_social']; ?></option>
														<?php 

													}
												}
												?>
											</select>
										</div>-->
									</th>
									<th width="120px">Fotos</th>
									<th width="130px">Opciones</th>
								</tr>
							</thead>
							<?php 

							$query = mysqli_query($conection,"SELECT p.codproducto,tp.producto_servicio,p.descripcion,p.precio,p.existencia,ps.razon_social,p.foto FROM producto p INNER JOIN personas ps 
								ON p.proveedor=ps.idpersona
								INNER JOIN tipo_producto tp
								ON p.idtipo = tp.idtipo  
								WHERE p.status = 1 ORDER BY p.codproducto");

							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									if ($data['foto']!= 'img_producto.png') {
										$foto = 'img/uploads/'.$data['foto'];

									}else{
										$foto = 'img/'.$data['foto'];	
									}

									?>
									<tr class="row<?php echo $data["codproducto"]; ?>">
										<td><?php echo $data["codproducto"]; ?></td>
										<td><?php echo $data["producto_servicio"]; ?></td>
										<td><?php echo $data["descripcion"]; ?></td>
										<td class="celPrecio"><?php echo $data["precio"]; ?></td>
										<td class="celExistencia"><?php echo $data["existencia"]; ?></td>
										<td><?php echo $data['razon_social'] ?></td>
										<td class="img_producto"><img src="<?php echo $foto; ?>" alt="<?php echo $data["descripcion"]; ?>"></td>

										<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>

											<td>
												<div class="btn-group">
													<a class="btn btn-primary add_product" title="Agregar" product="<?php echo $data["codproducto"]; ?>" href="#"><i class="fa fa-plus"></i></a>

													<a class="btn btn-info btn_edit" title="Editar" href="editar_producto.php?id=<?php echo $data["codproducto"]; ?>"><i class="fa fa-edit"></i></a>


													<a class="btn btn-danger del_product" title="Eliminar" href="#" producto_id="<?php echo $data["codproducto"]; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
												<?php } ?>
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

<!-- Data table plugin-->
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
<script src="js/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>

<script type="text/javascript">
	$('#tablaproductos').DataTable({
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
		},
  //para usar los botones   
  responsive: "true",
  dom: "Bfrtilp",

  buttons:[ 
  {
  	extend:    'excelHtml5',
  	text:      '<i class="fa fa-file-excel-o"></i> ',
  	titleAttr: 'Exportar a Excel',
  	className: 'btn btn-success'
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
  ]	     
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