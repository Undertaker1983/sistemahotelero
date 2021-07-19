<?php 
session_start();
if ($_SESSION['rol']!= 1 and $_SESSION['rol']!= 2) {
	header("location: ./");
}

include "../conexion.php";

	//validar producto
if (empty($_REQUEST['id'])) {
	header("location: lista_productos.php");
}else{
	$id_producto = $_REQUEST['id'];
	if (!is_numeric($id_producto)) {
		header("location: lista_productos.php");
	}

	$query_producto = mysqli_query($conection,"SELECT p.idtipo,p.codproducto, p.descripcion,p.precio,p.foto,pr.idpersona,pr.nombre,pr.razon_social
		FROM producto p 
		INNER JOIN personas pr ON p.proveedor = pr.idpersona

		WHERE p.codproducto = $id_producto AND p.status=1");
	$result_producto = mysqli_num_rows($query_producto);

	$foto = '';
	$classRemove = 'notBlock';


	if ($result_producto > 0 ) {
		$data_producto = mysqli_fetch_array($query_producto);
		if ($data_producto['foto'] != 'img_producto.png') {
			$classRemove = '';
			$foto = '<img id="img" src="img/uploads/'.$data_producto['foto'].'" alt="Producto">';
		}

	}else{
		header("location: lista_productos.php");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Productos</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Actualizar Producto</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-lg-6">
					<div class="tile">

						<form action="" method="post" enctype="multipart/form-data">
							<div class="form-row">
								<input type="hidden" id="id" name="id" value="<?php echo $data_producto['codproducto']; ?>">
								<input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
								<input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">
								<div class="form-group col-md-5">	
									<label class="col-form-label" for="nombre_proveedor">Proveedor: </label>

									<?php 
									$query_proveedor = mysqli_query($conection, "SELECT p.idtipo,p.idpersona,p.razon_social,tp.idtipo FROM personas p 
										INNER JOIN tipo_persona tp ON p.idtipo = tp.idtipo
										WHERE p.status = 1 AND p.idtipo = 2 ORDER BY p.razon_social ASC");
									$result_proveedor = mysqli_num_rows($query_proveedor);
									mysqli_close($conection);	

									?>

									<select name="proveedor" id="proveedor" class="form-control notItemOne">
										<option value="<?php echo $data_producto['idpersona']; ?>" selected><?php echo $data_producto['razon_social']; ?></option>
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

								<div class="form-group col-md-5">
									<label class="col-form-label" for="producto">Producto: </label>
									<input type="text" name="producto" id="producto" class="form-control" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']; ?>" />
								</div>

								<div class="form-group col-md-2">
									<label class="col-form-label" for="precio">Precio: </label>
									<input type="text" name="precio" id="precio" class="form-control" placeholder="Precio del producto" value="<?php echo $data_producto['precio']; ?>"/>
								</div>

								<div class="form-group col-md-12">
								<div class="photo">
									<center>
									<label class="col-form-label" for="foto">Imagen</label>
									<div class="prevPhoto">
										<span class="delPhoto <?php echo $classRemove ?>">X</span>
										<label class="col-form-label" for="foto"></label>
										<?php echo $foto; ?>

									</div>
									<div class="upimg">
										<input class="form-control" type="file" name="foto" id="foto">
									</div>
									<div id="form_alert"></div>
									</center>
								</div>
								</div>
							</div>

							<div class="tile-footer">
								<center>
									<button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i> Actualizar Producto</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
								</center>
							</div>
						</form>
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
	
</body>
</html>
<?php 

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']))
	{
		?>
		
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'Los campos son obligatorios',
									type: 'error',
								});
		</script>
		
		<?php 
	}else{

		$codproducto	= $_POST['id'];
		$proveedor 		= $_POST['proveedor'];
		$producto 		= $_POST['producto'];
		$precio   		= $_POST['precio'];
		$imgProducto  	= $_POST['foto_actual'];
		$imgRemove    	= $_POST['foto_remove'];

		$foto           = $_FILES['foto'];
		$nombre_foto	=$foto['name'];
		$type			=$foto['type'];
		$url_temp		=$foto['tmp_name'];
		$upd			='';


		if ($nombre_foto !='') {
			$destino       = 'img/uploads/';
			$img_nombre	   = 'img_'.md5(date('d-m-Y H:m:s'));
			$imgProducto   = $img_nombre.'.jpg';
			$src           = $destino.$imgProducto;
		}else{
			if ($_POST['foto_actual'] != $_POST['foto_remove']) {
				$imgProducto = 'img_producto.png';
			}
		}


		$query_update = mysqli_query($conection,"UPDATE producto SET descripcion = '$producto', proveedor = $proveedor, precio = $precio, foto = '$imgProducto' WHERE codproducto = $codproducto");

		if($query_update){
			if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove']))  {
				unlink('img/uploads/'.$_POST['foto_actual']);
			}
			if ($nombre_foto!='' ) {
				move_uploaded_file($url_temp,$src);
			}
			?>
			
			<script type="text/javascript">
				Swal.fire({
									icon: 'success',
									title: 'Guardando...',
									text: 'Datos actualizados correctamente',
									showConfirmButton: true,
									
								});
			</script>
			
			<?php 
			
		}else{
			?>
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'Error al actualizar producto',
									type: 'error',
								});
		</script>
		
		<?php 
		}

	}	

}
?>