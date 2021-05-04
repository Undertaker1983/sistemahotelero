<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Configuraciones</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php";
	?>		

	<?php  

//Datos Empresa
	$nit = '';
	$nombreEmpresa = '';
	$razonSocial = '';
	$telEmpresa = '';
	$emailEmpresa = '';
	$dirEmpresa = '';
	$iva = '';

	$query_empresa = mysqli_query($conection,"SELECT * FROM configuracion");
	$row_empresa = mysqli_num_rows($query_empresa);

	if ($row_empresa > 0) {
		while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)) {
			$nit = $arrInfoEmpresa['cedula'];
			$nombreEmpresa = $arrInfoEmpresa['nombre'];
			$razonSocial = $arrInfoEmpresa['razon_social'];
			$telEmpresa = $arrInfoEmpresa['telefono'];
			$emailEmpresa = $arrInfoEmpresa['email'];
			$dirEmpresa = $arrInfoEmpresa['direccion'];
			$iva = $arrInfoEmpresa['iva'];
		}
	}


	$query_dash = mysqli_query($conection,"CALL dataDashboard();");
	$result_dash = mysqli_num_rows($query_dash);
	if ($result_dash > 0) {
		$data_dash = mysqli_fetch_assoc($query_dash);
		//mysqli_close($conection);
	}

	?>
	
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  EMPRESA</h1>
		</div>

		<?php if ($_SESSION['rol'] == 1) {?>

			<div class="form-row">

				<div class="tarjeta">

					<form action="" method="post" name="frmEmpresa" id="frmEmpresa">
						<input type="hidden" name="action" value="updateDataEmpresa">
						<!--<div class="alertFormEmpresa" style="display: none;"></div>-->
						<div class="form-row">	
							<div class="form-group col-md-4">
								<label class="col-form-label">N° Identificación/RUC:</label><input class="form-control inputid" type="text" name="txtNit" placeholder="N° de Identificación de la Empresa" value="<?= $nit; ?>" required="">
							</div>
							<div class="form-group col-md-8">
								<label class="col-form-label">Nombre de la Empresa:</label> <input class="form-control inputemp" type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la Empresa" value="<?= $nombreEmpresa; ?>" required>
							</div>

							<div class="form-group col-md-8">
								<label class="col-form-label">Razón Social:</label> <input class="form-control inputrazon" type="text" name="txtRSocial" id="txtRSocial" placeholder="Razón Social" value="<?= $razonSocial; ?>" required>
							</div>
							<div class="form-group col-md-4">	
								<label class="col-form-label">Teléfono:</label> <input class="form-control inputemp" type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de teléfono" value="<?= $telEmpresa ?>" required>
							</div>	
							<div class="form-group col-md-8">
								<label class="col-form-label">Correo Eléctronico:</label> <input class="form-control inputemp" type="text" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo Eléctronico" value="<?= $emailEmpresa; ?>" required>
							</div>

							<div class="form-group col-md-8">
								<label class="col-form-label">Dirección:</label> <input class="form-control inputrazon" type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la Empresa" value="<?= $dirEmpresa; ?>" required>
							</div>

							<div class="form-group col-md-4">
								<label class="col-form-label">IVA (%):</label> <input class="form-control inputiva" type="text" name="txtIva" id="txtIva" placeholder="Impuesto al Valor Agregado (IVA)" value="<?= $iva; ?>" required>
							</div>
						</div>
						<div class="tile-footer">
							<center>
								<button type="submit" class="btn btn-primary btnChangePass"><i class="fa fa-save"></i> Guardar Datos</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
							</center>
						</div>
					</form>
					
				<?php } ?>

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
	<script type="text/javascript">
		$(function(){
			$('#register').click(function(e){
				var valid = this.form.checkValidity();

				if(valid){
					var id                      = $('#id').val();
					var nombre_habitacion 		= $('#nombre_habitacion').val();
					var idpiso					= $('#idpiso').val();
					var idcategoria 			= $('#idcategoria').val();
					var detalles 				= $('#detalles').val();
					var precio 					= $('#precio').val();


					e.preventDefault();	

					$.ajax({
						type: 'POST',
						url: 'registro_habitacion.php',
						data: {id:id,nombre_habitacion: nombre_habitacion,idpiso: idpiso,idcategoria: idcategoria,detalles: detalles,precio: precio},
						success: function(data){
							Swal.fire({
								icon: 'success',
								title: 'Actualizando...',
								text: 'Datos actualizados correctamente',
								showConfirmButton: true,
									/*'title': 'Successful',
									'text': data,
									'type': 'success'*/
								});

						},
						error: function(data){
							Swal.fire({
								title: 'Error',
								text: 'Error al actualizar el registro.',
								type: 'error'
							});
						}
					});


				}else{

				}





			});		


		});	
	</script>
</body>
</html>