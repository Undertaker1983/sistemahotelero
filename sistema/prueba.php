<?php 
session_start();
include "../conexion.php";	

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Recepción</title>
</head>

<?php 
//include "../conexion.php";
include "includes/nav_admin.php";

?>
<main class="app-content" >
	<div class="app-title">
		
		<h1><i class="fa fa-th-list"></i> Recepción</h1>
		
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tablahab">
							<thead class="thead-dark">
								<tr>
									<th width="90px">Habitación</th>
									<th width="100px">Condicion</th>
									<th>Detalles</th>
									<th width="130px">Opciones</th>
								</tr>
							</thead>
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
										$condicion = '<span class="disponible">DISPONIBLE</span>';
									}
									if ($data["condicion"] == 'Limpieza') {
										$condicion = '<span class="limpieza">LIMPIEZA</span>';
									}
									if ($data["condicion"] == 'Ocupado') {
										$condicion = '<span class="ocupado">OCUPADO</span>';
									}
									if ($data["condicion"] == 'Mantenimiento') {
										$condicion = '<span class="mantenimiento">MANTENIMIENTO</span>';
									}
									?>
									
									<tr class="row<?php echo $data["idhabitacion"]; ?>">
										<td><?php echo $data["nombre_habitacion"]; ?></td>
										<td><?php echo $condicion; ?></td>
										<td><?php echo $data["detalles"]; ?></td>
										<td>
											<div class="btn-group">
												<a class="btn btn-primary"  title="Ingresar" href="registrar_estadia.php?id=<?php echo $data['idhabitacion']; ?>"><i class="fa fa-edit"></i> Ingresar</a>
												<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>
													<a class="btn btn-danger act_limpieza" habitacion_id="<?php echo $data["idhabitacion"]; ?>" title="Realizar Limpieza" href="#"><i class="fa fa-hourglass-start"></i></a>
													<a class="btn btn-info limp_habitacion" habitacion_id="<?php echo $data["idhabitacion"]; ?>" title="Finalizar Limpieza" href="#"><i class="fa fa-spinner"></i></a>	
													
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

</main>

<!--Essential javascripts for application to work-->
<script src="js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<!-- The javascript plugin to display page loading on top
<script src="js/plugins/pace.min.js"></script>-->
<!-- Page specific javascripts
<script src="js/sweetalert2.all.min.js"></script>-->


<script type="text/javascript" src="js/plugins/jquery.dataTables.js"></script>
<!-- Data table plugin
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>-->
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
	$('#tablahab').DataTable({
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
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
<script type="text/javascript">
	$('.act_limpieza').click(function(e) {
    e.preventDefault();
    var habitacion_id = $(this).attr('habitacion_id');
    var action = 'actLimpieza';
    
    Swal.fire({
                            title: 'Realizar la limpieza de la habitación?',
                            //text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, realizar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {habitacion_id:habitacion_id, action:action},
                                    
                                    success: function(data){
                                    //$('.row'+habitacion_id).remove() ;
                                    window.location.reload();
                                    Swal.fire(
                                    'En Limpieza!',
                                    'La Habitación estará en limpieza.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se realizó la limpieza.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
});
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