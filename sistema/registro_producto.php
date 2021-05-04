<?php 
session_start();
if ($_SESSION['rol']!= 1 and $_SESSION['rol']!= 2) {
	header("location: ./");
}

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || $_POST['precio']<=0 )
	{
		$alert='<p class="msg_error">Los campos son obligatorios.</p>';
	}else{

		$proveedor 				= $_POST['proveedor'];
		$idtipo 				= $_POST['idtipo'];
		$producto 				= $_POST['producto'];
		$precio   				= $_POST['precio'];
		$cantidad  				= $_POST['cantidad'];
		$usuario_id    			= $_SESSION['idUser'];

		$foto           = $_FILES['foto'];
		$nombre_foto	= $foto['name'];
		$type			= $foto['type'];
		$url_temp		= $foto['tmp_name'];
		$imgProducto	='img_producto.png';

		if ($nombre_foto !='') {
			$destino       = 'img/uploads/';
			$img_nombre	   = 'img_'.md5(date('d-m-Y H:m:s'));
			$imgProducto   = $img_nombre.'.jpg';
			$src           = $destino.$imgProducto;
		}


		$query_insert = mysqli_query($conection,"INSERT INTO producto(proveedor,idtipo,descripcion,precio,existencia,usuario_id,foto) VALUES('$proveedor','$idtipo','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");
		if($query_insert){
			if ($nombre_foto!='' ) {
				move_uploaded_file($url_temp,$src);
			}

			$alert='<p class="msg_save">Producto registrado correctamente.</p>';
		}else{
			$alert='<p class="msg_error">Error al registrar el producto.</p>';
		}

	}	

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Registro Productos</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Registrar Productos</h1>
		</div>
		<div class="row">
			
			
			<div class="tarjeta">

				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-row">
						<div class="form-group col-md-7">
							<label class="col-form-label" for="producto">Producto/Servicio: </label>
							<input type="text" name="producto" id="producto" class="form-control" placeholder="Nombre del producto o servicio" />
						</div>

						<div class="form-group col-md-4">
							<label class="col-form-label" for="precio">Precio: </label>
							<input type="text" name="precio" class="form-control" id="precio" placeholder="Precio" />
						</div>

						<div class="form-group col-md-3">
							<label class="col-form-label" for="tipo_producto">Selecciona Tipo: </label>
							<?php 
							$query_tipo = mysqli_query($conection, "SELECT idtipo, producto_servicio FROM tipo_producto WHERE status = 1 ORDER BY producto_servicio ASC");
							$result_tipo = mysqli_num_rows($query_tipo);
			 //mysqli_close($conection);	

							?>

							<select class="form-control" name="idtipo" id="idtipo" onchange="habilitarcampos(this);">
								<!--<select name="idtipo" id="idtipo">-->	
									<?php 
									if ($result_tipo > 0) {
										while ($tipo = mysqli_fetch_array($query_tipo)) {
											?>
											<option value="<?php echo $tipo['idtipo']; ?>"><?php echo $tipo['producto_servicio']; ?></option>
											<?php 

										}
									}
									?>


								</select>
							</div>&nbsp;&nbsp;&nbsp;

							<div class="form-group col-md-5">
								<label class="col-form-label" for="nombre_proveedor">Proveedor: </label>
								<?php 
								$query_proveedor = mysqli_query($conection, "SELECT idpersona, razon_social FROM personas WHERE status = 1 AND idtipo = 2 ORDER BY razon_social ASC");
								$result_proveedor = mysqli_num_rows($query_proveedor);
								mysqli_close($conection);	

								?>

								<select name="proveedor" id="proveedor" class="form-control">
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
							</div>


							<div class="form-group col-md-3">
								<label class="col-form-label" for="cantidad">Cantidad: </label>
								<input type="number" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad" />
							</div>
							<div class="form-group col-md-12">
								<div class="photo">
									<div class="prevPhoto dropzone">
										<span class="delPhoto notBlock">X</span>
										<div class="text-center ">
											<div class="dz-message"><small class="text-info">Cargar imagen del producto</small></div>
										</div>
										<label class="col-form-label" for="foto"></label>
									</div>
									<div class="upimg">
										<input type="file" name="foto" id="foto">
									</div>
									<div id="form_alert"></div>
								</div>
							</div>
						</div>

						<div class="tile-footer">
							<center>
								<button class="btn btn-primary" type="submit" id="register"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
							</center>
						</div>
					</div>
				</form>

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
						var idtipo        			= $('#idtipo').val();
						var proveedor        		= $('#proveedor').val();
						var producto				= $('#producto').val();
						var imgProducto 			= $('#imgProducto').val();
						var precio 					= $('#precio').val();
						var cantidad 				= $('#cantidad').val();
						var usuario_id				= $('#usuario_id').val();

						e.preventDefault();	

						$.ajax({
							type: 'POST',
							url: 'registro_producto.php',
							data: {idtipo: idtipo, proveedor: proveedor,producto: producto,imgProducto: imgProducto,precio: precio, cantidad: cantidad, usuario_id: usuario_id},
							success: function(data){
								Swal.fire({
									icon: 'success',
									title: 'Guardando...',
									text: 'Datos registrados correctamente',
									showConfirmButton: true,
									/*'title': 'Successful',
									'text': data,
									'type': 'success'*/
								});

							},
							error: function(data){
								Swal.fire({
									title: 'Error',
									text: 'Error al guardar el registro.',
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
