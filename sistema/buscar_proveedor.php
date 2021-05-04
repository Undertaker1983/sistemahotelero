<?php 
	session_start();
	if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
		header("location: /");
	}

	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Proveedores</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
	<?php 

			$busqueda = strtolower($_REQUEST['busqueda']);
			if(empty($busqueda))
			{
				header("location: lista_usuarios.php");
				mysqli_close($conection);
			}


		 ?>

		<h1> <i class="fas fa-building"></i> Proveedores Registrados</h1>
		<a href="registro_proveedor.php" class="btn_new"><i class="fas fa-building"></i> Nuevo Proveedor</a>
		
		<form action="buscar_proveedor.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

	<div class="containerTable">	
		<table>
			<tr>
				<th>Proveedor</th>
				<th>Ruc</th>
				<th>Dirección</th>
				<th>Telefono</th>
				<th>Email</th>
				<th>Representante</th>
				<th>Opciones</th>
			</tr>
		<?php 
			//Paginador

		$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM proveedor 
																WHERE ( ruc LIKE '%$busqueda%' OR 
																		nombre_proveedor LIKE '%$busqueda%') 
																AND status = 1  ");

			$result_register = mysqli_fetch_array($sql_registe);
			$total_registro = $result_register['total_registro'];

			$por_pagina = 50;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conection,"SELECT * FROM proveedor WHERE (ruc LIKE '%$busqueda%' OR 
											nombre_proveedor LIKE '%$busqueda%') 
										AND	status = 1 ORDER BY idproveedor ASC LIMIT $desde,$por_pagina");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);

			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr class="row<?php echo $data["idproveedor"]; ?>">
					<td><?php echo $data["nombre_proveedor"]; ?></td>
					<td><?php echo $data["ruc"]; ?></td>
					<td><?php echo $data["direccion"]; ?></td>
					<td><?php echo $data['telefono'] ?></td>
					<td><?php echo $data["email"]; ?></td>
					<td><?php echo $data["nombre_representante"]; ?></td>

					
					<td class="textcenter">
						<a class="link_edit" href="editar_proveedor.php?id=<?php echo $data["idproveedor"]; ?>"><i class="fas fa-edit"></i> Editar</a>
						
						
						<a class="link_delete2 del_proveedor" proveedor="<?php echo $data["idproveedor"]; ?>" href="#"><i class="fas fa-trash-alt"></i> Eliminar</a>
					
						
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