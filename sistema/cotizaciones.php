<?php 
session_start();
include "../conexion.php";
 ?>
<html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Cotizaciones</title>

</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
				<div class="title_page">
					<h1><i class="fas fa-shopping-cart"></i> Nueva Cotización</h1>
				</div>
		<div class="datos_cliente">
			<div class="action_cliente">
				<h4>Datos del Cliente</h4>
				<a href="#" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>
			</div>
			<form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos_cotizacion">
				<input type="hidden" name="action" value="addCliente" required>
				<input type="hidden" id="idpersona" name="idpersona" value="" required>
				<div class="wd30">
					<label>N° de Identificación:</label>
					<input type="text" name="nit_cliente" id="nit_cliente">
				</div>
				<div class="wd30">
					<label>Nombre:</label>
					<input type="text" name="nom_cliente" id="nom_cliente"  required>
				</div>
				<div class="wd30">
					<label>Teléfono:</label>
					<input type="text" name="tel_cliente" id="tel_cliente" disabled required>
				</div>		
				<div class="wd60">
					<label>Dirección:</label>
					<input type="text" name="dir_cliente" id="dir_cliente" disabled >
				</div>
				<div class="wd30">
					<label>Correo:</label>
					<input type="text" name="cor_cliente" id="cor_cliente" disabled >
				</div>
				<div id="div_registro_cliente" class="wd100">
					<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
				</div>	
			</form>
		</div>
		
		<div class="datos_venta">
					<h4>Datos de Cotización</h4>
					<div class="datos_cotizacion">
				<div class="wd50">
					<label>Vendedor</label>
					<p><?php echo $_SESSION['nombre']; ?></p>
				</div>
				
				<div class="wd50">
					<label>Acciones</label>
					<div id="acciones_venta">
						<a href="#" class="btn_ok textcenter" id="btn_anular_cotizacion"><i class="fas fa-ban"></i> Anular</a>
						<a href="#" class="btn_new textcenter" id="btn_procesar_cotizacion" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
					</div>
				</div>
					</div>
		</div>
	
	<div class="containerTable">	
		<table class="tbl_venta">
			<thead>
				<tr>
					<th width="100px"> Código</th>
					<th> Descripción</th>
					<th> Existencia</th>
					<th width="100px"> Cantidad</th>
					<th class="textright"> Precio</th>
					<th class="textright"> Precio Total</th>
					<th> Acción</th>
				</tr>
				<tr>
					<td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
					<td id="txt_descripcion">-</td>
					<td id="txt_existencia">-</td>
					<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
					<td id="txt_precio" class="textright">0.00</td>
					<td id="txt_precio_total" class="textright">0.00</td>
					<td> <a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
				</tr>
				<tr>
					<th>Código</th>
					<th colspan="2">Descripción</th>
					<th>Cantidad</th>
					<th class="textright">Precio</th>
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
	</section>

<?php include "includes/footer.php"; ?>
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
					$('#dir_cliente').val(ui.item.direccion);
					$('#cor_cliente').val(ui.item.correo);
					$('#idpersona').val(ui.item.idpersona);
			     }
            });
		});
</script>	
</body>
</html>

