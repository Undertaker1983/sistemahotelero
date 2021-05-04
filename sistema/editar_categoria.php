<?php 
session_start();

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['id']) || empty($_POST['nombre_categoria']))
	{
		$alert='<p class="msg_error">El campo nombre es obligatorio.</p>';
	}else{
		$idcategoria		= $_POST['id'];
		$nombre_categoria	= $_POST['nombre_categoria'];
		

		$query_update = mysqli_query($conection,"UPDATE categorias SET (idcategoria = $idcategoria, nombre_categoria = '$nombre_categoria' WHERE idcategoria = $idcategoria)");

		if($query_update){ 
			$alert='<p class="msg_save">Actualizado correctamente.</p>';
		}else{
			$alert='<p class="msg_error">Error al actualizar categoria.</p>';
		}
	}
}			

//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: lista_categorias.php");
}else{
	$id_categoria = $_REQUEST['id'];
	if (!is_numeric($id_categoria)) {
		header("location: lista_categorias.php");
	}

	$query_categoria = mysqli_query($conection,"SELECT idcategoria,nombre_categoria 
		FROM categorias
		WHERE idcategoria = $id_categoria AND estado = 1");

	$result_categoria = mysqli_num_rows($query_categoria);
			//mysqli_close($conection);

	if ($result_categoria > 0 ) {
		$data_categoria = mysqli_fetch_array($query_categoria);
				//print_r($data_orden);
	}
	else{
		header("location: lista_categorias.php");
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Categoria</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Actualizar Categoria</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="tile">
					<div class="row">
						<div class="col-lg-6">	


							<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>	
							<form action="" method="post">
								<input type="hidden" name="id" value="<?php echo $data_categoria['idcategoria']; ?>"/>	
								<div class="form-group">
									<label for="nombre" class="col-form-label">Nombre</label>
									<input type="text" class="form-control" name="nombre_categoria" placeholder="Nombre Categoria" value="<?php echo $data_categoria['nombre_categoria']; ?>">
								</div>
								
								<button type="submit" class="btn btn-primary btn_save">Guardar Modificación</button>
							</form>


						</div>
					</div>
				</div>
			</div>

		</main>
		<?php 
		include "includes/footer_admin.php";
		?>	