<?php 
session_start();
include "../conexion.php";

if(!empty($_POST))
	{
			$alert	='';
			//if(empty($_POST['id']))
			if(empty($_POST['id']) || empty($_POST['trabajo']))	
		{
			$alert ='<p class="msg_error">Los campos nombre y trabajo son obligatorios.</p>';
		}else{
			$idorden		= $_POST['id'];
			//$idcliente 		= $_POST['idcliente'];
			$idequipo 		= $_POST['idequipo'];
			$idtecnico 		= $_POST['idtecnico'];
			//$usuario_id 	= $_SESSION['idUser'];
			$marca 			= $_POST['marca'];
			$serie 			= $_POST['serie'];
			$ncase 			= $_POST['ncase'];
			$fuente 		= $_POST['fuente'];
			$mainboard 		= $_POST['mainboard'];
			$procesador 	= $_POST['procesador'];
			$memoria 		= $_POST['memoria'];
			$discoduro 		= $_POST['discoduro'];
			$unidad_disco 	= $_POST['unidad_disco'];
			$tarjetas 		= $_POST['tarjetas'];
			$accesorios 	= $_POST['accesorios'];
			$observaciones 	= $_POST['observaciones'];
			$problema 		= $_POST['problema'];
			$trabajo 		= $_POST['trabajo'];

			// query
			$query_update = mysqli_query($conection,"UPDATE ordenes SET  idequipo = $idequipo, idtecnico = $idtecnico, marca = '$marca', serie = '$serie', ncase = '$ncase', fuente = '$fuente', mainboard = '$mainboard', procesador = '$procesador', memoria = '$memoria', discoduro = '$discoduro', unidad_disco = '$unidad_disco', tarjetas = '$tarjetas', accesorios = '$accesorios', observaciones = '$observaciones', problema = '$problema', trabajo = '$trabajo' WHERE idorden = $idorden");


			if($query_update){ 
								$alert='<p class="msg_save">Actualizado correctamente.</p>';
							}else{
								$alert='<p class="msg_error">Error al actualizar orden.</p>';
							}
				}
	}			

//Mostrar Datos
	if (empty($_REQUEST['id'])) {
			header("location: lista_orden.php");
		}else{
			$id_orden = $_REQUEST['id'];
			if (!is_numeric($id_orden)) {
				header("location: lista_orden.php");
			}
		
			$query_orden = mysqli_query($conection,"SELECT o.idorden,o.marca,o.serie,o.ncase,o.fuente,o.mainboard,o.procesador,o.memoria,o.discoduro,o.unidad_disco,o.tarjetas,o.accesorios,o.observaciones,o.problema,o.trabajo,e.idequipo,e.equipo,p.idpersona,p.nombre,p.cedula,p.telefono,p.direccion,p.correo,t.idtecnico,t.nombre_tecnico
									FROM ordenes o INNER JOIN
									personas p ON o.idpersona = p.idpersona INNER JOIN
                         			tecnicos t ON o.idtecnico = t.idtecnico INNER JOIN
									equipos e ON o.idequipo = e.idequipo
									WHERE o.idorden = $id_orden AND o.status = 1");
			
			$result_orden = mysqli_num_rows($query_orden);
			//mysqli_close($conection);
			
			if ($result_orden > 0 ) {
				$data_orden = mysqli_fetch_array($query_orden);
				//print_r($data_orden);
				}
				else{
				header("location: lista_orden.php");
			}
		}




 ?>
<html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Orden de Reparación</title>

</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
				<div class="title_page">
					<h1><i class="fas fa-edit "></i> Actualizar Orden de Reparación</h1>
				</div>
		<div class="datos_cliente_reparacion">
			<div class="action_cliente">
				<h4>Datos del Cliente</h4>
				
			</div>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post" class="datos3">
			<input type="hidden" name="id"  value="<?php echo $data_orden['idorden']; ?>"/>
			<div class="datos2">
				<div class="wd40">
					<label>Nombre:</label>
					<input type="text" name="nom_cliente" value="<?php echo $data_orden['nombre']; ?>" disabled>
				
				</div>
				<div class="wd25">
					<label>N° de Identificación:</label>
					<input type="text" name="nit_cliente" value="<?php echo $data_orden['cedula']; ?>" disabled>
					
				</div>
				
				<div class="wd30">
					<label>Teléfono:</label>
					<input type="text" name="tel_cliente"  value="<?php echo $data_orden['telefono']; ?>" disabled>
				</div>		
				<div class="wd60">
					<label>Dirección:</label>
					<input type="text" name="dir_cliente"  value="<?php echo $data_orden['direccion']; ?>" disabled>
				</div>
				<div class="wd30">
					<label>Correo:</label>
					<input type="text" name="cor_cliente"  value="<?php echo $data_orden['correo']; ?>" disabled>
				</div>
				
			</div>
		</div>
		
				
		<div class="datos_reparacion">
			<h4>Datos del Equipo</h4>
			<div class="datos2">
				<div class="wd15">
					<label>Fecha:</label>
					<input type="text" name="fecha_ingreso" value="<?php echo date("Y/m/d");?>" readonly/>
				</div>
				<div class="wd30">
					<label>Equipo:</label>
					<?php 
					 $query_equipo = mysqli_query($conection, "SELECT idequipo, equipo FROM equipos WHERE status = 1 ORDER BY idequipo ASC");
					 $result_equipo = mysqli_num_rows($query_equipo);
					 //mysqli_close($conection);	

					 ?>

					<select name="idequipo" class="notItemOne">
					<option value="<?php echo $data_orden['idequipo']; ?>" selected><?php echo $data_orden['equipo']; ?></option>
					<?php 
						if ($result_equipo > 0) {
							while ($equipo = mysqli_fetch_array($query_equipo)) {
					 ?>
					 		<option value="<?php echo $equipo['idequipo']; ?>"><?php echo $equipo['equipo']; ?></option>
					 <?php 

					  	}
						}
					 ?>
					
					</select>
				</div>
				<div class="w30">
					<label>Técnico Asignado</label>
					<?php 
					 $query_tecnicos = mysqli_query($conection, "SELECT * FROM tecnicos WHERE status = 1 ORDER BY nombre_tecnico DESC");
					 $result_tecnicos = mysqli_num_rows($query_tecnicos);
					 //mysqli_close($conection);	

					 ?>

					<select name="idtecnico" class="notItemOne">
					<option value="<?php echo $data_orden['idtecnico']; ?>" selected><?php echo $data_orden['nombre_tecnico'];?></option>
					<?php 
						if ($result_tecnicos > 0) {
							while ($tecnicos = mysqli_fetch_array($query_tecnicos)) {
					 ?>
					 		<option value="<?php echo $tecnicos['idtecnico']; ?>"><?php echo $tecnicos['nombre_tecnico']; ?></option>
					 <?php 

					  	}
						}
					 ?>
					
					</select>
				</div>
				<div class="wd30">
					<span>Marca:</span><input type="text" name="marca"  placeholder="Marca" value="<?php echo $data_orden['marca']; ?>"/>
				</div>
				<div class="wd30">
					<span>Serie:</span><input type="text" name="serie"  placeholder="Serie" value="<?php echo $data_orden['serie']; ?>"/>
				</div>
				<div class="wd30">
					<span>Case:</span><input type="text" name="ncase" placeholder="Case" value="<?php echo $data_orden['ncase']; ?>"/>
				</div>
				<div class="wd30">
					<span>Fuente:</span><input type="text" name="fuente"  placeholder="Fuente de Poder" value="<?php echo $data_orden['fuente']; ?>"/>
				</div>
				<div class="wd30">
					<span>Procesador:</span><input type="text" name="procesador"  placeholder="Procesador" value="<?php echo $data_orden['procesador']; ?>"/>
				</div>
				<div class="wd30">
					<span>Mainboard:</span><input type="text" name="mainboard"  placeholder="Mainboard" value="<?php echo $data_orden['mainboard']; ?>"/>
				</div>
				<div class="wd30">
					<span>Disco Duro:</span><input type="text" name="discoduro"  placeholder="Disco duro" value="<?php echo $data_orden['discoduro']; ?>"/>
				</div>
				<div class="wd30">
					<span>Memoria:</span><input type="text" name="memoria"  placeholder="Memoria" value="<?php echo $data_orden['memoria']; ?>"/>
				</div>
				<div class="wd30">
					<span>Tarjetas:</span><input type="text" name="tarjetas" placeholder="Tarjetas" value="<?php echo $data_orden['tarjetas']; ?>"/>
				</div>
				<div class="wd30">
					<span>Unidad CD/DVD:</span><input type="text" name="unidad_disco" placeholder="CD/DVD" value="<?php echo $data_orden['unidad_disco']; ?>"/>
				</div>
				<div class="wd65">
					<span>Observaciones:</span><input type="text" name="observaciones" placeholder="Observaciones" value="<?php echo $data_orden['observaciones']; ?>"/>
				</div>
				<div class="wd100">
					<span>Cables, Cargadores y Accesorios:</span><input type="text" name="accesorios"  placeholder="Accesorios" value="<?php echo $data_orden['accesorios']; ?>"/>
				</div>
				<div class="wd55">
					<span>Problema Reportado:</span><input type="text" name="problema"  placeholder="Problema Reportado" value="<?php echo $data_orden['problema']; ?>"/>
				</div>
				<div class="wd40">
					<span>Trabajo a Realizar:</span><input type="text" name="trabajo"  placeholder="Trabajo a Realizar" value="<?php echo $data_orden['trabajo']; ?>"/>
				</div>
				<div class="wd100">
					<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Actualizar</button>
				</div>
			 </div>	
			</form>
		</div>
		
	</section>

<?php include "includes/footer.php"; ?>
	
</body>
</html>

