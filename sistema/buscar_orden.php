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
		$busqueda= '';
		//$search_cliente= '';
		if (empty($_REQUEST['busqueda'])) {
			header("location: lista_orden.php");
		}
		if (!empty($_REQUEST['busqueda'])) {
			$busqueda = strtolower($_REQUEST['busqueda']);
			$where = "(o.idorden LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%' OR e.equipo LIKE '%$busqueda%') AND o.status = 1";
			$buscar = 'busqueda='.$busqueda;
		}
		
	 ?>	

		<h1> <i class="fas fa-tools"></i> Ordenes Registradas</h1>
		<a href="registro_orden.php" class="btn_new btnNewOrden"><i class="fas fa-plus"></i> Nueva Orden</a>
		
		<form action="buscar_orden.php" method="get" class="form_search">
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
				<th>Trabajo</th>
				<th style="width: 9%">Estado</th>
				<th style="width: 13%">Tecnico</th>
				<th style="width: 10%">Opciones</th>
			</tr>
		<?php 
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM ordenes o 
															INNER JOIN personas cl ON o.idpersona = cl.idpersona
                                                            INNER JOIN tecnicos t ON o.idtecnico = t.idtecnico
                                                            INNER JOIN equipos e ON o.idequipo = e.idequipo 
                                                            WHERE $where ");
			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 15;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT o.idorden,o.idpersona,DATE_FORMAT(o.fecha_ingreso, '%d/%m/%Y') as fecha,o.trabajo,o.estado,cl.nombre as Cliente,o.problema,o.observaciones,t.nombre_tecnico,e.equipo
						FROM  ordenes o INNER JOIN
                         personas cl ON o.idpersona = cl.idpersona INNER JOIN
                         tecnicos t ON o.idtecnico = t.idtecnico INNER JOIN
                         equipos e ON o.idequipo = e.idequipo 
                         WHERE $where ORDER BY o.idorden DESC LIMIT $desde,$por_pagina 
				");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					if ($data["estado"] == 'ENTREGADO') {
						$estado = '<span class="entregado">ENTREGADO</span>';
					}
					if ($data["estado"] == 'LISTO') {
						$estado = '<span class="listo">LISTO</span>';
					}
					
					if($data["estado"] == 'PENDIENTE'){
						$estado = '<span class="anulada">PENDIENTE</span>';
					}

					if($data["estado"] == 'EN REPARACIÓN'){
						$estado = '<span class="reparacion">REPARANDO</span>';
					}	
			?>
				<tr class="row<?php echo $data["idorden"]; ?>">
					<td><span class="n_orden"><?php echo $data["idorden"]; ?></span></td>
					<td><?php echo $data["fecha"]; ?></td>
					<td><?php echo $data["Cliente"]; ?></td>
					<td class="textcenter"><?php echo $data['equipo'] ?></td>
					<td><?php echo $data["problema"]; ?></td>
					<td><?php echo $data["trabajo"]; ?></td>
					<!--<td><?php echo $data["estado"]; ?></td>-->
					<td class="estado textcenter"><?php echo $estado; ?></td>
					<td class="textcenter"><?php echo $data["nombre_tecnico"]; ?></td>
					<td class="textcenter">
						<a class="btn_print view_orden" title="Imprimir" type="button" cli="<?php echo $data["idpersona"]; ?>" o="<?php echo $data['idorden']; ?>"><i class="fas fa-print"></i></a>
						<a class="btn_edit " href="editar_orden.php?id=<?php echo $data["idorden"]; ?>"><i class="fas fa-edit"></i></a>
						<a class="btn_informe" title="Informe" href="informe.php?id=<?php echo $data["idorden"]; ?>"><i class="fas fa-clipboard"></i></a>
						<a class="btn_eliminar link_delete" href="eliminar_confirmar_orden.php?id=<?php echo $data["idorden"]; ?>"><i class="fas fa-trash-alt"></i></a>
					
						
					</td>
				</tr>
			
		<?php 
				}

			}
		 ?>


		</table>
		</div>
        <?php
        if ($total_paginas != 0) {
			# code...
		

		 ?>
        
		<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?> & <?php echo $buscar; ?>"><i class="fas fa-step-backward"></i></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?> & <?php echo $buscar; ?>"><i class="fas fa-backward fa-lg"></i></a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?> & <?php echo $buscar; ?>"><i class="fas fa-forward fa-lg"></i></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?> & <?php echo $buscar; ?>"><i class="fas fa-fast-forward"></i></a></li>
			<?php } ?>
			</ul>
		</div>
        <?php 
			} 
		?>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>