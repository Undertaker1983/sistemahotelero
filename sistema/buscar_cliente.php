<?php 
	session_start();
	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<?php 

			$busqueda = strtolower($_REQUEST['busqueda']);
			if(empty($busqueda))
			{
				header("location: lista_clientes.php");
				mysqli_close($conection);
			}


		 ?>
		
		<h1>Clientes Registrados</h1>
		<a href="registro_usuario.php" class="btn_new"><i class="fas fa-user-plus"></i> Nuevo Cliente</a>
		
		<form action="buscar_cliente.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

	<div class="containerTable">
		<table>
			<tr>
				<th>Nombre</th>
				<th>Cedula/Ruc</th>
				<th>Telefono</th>
				<th>Dirección</th>
				<th>Opciones</th>
			</tr>
		<?php 
			//Paginador
			
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM personas 
																WHERE ( cedula LIKE '%$busqueda%' OR 
																		nombre LIKE '%$busqueda%' OR 
																		telefono LIKE '%$busqueda%' OR 
																		direccion LIKE '%$busqueda%' ) 
																AND status = 1  ");

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

			$query = mysqli_query($conection,"SELECT * FROM personas WHERE (cedula LIKE '%$busqueda%' OR 
											nombre LIKE '%$busqueda%' OR 
											telefono LIKE '%$busqueda%'OR 
											direccion LIKE '%$busqueda%') 
										AND
										status = 1 ORDER BY idpersona ASC LIMIT $desde,$por_pagina 
				");
			mysqli_close($conection);
			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr class="row<?php echo $data["idpersona"]; ?>">
					<td><?php echo $data["nombre"]; ?></td>
					<td><?php echo $data["cedula"]; ?></td>
					<td><?php echo $data["telefono"]; ?></td>
					<td><?php echo $data['direccion'] ?></td>
					
					<td class="textcenter">
						<a class="btn_viewpersonas view_orden" title="Ver" type="button" cl="<?php echo $data["codcliente"]; ?>"><i class="fas fa-eye"></i></a>
						<a class="btn_editpersonas"  title="Editar" href="editar_cliente.php?id=<?php echo $data['idpersona']; ?>"><i class="fas fa-edit"></i></a>
						<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>
						<a class="link_delete2 del_persona" title="Eliminar" persona="<?php echo $data["idpersona"]; ?>" href="#"><i class="fas fa-trash-alt"></i></a>
					<?php } ?>
						
					</td>
				</tr>
			
		<?php 
				}

			}
		 ?>


		</table>
	</div>		
<?php 
	
	if($total_registro != 0)
	{
 ?>
		<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-backward"></i></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-backward fa-lg"></i></a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-forward fa-lg"></i></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?> "><i class="fas fa-fast-forward"></i></a></li>
			<?php } ?>
			</ul>
		</div>
<?php } ?>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>