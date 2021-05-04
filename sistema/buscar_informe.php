<?php 
	session_start();
	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Reparaciones</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<?php 

			$busqueda = strtolower($_REQUEST['busqueda']);
			if(empty($busqueda))
			{
				header("location: lista_informe.php");
				mysqli_close($conection);
			}else


		 ?>
	<section id="container">
		
		<h1> <i class="fas fa-tools"></i> Informe de Reparaciones</h1>
				
		<form action="buscar_informe.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

	<div class="containerTable">
		<table>
			<tr>
				<th>Nº.</th>
				<th>F. Ingreso</th>
				<th>Cliente</th>
				<th>Equipo</th>
				<th >Problema</th>
				<th>Informe</th>
				<th>Precio</th>
				<th style="width: 7%">F. Entrega</th>
				<th style="width: 8%">Opciones</th>
			</tr>
		<?php 
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM ordenes WHERE (idorden LIKE '%$busqueda%') AND status = 1 ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 10;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT o.idorden,o.idcliente,DATE_FORMAT(o.fecha_ingreso, '%d/%m/%Y') as fecha,o.trabajo,o.informe,o.precio,o.fecha_entrega,cl.nombre as Cliente,e.equipo,o.problema,o.observaciones,t.nombre_tecnico	FROM  ordenes o INNER JOIN
                         cliente cl ON o.idcliente = cl.idcliente INNER JOIN
                         tecnicos t ON o.idtecnico = t.idtecnico INNER JOIN
                         equipos e ON o.idequipo = e.idequipo 
                         WHERE (o.idorden LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%' OR e.equipo LIKE '%$busqueda%') AND o.status = 1 ORDER BY o.idorden DESC LIMIT $desde,$por_pagina 
				");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr>
					<td><?php echo $data["idorden"]; ?></td>
					<td><?php echo $data["fecha"]; ?></td>
					<td><?php echo $data["Cliente"]; ?></td>
					<td><?php echo $data['equipo'] ?></td>
					<td><?php echo $data["problema"]; ?></td>
					<td><?php echo $data["informe"]; ?></td>
					<td><?php echo $data["precio"]; ?></td>
					<td><?php echo $data["fecha_entrega"]; ?></td>
					<td>
						
						<a class="btn_edit " title="Informe" href="informe.php?id=<?php echo $data["idorden"]; ?>"><i class="fas fa-edit"></i></a>
						
						<button class="btn_print view_informe" title="Imprimir" type="button" cli="<?php echo $data["idcliente"]; ?>" o="<?php echo $data['idorden']; ?>"><i class="fas fa-print"></i></button>
			
						
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