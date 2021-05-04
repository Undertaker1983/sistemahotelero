<?php 
session_start();

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['id']) || empty($_POST['nombre_piso']))
	{
		$alert='<p class="msg_error">El campo nombre es obligatorio.</p>';
	}else{
		$idpiso		= $_POST['id'];
		$nombre_piso	= $_POST['nombre_piso'];
		

		$query_update = mysqli_query($conection,"UPDATE categorias SET (idpiso = $idpiso, nombre_piso = '$nombre_piso' WHERE idpiso = $idpiso)");

		if($query_update){ 
			$alert='<p class="msg_save">Actualizado correctamente.</p>';
		}else{
			$alert='<p class="msg_error">Error al actualizar categoria.</p>';
		}
	}
}			

//Mostrar Datos
if (empty($_REQUEST['id'])) {
	header("location: lista_pisos.php");
}else{
	$id_piso = $_REQUEST['id'];
	if (!is_numeric($id_piso)) {
		header("location: lista_pisos.php");
	}

	$query_piso = mysqli_query($conection,"SELECT idpiso,nombre_piso 
		FROM pisos
		WHERE idpiso = $id_piso AND estado = 1");

	$result_piso = mysqli_num_rows($query_piso);
			//mysqli_close($conection);

	if ($result_piso > 0 ) {
		$data_piso = mysqli_fetch_array($query_piso);
				//print_r($data_orden);
	}
	else{
		header("location: lista_pisos.php");
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Piso</title>
</head>
<body>
	<?php 
	include "../conexion.php";
	include "includes/nav_admin.php"; 
	?>
	<main class="app-content">
		<div class="app-title">
			<h1><i class="fa fa-edit"></i>  Actualizar Piso</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="tile">
					<div class="row">
						<div class="col-lg-6">	


							<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>	
							<form action="" method="post">
								<input type="hidden" name="id" value="<?php echo $data_piso['idpiso']; ?>"/>	
								<div class="form-group">
									<label for="nombre" class="col-form-label">Nombre</label>
									<input type="text" class="form-control" name="nombre_piso" placeholder="Nombre Categoria" value="<?php echo $data_piso['nombre_piso']; ?>">
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