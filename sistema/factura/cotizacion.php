<?php
	$subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="img/logo.png">
				</div>
			</td>
			<td class="info_empresa">
				<?php
					if($result_config > 0){
						$iva = $configuracion['iva'];
				 ?>
				<div>
					<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					<p><?php echo $configuracion['razon_social']; ?></p>
					<p><?php echo $configuracion['direccion']; ?></p>
					<p>RUC: <?php echo $configuracion['cedula']; ?></p>
					<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
					<p>Email: <?php echo $configuracion['email']; ?></p>
				</div>
				<?php
					}
				 ?>
			</td>
			<td class="info_factura">
				<div class="round_cotizacion">
					<span class="titulo_cotizacion">COTIZACIÓN</span>
					<p class="encabezado_cotizacion">No. <strong><?php echo str_pad($factura['nocotizacion'],5,"0",STR_PAD_LEFT); ?></strong></p>
					<!--<p class="encabezado">Fecha: <?php echo $factura['fecha']; ?></p>
					<p class="encabezado">Hora: <?php echo $factura['hora']; ?></p>
					<p class="encabezado">Vendedor: <?php echo $factura['vendedor']; ?></p>-->
				</div>
			</td>
		</tr>
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round_cotizacion">
					<!--<span class="titulo">Cliente</span>-->
					<table class="datos_cliente_cotizacion">
						<tr>
							<td><label class="referencias">CEDULA/RUC: </label>   <p><?php echo $factura['cedula']; ?></p></td>
							<!--<td><label class="referencias">CEDULA/RUC:</label>
							<td ><p><?php echo $factura['cedula']; ?></p></td>-->
							<td><p class="referencias">FECHA DE EMISIÓN: <p><?php echo $factura['fecha']; ?></p></p></td>	
							
							
						</tr>	
						<tr>	
							<td><label class="referencias">NOMBRE:     </label> <p><?php echo $factura['nombre']; ?></p></td>
						</tr>
						<tr>	
							<td><label class="referencias">DIRECCIÓN:  </label> <p><?php echo $factura['direccion']; ?></p></td>
						</tr>
						<tr>	
							<td><label class="referencias">TELÉFONO:   </label> <p><?php echo $factura['telefono']; ?></p></td>
						</tr>
										
					</table>
				</div>
			</td>

		</tr>
	</table>

	<table id="detalle_cotizacion">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Descripción</th>
					<th class="textright" width="150px">Precio Unitario</th>
					<th class="textright" width="150px">Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

			<?php

				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
			 ?>
				<tr>
					<td class="textcenter"><?php echo $row['cantidad']; ?></td>
					<td><?php echo $row['descripcion']; ?></td>
					<td class="textright"><?php echo $row['precio_venta']; ?></td>
					<td class="textright"><?php echo $row['precio_total']; ?></td>
				</tr>
			<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto,2 );
				$total 		= round($tl_sniva + $impuesto,2);
				//formato para monedas
				$imoney		= number_format($impuesto, 2, '.', ',');
				$submoney	= number_format($tl_sniva, 2, '.', ',');
				$tmoney		= number_format($total, 2, '.', ',');

			?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL</span></td>
					<td class="textright"><span><?php echo $submoney; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo $imoney; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL A PAGAR</span></td>
					<td class="textright"><span><?php echo $tmoney; ?></span></td>
				</tr>
		</tfoot>
	</table>
	<br>
	<div>
		
		<p>________________________</p>
		<p class="encabezado">     Firma Autorizada</p>
		<!--<h4 class="label_gracias">¡Gracias por su visita!</h4>-->
	</div>

</div>

</body>
</html>