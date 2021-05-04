<?php

	//print_r($_REQUEST);
	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;

	if(empty($_REQUEST['cli']) || empty($_REQUEST['o']))
	{
		echo "No es posible generar la orden.";
	}else{
		$codCliente = $_REQUEST['cli'];
		$noOrden = $_REQUEST['o'];
		
		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conection,"SELECT o.idorden,o.marca,o.serie,o.ncase,o.fuente,o.mainboard,o.procesador,o.memoria,o.discoduro,o.unidad_disco, DATE_FORMAT(o.fecha_ingreso, '%d/%m/%Y') as fecha, DATE_FORMAT(o.fecha_ingreso,'%H:%i:%s') as  hora, o.idpersona,o.tarjetas,o.accesorios,o.observaciones,o.problema,o.trabajo, o.status,o.usuario_id,e.equipo,t.nombre_tecnico, v.nombre as vendedor, p.cedula, p.nombre, p.telefono,p.direccion
											FROM ordenes o
											INNER JOIN usuario v
											ON o.usuario_id = v.idusuario
											INNER JOIN personas p
											ON o.idpersona = p.idpersona
											INNER JOIN equipos e
											ON o.idequipo = e.idequipo
											INNER JOIN tecnicos t
											ON o.idtecnico = t.idtecnico
											WHERE o.idorden = $noOrden AND o.idpersona= $codCliente  AND o.status = 1 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$orden = mysqli_fetch_assoc($query);
			$no_orden = $orden['idorden'];

			/*if($factura['status'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conection,"SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detallefactura dt
														ON f.nofactura = dt.nofactura
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE f.nofactura = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);*/

			ob_start();
		    include(dirname('__FILE__').'/ordenreparacion.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'landscape');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('orden_'.$noOrden.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>