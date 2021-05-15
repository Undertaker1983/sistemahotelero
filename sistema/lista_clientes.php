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
	<title>Clientes</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>

<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-users"></i> Clientes</h1>
		<a href="registro_persona.php" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Registrar Nuevo Cliente</a>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tablacliente">
							<thead class="thead-dark">
								<tr>
									<th>Nombre</th>
									<th>Cedula/Ruc</th>
									<th>Telefono</th>
									<th>Dirección</th>
									<th width="150px">Opciones</th>
								</tr>
							</thead>	
							<?php 

							$query = mysqli_query($conection,"SELECT p.idpersona,p.cedula,p.idtipo,p.nombre,p.telefono,p.direccion,tp.tipo FROM personas p INNER JOIN 
								tipo_persona tp ON p.idtipo = tp.idtipo 
								WHERE p.status = 1 AND p.idtipo = 1
								ORDER BY p.idpersona 
								");

							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {
									
									?>
									<tr class="row<?php echo $data["idpersona"]; ?>">
										<td><?php echo $data["nombre"]; ?></td>
										<td><?php echo $data["cedula"]; ?></td>
										<td><?php echo $data["telefono"]; ?></td>
										<td><?php echo $data["direccion"] ?></td>
										<td>
											<div class="btn-group">
												<a class="btn btn-warning" title="Ver" type="button" href="verpersona.php?id=<?php echo $data['idpersona']; ?>"><i class="fa fa-eye"></i></a>
												<a class="btn btn-info"  title="Editar" href="editar_personas.php?id=<?php echo $data['idpersona']; ?>"><i class="fa fa-edit"></i></a>
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
<script src="js/plugins/pace.min.js"></script>
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