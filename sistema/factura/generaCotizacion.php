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

	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la cotización.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		$anulada = '';

		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conection,"SELECT c.nocotizacion, DATE_FORMAT(c.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(c.fecha,'%H:%i:%s') as  hora, c.codpersona, c.status,
												 v.nombre as vendedor,
												 p.idpersona,p.cedula, p.nombre, p.telefono,p.direccion
											FROM cotizacion c
											INNER JOIN usuario v
											ON c.usuario = v.idusuario
											INNER JOIN personas p
											ON c.codpersona = p.idpersona
											WHERE c.nocotizacion = $noFactura AND c.codpersona = $codCliente  AND c.status != 10 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$no_factura = $factura['nocotizacion'];

			if($factura['status'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conection,"SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM cotizacion c
														INNER JOIN detallefactura dt
														ON c.nocotizacion = dt.nofactura
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE c.nocotizacion = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/cotizacion.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'landscape');
			//$dompdf->setPaper('A5');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('cotizacion_'.$noFactura.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>