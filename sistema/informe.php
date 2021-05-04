<?php 
session_start();
include "../conexion.php";

if(!empty($_POST))
	{
			$alert	='';
			//if(empty($_POST['id']))
			
			$idorden		= $_POST['id'];
			$informe 		= $_POST['informe'];
			$estado	 		= $_POST['estado'];
			$precio	 		= $_POST['precio'];
			$fecha_entrega	= $_POST['fecha_entrega'];
			

			// query
			$query_update = mysqli_query($conection,"UPDATE ordenes SET  informe = '$informe', estado = '$estado', precio = '$precio', fecha_entrega = '$fecha_entrega' WHERE idorden = $idorden");

			if($query_update){ 
								$alert='<p class="msg_save">Informe realizado correctamente.</p>';
							}else{
								$alert='<p class="msg_error">Error al realizar informe.</p>';
								
							}
				
	}			

//Mostrar Datos
	if (empty($_REQUEST['id'])) {
			header("location: lista_informe.php");
		}else{
			$id_orden = $_REQUEST['id'];
			if (!is_numeric($id_orden)) {
				header("location: lista_informe.php");
			}
		
			$query_orden = mysqli_query($conection,"SELECT o.idorden,o.informe,o.precio,o.fecha_entrega,o.problema,o.observaciones,o.trabajo,o.estado,e.idequipo,e.equipo
									FROM ordenes o INNER JOIN
									      			
									equipos e ON o.idequipo = e.idequipo
									WHERE o.idorden = $id_orden AND o.status = 1");
			
			$result_orden = mysqli_num_rows($query_orden);
			//mysqli_close($conection);
			
			if ($result_orden > 0 ) {
				$data_orden = mysqli_fetch_array($query_orden);
				//print_r($data_orden);
				}
				else{
				header("location: lista_informe.php");
			}
		}




 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Informe de Reparaciones</title>

</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
				<div class="title_page">
					<h1><i class="fas fa-edit "></i> Informe de Reparaciones</h1>
				</div>
						
		<div class="datos_reparacion">
							
		<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post" class="datos2">
			<input type="hidden" name="id" value="<?php echo $data_orden['idorden']; ?>">	
				<div class="wd30">
					<span>Equipo:</span><input type="text" name="equipo" placeholder="Equipo" value="<?php echo $data_orden['equipo']; ?>" readonly>
				</div>
				<div class="wd65">
					<span>Observaciones:</span><input type="text" name="observaciones" placeholder="Observaciones" value="<?php echo $data_orden['observaciones']; ?>" readonly>
				</div>
				
				<div class="wd55">
					<span>Problema Reportado:</span><input type="text" name="problema" placeholder="Problema Reportado" value="<?php echo $data_orden['problema']; ?>"readonly>
				</div>
				<div class="wd40">
					<span>Trabajo a Realizar:</span><input type="text" name="trabajo" placeholder="Trabajo a Realizar" value="<?php echo $data_orden['trabajo']; ?>"readonly>
				</div>
				<div>
					<span>Informe Técnico:</span><textarea style="width: 500px; height: 70px" name="informe"><?php echo $data_orden['informe']; ?></textarea>
				</div>
				<div class="wd15">
					<span>Precio:</span><input type="text" name="precio" placeholder="Precio" value="<?php echo $data_orden['precio']; ?>">
				</div>
				<div class="wd20">
					<span>Estado:</span>
					<select name="estado" id="estado" class="notItemOne">
					<option value="<?php echo $data_orden['estado']; ?>" selected><?php echo $data_orden['estado']; ?></option>
							<option value="EN REPARACIÓN">EN REPARACIÓN</option>
                  			<option value="LISTO">LISTO</option>
                  			<option value="ENTREGADO">ENTREGADO</option>
					
					</select>
				</div>
				<div class="wd15">
					<span>Fecha de Entrega:</span><input type="date" name="fecha_entrega" value="<?php echo $data_orden['fecha_entrega']; ?>">
				</div>
				<div class="wd100">
					<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
				</div>
			 </div>	
			</form>
		</div>
		
	</section>

<?php include "includes/footer.php"; ?>
	
</body>
</html>

