<?php 
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Nueva Venta</title>

</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
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
	mysqli_close($conection);
}

?>
<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-th-list"></i> Nueva Venta</h1>
	</div>	

	<div class="row">

		<div class="post">	
			<div class="datos_cliente">
				<br>
				<div class="action_cliente">
					<h2 align="center"><?php echo $razonSocial;?></h2>

				</div>
				<form name="form_new_cliente_venta" id="form_new_cliente_venta" >
					<input type="hidden" name="action" value="addCliente" required>
					<input type="hidden" id="idpersona" name="idpersona" value="" required>
					<br>
					<hr>
					<div class="form-row">

						<table width="900px">
							<tbody>
								<tr>
									<td align="right">
										<label class="col-form-label">Cliente</label>
										<span> *</span>
									</td>
									<td>
										<input type="text" class="form-control" name="nom_cliente" id="nom_cliente" required>
									</td>
									<td>
										<!--<a href="registro_persona.php" class="nav-link"><i class="fa fa-plus"></i> Nuevo Cliente</a>-->
										<a href="#" class="nav-link add_cliente"><i class="fa fa-plus"></i> Nuevo Cliente</a>
									</td>
									<td align="right">
										<label class="col-form-label">Fecha</label>
									</td>
									<td>
										<input type="date" class="form-control" value="<?php echo date("d/m/y");?>" name="FechaIngreso" required>
									</td>
								</tr>
								<tr>
									<td align="right">
										<label class="col-form-label">N° de Identificación</label>
									</td>
									<td>
										<input class="form-control" type="text" name="nit_cliente" id="nit_cliente">
									</td>
									<td></td>
									<td align="right">
										<label class="col-form-label">Vendedor</label>
									</td>
									<td>
										<input type="" class="form-control" value="<?php echo $_SESSION['nombre']; ?>" readonly>
									</td>
								</tr>
								<tr>
									<td align="right">
										<label class="col-form-label">Teléfono</label>
									</td>
									<td>
										<input type="text" class="form-control" name="tel_cliente" id="tel_cliente" disabled required>
									</td>
								</tr>
							</tbody>
						</table>

					</div>	
				</form>
			</div>
			<br>
			<div class="containerTable">
				<table class="table table-hover tbl_venta">
					<thead>
						<tr style="background-color: #EFEEEC">
							<th width="100px"> Código</th>
							<th> Descripción</th>
							<th> Existencia</th>
							<th width="100px"> Cantidad</th>
							<th class="textright"> Precio Unitario</th>
							<th class="textright"> Precio Total</th>
							<th> Acción</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control" name="txt_cod_producto" id="txt_cod_producto"></td>
							<!--<td id="txt_descripcion"></td>-->
							<td><input type="text" class="form-control" name="txt_descripcion" id="txt_descripcion"></td>
							<td id="txt_existencia">-</td>
							<td><input type="text" class="form-control" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
							<td id="txt_precio" class="textright">0.00</td>
							<td id="txt_precio_total" class="textright">0.00</td>
							<td> <a href="#" id="add_product_venta" class="link_add"><i class="fa fa-plus"></i> Agregar</a></td>
						</tr>
						<tr style="background-color: #EFEEEC">
							<th>Código</th>
							<th colspan="2">Descripción</th>
							<th>Cantidad</th>
							<th class="textright">Precio Unitario</th>
							<th class="textright">Precio Total</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody id="detalle_venta">
						<!--contenido ajax-->
					</tbody>
					<tfoot id="detalle_totales">
						<!--contenido ajax-->
					</tfoot>
				</table>
			</div>
			<br>
			<center>
				<div class="datos_cliente">
					<div id="acciones_venta">
						<a href="#" class="btn btn-secondary" id="btn_anular_venta"><i class="fa fa-ban"></i> Cancelar</a>
						<a href="#" class="btn btn-primary" id="btn_facturar_venta" style="display: none;"><i class="fa fa-edit"></i> Guardar e Imprimir</a>
					</div>
				</div>
			</center>
		</div>
	</div>
</main>	

<!--Essential javascripts for application to work-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
	
<!-- Google analytics script-->

<script type="text/javascript" src="js/functions.js"></script>

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
	<script type="text/javascript">
		$(document).ready(function(){
			var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
			searchForDetalle(usuarioid);
		});
	</script>
	
	<script type="text/javascript">
		$(function() {
			$("#nom_cliente").autocomplete({
				source: "personas.php",
				minLength: 2,
				select: function(event, ui) {
					event.preventDefault();
					$('#nom_cliente').val(ui.item.nombre);
					$('#nit_cliente').val(ui.item.cedula);
					$('#tel_cliente').val(ui.item.telefono);
				/*$('#dir_cliente').val(ui.item.direccion);
				$('#cor_cliente').val(ui.item.correo);*/
				$('#idpersona').val(ui.item.idpersona);
			}
		});
		});
	</script>
	
	<script type="text/javascript">
		$(function() {
			$("#txt_descripcion").autocomplete({
				source: "productos.php",
				minLength: 2,
				select: function(event, ui) {
					event.preventDefault();
					$('#txt_descripcion').val(ui.item.descripcion);
					$('#txt_existencia').html(ui.item.existencia);
					$('#txt_precio').html(ui.item.precio);
					$('#txt_cod_producto').val(ui.item.codproducto);
					
				/*$('#cor_cliente').val(ui.item.correo);
				$('#idpersona').val(ui.item.idpersona);*/
				$('#txt_cant_producto').removeAttr('disabled');
			}
		});
		});
	</script>
</body>
</html>



