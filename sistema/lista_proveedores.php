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
	<title>Proveedores</title>
</head>

<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>
<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-truck"></i>  Proveedores</h1>
		<a href="registro_persona.php" class="btn btn-primary btn_new">Nuevo Proveedor</a>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tablacliente">
							<thead class="thead-dark">
								<tr>
									<th>Cedula/Ruc</th>
									<th>Nombre</th>
									<th>Razón Social</th>
									<th width="200px">Telefono</th>
									<th>Dirección</th>
									<th width="130px">Opciones</th>
								</tr>
							</thead>	
							<?php 
							
							$query = mysqli_query($conection,"SELECT p.idpersona,p.cedula,p.idtipo,p.nombre,p.razon_social,p.telefono,p.direccion,tp.tipo FROM personas p INNER JOIN 
								tipo_persona tp ON p.idtipo = tp.idtipo 
								WHERE p.status = 1 AND p.idtipo = 2
								ORDER BY p.idpersona 
								");

							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									
									?>
									<tr class="row<?php echo $data["idpersona"]; ?>">
										<td><?php echo $data["cedula"]; ?></td>
										<td><?php echo $data["nombre"]; ?></td>
										<td class="textcenter"><?php echo $data["razon_social"]; ?></td>	
										<td><?php echo $data["telefono"]; ?></td>
										<td><?php echo $data['direccion'] ?></td>
										<td class="textcenter">
											<div class="btn-group">
												<a class="btn btn-warning btn_viewpersonas" title="Ver" type="button" href="verpersona.php?id=<?php echo $data['idpersona']; ?>"><i class="fa fa-eye"></i></i></a>
												<a class="btn btn-info btn_editpersonas" title="Editar" href="editar_proveedor.php?id=<?php echo $data['idpersona']; ?>"><i class="fa fa-edit"></i></a>
												<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>
													<a class="btn btn-danger del_persona" title="Eliminar" persona_id="<?php echo $data["idpersona"]; ?>" href="#"><i class="fa fa-trash"></i></a>
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
<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="js/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<!-- Data table plugin-->
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">$('#tablacliente').DataTable({
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
<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->

</body>
</html>