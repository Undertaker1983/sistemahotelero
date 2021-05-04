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
	<title>Usuarios</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>
<main class="app-content">
	<div class="app-title">		
		<h1> <i class="fa fa-users"></i> Usuarios Registrados</h1>
		<a href="registro_usuario.php" class="btn btn-primary"><i class="fa fa-user-plus"></i> Crear usuario</a>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">			

						<table class="table table-hover table-bordered" id="tablausuarios">
							<thead class="thead-dark">
								<tr>
									<th>ID</th>
									<th>Nombre</th>
									<th>Correo</th>
									<th>Usuario</th>
									<th>Rol</th>
									<th style="width: 15%">Acciones</th>
								</tr>
							</thead>
							<?php 

							$query = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estatus = 1 ORDER BY u.idusuario ASC");

							mysqli_close($conection);

							$result = mysqli_num_rows($query);
							if($result > 0){

								while ($data = mysqli_fetch_array($query)) {

									?>
									<tr class="row<?php echo $data["idusuario"]; ?>">
										<td><?php echo $data["idusuario"]; ?></td>
										<td><?php echo $data["nombre"]; ?></td>
										<td><?php echo $data["correo"]; ?></td>
										<td><?php echo $data["usuario"]; ?></td>
										<td><?php echo $data['rol'] ?></td>
										<td class="textcenter">
											<div lass="btn-group">
												<a class="btn btn-info" title="Editar" href="editar_usuario.php?id=<?php echo $data["idusuario"]; ?>"><i class="fa fa-edit"></i></a>

												<?php if($data["idusuario"] != 1){ ?>

													<a class="btn btn-danger del_usuario" title="Eliminar" usuario_id="<?php echo $data["idusuario"]; ?>" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
<script type="text/javascript">$('#tablausuarios').DataTable({
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