<?php 

session_start();
if($_SESSION['rol'] != 1)
{
	header("location: ./");
}

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])  || empty($_POST['rol']))
	{
		$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
	}else{

		$idUsuario = $_POST['id'];
		$nombre = $_POST['nombre'];
		$email  = $_POST['correo'];
		$user   = $_POST['usuario'];
		$clave  = md5($_POST['clave']);
		$rol    = $_POST['rol'];


		$query = mysqli_query($conection,"SELECT * FROM usuario 
			WHERE (usuario = '$user' AND idusuario != $idUsuario)
			OR (correo = '$email' AND idusuario != $idUsuario) ");

		$result = mysqli_fetch_array($query);
			//$result = count($result);

		if($result > 0){
			$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
		}else{

			if(empty($_POST['clave']))
			{

				$sql_update = mysqli_query($conection,"UPDATE usuario
					SET nombre = '$nombre', correo='$email',usuario='$user',rol='$rol'
					WHERE idusuario= $idUsuario ");
			}else{
				$sql_update = mysqli_query($conection,"UPDATE usuario
					SET nombre = '$nombre', correo='$email',usuario='$user',clave='$clave', rol='$rol'
					WHERE idusuario= $idUsuario ");

			}

			if($sql_update){
				$alert='<p class="msg_save">Usuario actualizado correctamente.</p>';
			}else{
				$alert='<p class="msg_error">Error al actualizar el usuario.</p>';
			}

		}


	}


}

	//Mostrar Datos
if(empty($_REQUEST['id']))
{
	header('Location: lista_usuarios.php');
	mysqli_close($conection);
}
$iduser = $_REQUEST['id'];

$sql= mysqli_query($conection,"SELECT u.idusuario, u.nombre,u.correo,u.usuario, (u.rol) as idrol, (r.rol) as rol
	FROM usuario u
	INNER JOIN rol r
	on u.rol = r.idrol
	WHERE idusuario= $iduser AND estatus = 1");
mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if($result_sql == 0){
	header('Location: lista_usuarios.php');
}else{
	$option = '';
	while ($data = mysqli_fetch_array($sql)) {
			# code...
		$iduser  = $data['idusuario'];
		$nombre  = $data['nombre'];
		$correo  = $data['correo'];
		$usuario = $data['usuario'];
		$idrol   = $data['idrol'];
		$rol     = $data['rol'];

		if($idrol == 1){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
		}else if($idrol == 2){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';	
		}else if($idrol == 3){
			$option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
		}


	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>

<main class="app-content">		
	<div class="app-title">
		<h1><i class="fa fa-edit"></i> Actualizar usuario</h1>
	</div>
	<div class="col-md-8">

		<div class="tarjeta col-md-6">		
			<form action="" method="post">
				<div class="form-group row">
					<input type="hidden" name="id" value="<?php echo $iduser; ?>">
					<label class="control-label col-md-3" for="nombre">Nombre</label>
					<div class="col-md-8">
						<input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php echo $nombre; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="correo">Correo electrónico</label>
					<div class="col-md-8">
						<input type="email" class="form-control" name="correo" id="correo" placeholder="Correo electrónico" value="<?php echo $correo; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="usuario">Usuario</label>
					<div class="col-md-8">
						<input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-md-3" for="clave">Clave</label>
					<div class="col-md-8">
						<input class="form-control" type="password" name="clave" id="clave" placeholder="Clave de acceso">
					</div>
				</div>

				<div class="form-group row">	
					<label class="control-label col-md-3" for="rol">Tipo Usuario</label>

					<?php 
					include "../conexion.php";
					$query_rol = mysqli_query($conection,"SELECT * FROM rol");
					mysqli_close($conection);
					$result_rol = mysqli_num_rows($query_rol);

					?>
					<div class="col-md-8">
						<select name="rol" id="rol" class="form-control notItemOne">
							<?php
							echo $option; 
							if($result_rol > 0)
							{
								while ($rol = mysqli_fetch_array($query_rol)) {
									?>
									<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"] ?></option>
									<?php 
								# code...
								}

							}
							?>
						</select>
					</div>
				</div>

				<div class="tile-footer">
					<center>
						<button type="submit" class="btn btn-primary" id="register"><i class="fa fa-edit"></i> Actualizar Usuario</button>
					</center>
				</div>
			</div>
		</form>
	</div>

</div>


</main>
<!--Essential javascripts for application to work-->
<script src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script src="js/sweetalert2.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>	
<script type="text/javascript">
	$(function(){
		$('#register').click(function(e){
			var valid = this.form.checkValidity();

			if(valid){
				var id 						= $('#id').val();
				var nombre 					= $('#nombre').val();
				var correo 					= $('#correo').val();
				var usuario 				= $('#usuario').val();
				var clave 					= $('#clave').val();
				var rol 					= $('#rol').val();

				e.preventDefault();	

				$.ajax({
					type: 'POST',
					url: 'editar_usuario.php',
					data: {id : id, nombre: nombre,correo: correo, usuario: usuario, clave: clave, rol: rol},
					success: function(data){
						Swal.fire({
							icon: 'success',
							title: 'Actualizando...',
							text: 'Datos actualizados correctamente',
							showConfirmButton: true,
									/*'title': 'Successful',
									'text': data,
									'type': 'success'*/
								});

					},
					error: function(data){
						Swal.fire({
							title: 'Error',
							text: 'Error al actualizar el registro.',
							type: 'error'
						});
					}
				});


			}else{

			}





		});		


	});	
</script>

</body>
</html>