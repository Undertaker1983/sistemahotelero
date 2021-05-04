<?php 
session_start();
include "../conexion.php";

if(!empty($_POST))
	{
			$alert	='';
			if(empty($_POST['nom_cliente']))
			//if(empty($_POST['nit_cliente']) || empty($_POST['trabajo']))	
		{
			$alert ='<p class="msg_error">El campo nombre es obligatorio.</p>';
		}else{
			$idcliente 		= $_POST['idpersona'];
			$idequipo 		= $_POST['idequipo'];
			$idtecnico 		= $_POST['idtecnico'];
			$usuario_id 	= $_SESSION['idUser'];
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
			$query_insert = mysqli_query($conection,"INSERT INTO ordenes(idpersona,idequipo,idtecnico,usuario_id,marca,serie,ncase,fuente,mainboard,procesador,memoria, discoduro,unidad_disco,tarjetas,accesorios,observaciones,problema,trabajo) VALUES('$idcliente','$idequipo','$idtecnico','$usuario_id','$marca','$serie','$ncase','$fuente','$mainboard','$procesador','$memoria','$discoduro','$unidad_disco','$tarjetas','$accesorios','$observaciones','$problema','$trabajo')");

			if($query_insert){ 
								header("location: lista_orden.php");
								//$alert='<p class="msg_save">Orden registrado correctamente.</p>';
							}else{
								$alert='<p class="msg_error">Error al registrar orden.</p>';
							}
				}
	}			
 ?>
<html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Orden de Reparación</title>

</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
				<div class="title_page">
					<h1><i class="fas fa-tools "></i> Nueva Orden</h1>
				</div>
		<div class="datos_cliente_reparacion">
			<div class="action_cliente">
				<h4>Datos del Cliente</h4>
				<a href="#" class="btn_new add_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>
				<!--<a href="registro_cliente.php" class="btn_new"><i class="fas fa-plus"></i> Nuevo Cliente</a>-->
			</div>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<form action="" method="post" class="datos3">
			<div class="datos2">
				<div class="wd40">
					<label>Nombre:</label>
					<input type="text" name="nom_cliente" id="nom_cliente" >
					
				</div>
				<div class="wd25">
					<label>N° de Identificación:</label>
					<input type="text" name="nit_cliente" id="nit_cliente" >
					<input type="hidden" id="idpersona" name="idpersona" value="" required>
				</div>
				
				<div class="wd30">
					<label>Teléfono:</label>
					<input type="text" name="tel_cliente" id="tel_cliente" disabled>
				</div>		
				<div class="wd60">
					<label>Dirección:</label>
					<input type="text" name="dir_cliente" id="dir_cliente" disabled>
				</div>
				<div class="wd30">
					<label>Correo:</label>
					<input type="text" name="cor_cliente" id="cor_cliente" disabled>
				</div>
				
			</div>
		</div>
		
				
		<div class="datos_reparacion">
			<h4>Datos del Equipo</h4>
			<div class="datos2">
				<div class="wd15">
					<label>Fecha:</label>
					<input type="text" value="<?php echo date("Y/m/d");?>" name="FechaIngreso" readonly>
				</div>
				<div class="wd30">
					<label>Equipo:</label>
					<?php 
					 $query_equipo = mysqli_query($conection, "SELECT idequipo, equipo FROM equipos WHERE status = 1 ORDER BY idequipo ASC");
					 $result_equipo = mysqli_num_rows($query_equipo);
					 //mysqli_close($conection);	

					 ?>

					<select name="idequipo" id="idequipo">
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

					<select name="idtecnico" id="idtecnico">
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
					<span>Marca:</span><input type="text" name="marca" id="marca" placeholder="Marca">
				</div>
				<div class="wd30">
					<span>Serie:</span><input type="text" name="serie" id="serie" placeholder="Serie">
				</div>
				<div class="wd30">
					<span>Case:</span><input type="text" name="ncase" id="ncase" placeholder="Case">
				</div>
				<div class="wd30">
					<span>Fuente:</span><input type="text" name="fuente" id="fuente" placeholder="Fuente de Poder">
				</div>
				<div class="wd30">
					<span>Procesador:</span><input type="text" name="procesador" id="procesador" placeholder="Procesador">
				</div>
				<div class="wd30">
					<span>Mainboard:</span><input type="text" name="mainboard" id="mainboard" placeholder="Mainboard">
				</div>
				<div class="wd30">
					<span>Disco Duro:</span><input type="text" name="discoduro" id="discoduro" placeholder="Disco duro">
				</div>
				<div class="wd30">
					<span>Memoria:</span><input type="text" name="memoria" id="memoria" placeholder="Memoria">
				</div>
				<div class="wd30">
					<span>Tarjetas:</span><input type="text" name="tarjetas" id="tarjetas" placeholder="Tarjetas">
				</div>
				<div class="wd30">
					<span>Unidad CD/DVD:</span><input type="text" name="unidad_disco" id="unidad_disco" placeholder="CD/DVD">
				</div>
				<div class="wd65">
					<span>Observaciones:</span><input type="text" name="observaciones" id="observaciones" placeholder="Observaciones">
				</div>
				<div class="wd100">
					<span>Cables, Cargadores y Accesorios:</span><input type="text" name="accesorios" id="accesorios" placeholder="Accesorios">
				</div>
				<div class="wd55">
					<span>Problema Reportado:</span><input type="text" name="problema" id="problema" placeholder="Problema Reportado">
				</div>
				<div class="wd40">
					<span>Trabajo a Realizar:</span><input type="text" name="trabajo" id="trabajo" placeholder="Trabajo a Realizar">
				</div>
				<div class="wd100">
					<button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar Orden</button>
				</div>
			 </div>	
			</form>
		</div>
		
	</section>


<?php include "includes/footer.php"; ?>
<script type="text/javascript">
$(function() {
            $("#nom_cliente").autocomplete({
                source: "personas.php",
                minLength: 2,
                select: function(event, ui) {
					event.preventDefault();
                    $('#nom_cliente').val(ui.item.nombre);
					$('#nit_cliente').val(ui.item.cedula);
					$('#tel_cliente').val(ui.item.telefono);
					$('#dir_cliente').val(ui.item.direccion);
					$('#cor_cliente').val(ui.item.correo);
					$('#idpersona').val(ui.item.idpersona);
			     }
            });
		});
</script>	
</body>
</html>

