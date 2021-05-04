<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orden Reparación</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="img/logo.png">
				</div>
			</td>
			<td class="info_empresa">
				<div>
					<div>
					<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					<p class="enc_empresa"><?php echo $configuracion['razon_social']; ?></p>
					<p class="enc_empresa"><?php echo $configuracion['direccion']; ?></p>
					<p class="enc_empresa">RUC: <?php echo $configuracion['cedula']; ?></p>
					<p class="enc_empresa">Teléfono: <?php echo $configuracion['telefono']; ?></p>
					<p class="enc_empresa">Email: <?php echo $configuracion['email']; ?></p>
				</div>
				</div>
			</td>
			<td class="info_factura">
				<div class="round_cuadro">
					<span class="titulo">Orden de Reparación</span>
					<p class="encabezado">No. Orden: <strong style="color: #D70505; font-size: 11pt"><?php echo str_pad($orden['idorden'],6,"0",STR_PAD_LEFT); ?></strong></p>
					<p class="encabezado">Fecha: <?php echo $orden['fecha']; ?></p>
					<p class="encabezado">Hora: <?php echo $orden['hora']; ?></p>
					<p class="encabezado">Recibido por: <?php echo $orden['vendedor']; ?></p>
				</div>
			</td>
		</tr>
	</table>
	<table id="orden_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">DATOS DEL CLIENTE</span>
					<table class="datos_cliente">
						<tr>
							<td><label class="label_encabezados">Cedula/RUC:</label><p><?php echo $orden['cedula']; ?></p></td>
							<td><label class="label_encabezados">Teléfono:</label> <p><?php echo $orden['telefono']; ?></p></td>
						</tr>
						<tr>
							<td><label class="label_encabezados">Nombre:</label> <p><?php echo $orden['nombre']; ?></p></td>
							<td><label class="label_encabezados">Dirección:</label> <p><?php echo $orden['direccion']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>

	<table id="orden_equipo">
		<tr>
			<td class="info_equipo">
				<div class="round">
					<span class="h3">DATOS DEL EQUIPO</span>
					<table class="datos_equipo">
						<tr>
							<td><label class="label_encabezados">Equipo:</label><p><?php echo $orden['equipo']; ?></p></td>
							<td><label class="label_encabezados">Marca/Modelo:</label> <p><?php echo $orden['marca']; ?></p></td>
							<td><label class="label_encabezados">Serie:</label> <p><?php echo $orden['serie']; ?></p></td>
							
						</tr>
						<tr>
							<td><label class="label_encabezados">Case:</label> <p><?php echo $orden['ncase']; ?></p></td>
							<td><label class="label_encabezados">Fuente:</label> <p><?php echo $orden['fuente']; ?></p></td>
							<td><label class="label_encabezados">Mainboard:</label> <p><?php echo $orden['mainboard']; ?></p></td>
						</tr>
						<tr>
							<td><label class="label_encabezados">Procesador:</label> <p><?php echo $orden['procesador']; ?></p></td>
							<td><label class="label_encabezados">Memoria:</label> <p><?php echo $orden['memoria']; ?></p></td>
							<td><label class="label_encabezados">Disco Duro:</label> <p><?php echo $orden['discoduro']; ?></p></td>
						</tr>
						<tr>
							<td><label class="label_encabezados">Unidad:</label> <p><?php echo $orden['unidad_disco']; ?></p></td>
							<td><label class="label_encabezados">Tarjetas:</label><p><?php echo $orden['tarjetas']; ?></p></td>
							
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>

<table class="datos_equipo2">
	<tr>
		<td><label class="label_encabezados">Accesorios:</label><p><?php echo $orden['accesorios']; ?></p></td>
		<td><label class="label_encabezados">Observaciones:</label><p><?php echo $orden['observaciones']; ?></p></td>
	</tr>
	<tr>
		<td><label class="label_encabezados">Inconveniente Reportado:</label><p><?php echo $orden['problema']; ?></p></td>
		<td><label class="label_encabezados">Trabajo a Realizar:</label><p><?php echo $orden['trabajo']; ?></p></td>
	</tr>
</table><br>

<table id="orden_informe">
		<tr>
			<td class="info_informe">
				<div class="round_informe">
					<span class="titulo2">INFORME TÉCNICO</span>
					<table class="datos_cliente">
						<tr>
							<td><label class="label_encabezados">Informe:</label><p><?php echo $orden['informe']; ?></p></td>
							
						</tr>
						<tr>
							<td><label class="label_encabezados">Técnico:</label> <p><?php echo $orden['nombre_tecnico']; ?></p></td>
							
						</tr>
						<tr>
							<td><label class="label_encabezados">Costo $.:</label> <p><?php echo $orden['precio']; ?></p></td>
							
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>


	
	

</div>

</body>
</html>