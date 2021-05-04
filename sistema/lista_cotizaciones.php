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
	<?php include "includes/scripts.php"; ?>
	<title>Cotizaciones</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<h1> <i class="fas fa-newspaper"></i> Cotizaciones Registradas</h1>
		<a href="cotizaciones.php" class="btn_new btnNewVenta"><i class="fas fa-plus"></i> Nueva Cotizacion </a>
		
		<form action="buscar_cotizacion.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="N° Cotización">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<div>
			<h5>Buscar por Fecha</h5>
			<form action="buscar_venta.php" method="get" class="form_search_date">
				<label>De:</label>
				<input type="date" name="fecha_de" id="fecha_de" required>
				<label>A</label>
				<input type="date" name="fecha_a" id="fecha_a" required>
				<button type="submit" class="btn_new"><i class="fas fa-search"></i></button>
			</form>
		</div>

	<div class="containerTable">	
		<table>
			<tr>
				<th style="width: 9%" class="texcenter">N°.</th>
				<th style="width: 9%" class="texcenter">Fecha/Hora</th>
				<th class="texcenter">Cliente</th>
				<th style="width: 9%" class="texcenter">Vendedor</th>
				
				<th style="width: 9%" class="texcenter">Total Cotización</th>
				<th class="textcenter">Opciones</th>
			</tr>
		<?php 
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM cotizacion WHERE status != 10 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 20;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT c.nocotizacion,c.fecha,c.totalcotizacion,c.codpersona,c.status,u.nombre as vendedor,
				 p.nombre as cliente
				 FROM cotizacion c 
				 INNER JOIN usuario u 
				 ON c.usuario = u.idusuario
				 INNER JOIN personas p
				 ON c.codpersona = p.idpersona
				 WHERE c.status != 10 
				 ORDER BY c.fecha DESC LIMIT $desde,$por_pagina");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr id="row_<?php echo $data["nofactura"]; ?>">

					<td><span class="n_ventas"><?php echo $data["nocotizacion"]; ?></span></td>
					<td><?php echo $data["fecha"]; ?></td>
					<td><?php echo $data["cliente"]; ?></td>
					<td><?php echo $data['vendedor'] ?></td>
					
					<td class="textright totalfactura"><span>$.</span><?php echo $data["totalcotizacion"]; ?></td>
					
					<td>
						<div class="div_acciones">
							<div>
								<button class="btn_view view_cotizacion" type="button" cl="<?php echo $data["codpersona"]; ?>" f="<?php echo $data['nocotizacion']; ?>"><i class="fas fa-eye"></i></button>
							</div>
						
						
						
						</div>
					</td>
				</tr>
			
		<?php 
				}

			}
		 ?>


		</table>
		</div>
		<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-backward fa-lg"></i></a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-forward fa-lg"></i></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?> "><i class="fas fa-fast-forward"></i></a></li>
			<?php } ?>
			</ul>
		</div>


	</section>
	<?php include "includes/footer.php"; ?>
	
</body>
</html>