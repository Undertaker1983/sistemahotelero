<?php 

session_start();
if($_SESSION['rol'] != 1)
{
	header("location: ./");
}

include "../conexion.php";

//Mostrar Datos
if(empty($_REQUEST['id']))
{
	header('Location: lista_clientes.php');
	mysqli_close($conection);

}else{
	$idpersonas = $_REQUEST['id'];

	$sql= mysqli_query($conection,"SELECT p.idtipo, p.idpersona,p.cedula,p.nombre,p.telefono,p.direccion,p.correo,p.razon_social,p.entidad_financiera,p.tipo_cuenta,p.numero_cuenta,tp.idtipo,tp.tipo
		FROM personas p INNER JOIN
		tipo_persona tp ON p.idtipo = tp.idtipo
		WHERE p.idpersona= $idpersonas AND p.status = 1");
	//mysqli_close($conection);
	$result_sql = mysqli_num_rows($sql);

	if($result_sql > 0){
		$data = mysqli_fetch_array($sql);
	}else{
		header("Location: lista_clientes.php");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/header_admin.php"; ?>
	<title>Actualizar Registro</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
?>
<main class="app-content">
	<div class="app-title">
		<h1><i class="fa fa-users"></i> Actualizar Registro</h1>
		
	</div>
	<div class="row">
		<div class="tarjeta col-lg-6">
			<form action="" method="post">
				<!--<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>-->	
				<div class="form-row">
					<input type="hidden" id="id" name="id" value="<?php echo $data ['idpersona']; ?>">
					<input type="hidden" id="idtipo" name="idtipo" value="<?php echo $data ['idtipo']; ?>">
					<div class="form-group col-md-8">      
						<label for="cedula" class="col-form-label">Nº Identificación (Cédula/Ruc/Pasaporte)</label>
						<input type="text" name="cedula" id="cedula" class="form-control" placeholder="Número de Identificación" value="<?php echo $data['cedula']; ?>">
					</div>

					<div class="form-group col-md-8">
						<label for="nombre" class="col-form-label">Nombres y Apellidos</label>
						<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre completo" value="<?php echo $data['nombre']; ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="telefono" class="col-form-label">Teléfono</label>
						<input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" value="<?php echo $data['telefono']; ?>">
					</div>

					<div class="form-group col-md-8">
						<label for="direccion" class="col-form-label">Dirección</label>
						<input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección" value="<?php echo $data['direccion']; ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="correo" class="col-form-label">Correo electrónico</label>
						<input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico" value="<?php echo $data['correo']; ?>">
					</div>

					<div class="form-group col-md-7">
						<label for="razon" class="col-form-label">Razón Social</label>
						<input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón Social" value="<?php echo $data['razon_social']; ?>">
					</div>

					<div class="form-group col-md-5">
						<label for="entidad" class="col-form-label">Entidad Financiera</label>
						<input type="text" name="entidad_financiera" id="entidad_financiera" class="form-control" placeholder="Entidad Financiera" value="<?php echo $data['entidad_financiera']; ?>">
					</div>

					<div class="form-group col-md-5">
						<label for="tipo_cuenta" class="col-form-label">Tipo de Cuenta</label>
						<select name="tipo_cuenta" id="tipo_cuenta" class="form-control">
							<option value="<?php echo $data['tipo_cuenta']; ?>"></option>
							<option value="Cuenta de Corriente">Cuenta Corriente</option>
							<option value="Cuenta de Ahorros">Cuenta de Ahorros</option>
						</select>
					</div>

					<div class="form-group col-md-7">
						<label for="numero_cuenta" class="col-form-label">Número de Cuenta</label>
						<input type="text" name="numero_cuenta" id="numero_cuenta" class="form-control" placeholder="Número de Cuenta" value="<?php echo $data['numero_cuenta']; ?>">
					</div>
				</div>

				<div class="tile-footer">
					<center>
						<button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i> Actualizar Registro</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary btn_cancelar" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
					</center>
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
	

</body>
</html>

<?php 

include "../conexion.php";

if(!empty($_POST))
{
	$alert='';
	if(empty($_POST['id']) || empty($_POST['nombre']))
	{
		?>
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'El campo nombre es obligatorio',
									type: 'error',
								});
		</script>
		
		<?php 
	}else{

		$id 			    	= $_POST['id'];
		$idtipo 			    = $_POST['idtipo'];
		$cedula 				= $_POST['cedula'];
		$nombre 				= $_POST['nombre'];
		$telefono   			= $_POST['telefono'];
		$direccion  			= $_POST['direccion'];
		$correo  				= $_POST['correo'];
		$razon_social 	 	    = $_POST['razon_social'];
		$entidad_financiera	    = $_POST['entidad_financiera'];
		$tipo_cuenta   			= $_POST['tipo_cuenta'];
		$numero_cuenta  		= $_POST['numero_cuenta'];
		

		$result = 0;

		if (is_numeric($cedula)) 
		{
			$query = mysqli_query($conection,"SELECT * FROM personas 
				WHERE (cedula = '$cedula' AND idpersona != $id)");
			$result = mysqli_fetch_array($query);
			

		}

		$sql_update = mysqli_query($conection,"UPDATE personas SET idtipo = $idtipo, cedula = '$cedula',nombre = '$nombre',telefono='$telefono',direccion='$direccion',razon_social='$razon_social',entidad_financiera='$entidad_financiera', tipo_cuenta='$tipo_cuenta', numero_cuenta='$numero_cuenta', correo='$correo' WHERE idpersona = $id");


		if($sql_update){
			?>
			
			<script type="text/javascript">
				Swal.fire({
									icon: 'success',
									title: 'Guardando...',
									text: 'Datos actualizados correctamente',
									showConfirmButton: true,
									
								});
			</script>
			
			<?php 
		}else{
			?>
		
		<script type="text/javascript">
			Swal.fire({
									icon : 'error',
									title: 'Error',
									text: 'Error al actualizar producto',
									type: 'error',
								});
		</script>
		
		<?php 
		}

	}


}
?>