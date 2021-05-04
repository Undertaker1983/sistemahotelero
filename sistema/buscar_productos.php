<?php 
	session_start();
	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Productos</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
	<?php 
		$busqueda= '';
		$search_proveedor= '';
		if (empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])) {
			header("location: lista_productos.php");
		}
		if (!empty($_REQUEST['busqueda'])) {
			$busqueda = strtolower($_REQUEST['busqueda']);
			$where = "(p.codproducto LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%') AND p.status = 1";
			$buscar = 'busqueda='.$busqueda;
		}
		if (!empty($_REQUEST['proveedor'])) {
			$search_proveedor = $_REQUEST['proveedor'];
			$where = "p.proveedor LIKE $search_proveedor AND p.status = 1";
			$buscar = 'proveedor='.$search_proveedor;
		}
	 ?>	
		
		<h1> <i class="fas fa-cube"></i> Productos Registrados</h1>
		<a href="registro_producto.php" class="btn_new btnNewProducto"><i class="fas fa-plus"></i> Nuevo producto</a>
		
		<form action="buscar_productos.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

	<div class="containerTable">	
		<table>
			<tr>
				<th>Id. Producto</th>
				<th>Tipo</th>
				<th>Descripción</th>
				<th>Precio</th>
				<th>Existencia</th>
				<th style="width: 12%">
				<?php 
			 	$pro = 0;
			 	if (!empty($_REQUEST['proveedor'])) {
					$pro = $_REQUEST['proveedor'];
				
			 	}
			 	$query_proveedor = mysqli_query($conection, "SELECT * FROM personas WHERE status = 1 AND idtipo = 2 ORDER BY razon_social ASC");
			 	$result_proveedor = mysqli_num_rows($query_proveedor);
			 	?>
			 	<div class="box">
				<select name="proveedor" id="search_proveedor">
				<option value="" selected>PROVEEDORES</option>
				<?php 
				if ($result_proveedor > 0) {
					while ($proveedor = mysqli_fetch_array($query_proveedor)) {
						if ($pro == $proveedor["idpersona"]) 
						{	# code...
						
			 	?>
			 		<option value="<?php echo $proveedor["idpersona"]; ?>" selected><?php echo $proveedor["razon_social"]; ?></option>
			 	<?php 
			 	}else{
			 ?>
			
			 	<option value="<?php echo $proveedor["idpersona"]; ?>"><?php echo $proveedor["razon_social"]; ?></option>
			 <?php 
			  	
			 		}
			  	}
				}
			 	?>
				</select>
				</div>
				</th>
				<th style="width: 13%">Foto</th>
				<th>Opciones</th>
			</tr>
		<?php 
			//Paginador
			$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM producto AS p
										WHERE $where ");
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

			$query = mysqli_query($conection,"SELECT p.codproducto, p.descripcion,p.precio,p.existencia,pr.nombre,p.foto,pr.razon_social,tp.producto_servicio
							   		    FROM producto p 
										INNER JOIN personas pr ON p.proveedor=pr.idpersona  
										INNER JOIN tipo_producto tp
										ON p.idtipo = tp.idtipo  
										WHERE 
										$where ORDER BY p.codproducto DESC LIMIT $desde,$por_pagina 
				");

			mysqli_close($conection);

			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					if ($data['foto']!= 'img_producto.png') {
						$foto = 'img/uploads/'.$data['foto'];

					}else{
						$foto = 'img/'.$data['foto'];	
						
						}

			?>
				<tr class="row<?php echo $data["codproducto"]; ?>">
					<td class="textleft"><?php echo $data["codproducto"]; ?></td>
					<td class="textleft"><?php echo $data["producto_servicio"]; ?></td>
					<td><?php echo $data["descripcion"]; ?></td>
					<td class="celPrecio textright"><?php echo $data["precio"]; ?></td>
					<td class="celExistencia textright"><?php echo $data["existencia"]; ?></td>
					<td class="textcenter"><?php echo $data['razon_social'] ?></td>
					<td class="img_producto textcenter"><img src="<?php echo $foto; ?>" alt="<?php echo $data["descripcion"]; ?>"></td>
					
					<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol']==2) { ?>

					<td class="textcenter">
						<a class="link_add add_product" title="Agregar" product="<?php echo $data["codproducto"]; ?>" href="#"><i class="fas fa-plus"></i> </a>
						
						<a class="btn_edit" title="Editar" href="editar_producto.php?id=<?php echo $data["codproducto"]; ?>"><i class="fas fa-edit"></i> </a>
						
						
						<a class="btn_eliminar del_product" title="Eliminar" href="#" product="<?php echo $data["codproducto"]; ?>"><i class="fas fa-trash-alt"></i> </a>
					
						
					</td>

					<?php } ?>
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