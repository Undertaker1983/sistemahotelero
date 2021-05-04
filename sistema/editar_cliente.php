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
	if(empty($_POST['nombre']) || empty($_POST['telefono']))
	{
		$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
	}else{

		$idcliente 	= $_POST['id'];
		$cedula 	= $_POST['cedula'];
		$nombre 	= $_POST['nombre'];
		$telefono   = $_POST['telefono'];
		$direccion  = $_POST['direccion'];
		$correo  	= $_POST['correo'];

		$result = 0;

		if (is_numeric($cedula)) 
		{
			$query = mysqli_query($conection,"SELECT * FROM personas 
				WHERE (cedula = '$cedula' AND idpersona != $idcliente)");
			$result = mysqli_fetch_array($query);
			

		}

		if($result > 0){
			$alert='<p class="msg_error">El numero de identificación ya existe.</p>';
		}else{

			$sql_update = mysqli_query($conection,"UPDATE personas
				SET cedula='$cedula',nombre='$nombre',telefono='$telefono',direccion='$direccion',correo='$correo' WHERE idpersona= $idcliente");


			if($sql_update){
				$alert='<p class="msg_save">Cliente actualizado correctamente.</p>';
			}else{
				$alert='<p class="msg_error">Error al actualizar el cliente.</p>';
			}

		}


	}


}

	//Mostrar Datos
if(empty($_REQUEST['id']))
{
	header('Location: lista_clientes.php');
	mysqli_close($conection);
}
$idcliente = $_REQUEST['id'];

$sql= mysqli_query($conection,"SELECT p.idtipo,p.idpersona,p.nombre,p.cedula,p.telefono,p.direccion,p.telefono,p.correo,tp.tipo,tp.idtipo
	FROM personas p
	INNER JOIN tipo_persona tp ON p.idtipo = tp.idtipo
	WHERE p.idpersona= $idcliente AND p.status = 1");
	//mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if($result_sql > 0){
	$data = mysqli_fetch_array($sql);

}else{
	header('Location: lista_clientes.php');
		/*$option = '';
		while () {
			
			$idcliente  = $data['idpersona'];
			$cedula  	= $data['cedula'];
			$nombre  	= $data['nombre'];
			$telefono 	= $data['telefono'];
			$direccion  = $data['direccion'];
			$correo  	= $data['correo'];*/
			
		}
		/*}*/

		?>

		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<?php include "includes/header_admin.php"; ?>
			<title>Actualizar Cliente</title>
		</head>
		<body>
			<?php 
			include "../conexion.php";
			include "includes/nav_admin.php";
			?>
			<main class="app-content">
				<div class="app-title">
					<h1><i class="fa fa-edit"></i>  Actualizar Clientes</h1>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="tile">
						<div class="row">
              <div class="col-lg-6">			
							<form action="" method="post">
								<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>	
								<input type="hidden" name="id" value="<?php echo $data ['idpersona']; ?>">
				<!--<label class="labelregistro">Tipo de Persona</label>
				<?php 
                     $query_tipo = mysqli_query($conection, "SELECT p.idtipo,p.nombre,tp.tipo FROM personas p 
                     										INNER JOIN tipo_persona tp ON p.idtipo = tp.idtipo
                     										WHERE p.status = 1 AND p.idtipo = 1 ORDER BY p.nombre ASC");
                     $result_tipo = mysqli_num_rows($query_tipo);
                      
                     ?>
                    <select name="idtipo" id="idtipo" class="notItemOne">
                    <option value="<?php echo $data['idtipo']; ?>" selected><?php echo $data['tipo']; ?></option>
                    <?php
                        if ($result_tipo > 0) {
                            while ($tipo = mysqli_fetch_array($query_tipo)) {
                     ?>
                     <option value="<?php echo $tipo['idtipo']; ?>"><?php echo $tipo['tipo']; ?></option>
                     <?php 
                        }
                        }
                     ?>
                 </select>-->

                 <div class="form-group">     
                 	<label for="cedula" class="col-form-label">Nº Identificación (Cédula/Ruc/Pasaporte)</label>
                 	<input type="text" name="cedula" id="cedula" class="form-control" placeholder="Número de Identificación" value="<?php echo $data['cedula']; ?>">
                 </div>
                 <div class="form-group">
                 	<label for="nombre" class="col-form-label">Nombres y Apellidos</label>
                 	<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre completo" value="<?php echo $data['nombre']; ?>">
                 </div>

                 <div class="form-group">
                 	<div>
                 		<label for="telefono" class="col-form-label">Teléfono</label>
                 		<input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" value="<?php echo $data['telefono']; ?>">
                 	</div>
                 	<div class="form-group">
                 		<label for="direccion" class="col-form-label">Dirección</label>
                 		<input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección" value="<?php echo $data['direccion']; ?>">
                 	</div>
                 	<div class="form-group">
                 		<label for="exampleInputEmail1" class="labelregistro">Correo electrónico</label>
                 		<input type="email" aria-describedby="emailHelp" class="form-control" name="correo" id="correo" placeholder="Correo electrónico" value="<?php echo $data['correo']; ?>">
                 	</div>
                 </div>
                 <button type="submit" class="btn btn-primary">Actualizar Cliente</button>

             </form>

         </div>
     </div>
 </div>
</div>

</main>
<?php 
include "includes/footer_admin.php";
?>