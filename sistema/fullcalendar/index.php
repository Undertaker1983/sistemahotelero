<?php
session_start();
include('../../conexion.php');
$sql = "SELECT id, title, start, end, color FROM events ";

$req = mysqli_query($conection,$sql);

$events = mysqli_fetch_all($req, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include "header_calendar.php"; ?>	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<!-- Bootstrap Core CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">

	<!-- FullCalendar -->
	<link href="css/fullcalendar.css" rel="stylesheet"/>	


	<!-- Custom CSS 
	<style>
   /* body {
        padding-top: 70px;
        
        }*/
        #calendar {
        	max-width: 800px;

        }
        .col-centered{
        	float: none;
        	margin: 0 auto;
        }
    </style>-->

</head>
<?php  
include "../../conexion.php";
include "nav_calendar.php";
?>
<main class="app-content">
	<div class="app-title">
		<div>
			<h1><i class="fa fa-hotel"></i> Reservaciones</h1>
		</div>
	</div>	
		<!-- Page Content -->
		<div class="row">
			<div class="col-md-12">
				<div class="tile">
					<div class="tile-body">

						<div id="calendar" class="col-centered">
						</div>
						<!-- /.row -->

						<!-- Modal -->
						<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<form class="form-horizontal" method="POST" action="addEvent.php">

										<div class="modal-header headerRegister">
											<h4 class="modal-title" id="myModalLabel"> Agregar Reservación</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											
										</div>
										<div class="modal-body">

											<div class="form-group">
												<label for="title" class="col-sm-2 control-label">Titulo</label>
												<div class="col-sm-10">
													<input type="text" name="title" class="form-control" id="title" placeholder="Titulo">
												</div>
											</div>
											<div class="form-group">
												<label for="color" class="col-sm-2 control-label">Color</label>
												<div class="col-sm-10">
													<select name="color" class="form-control" id="color">
														<option value="">Seleccionar</option>
														<option style="color:#0071c5;" value="#0071c5">&#9724; Azul oscuro</option>
														<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquesa</option>
														<option style="color:#008000;" value="#008000">&#9724; Verde</option>						  
														<option style="color:#FFD700;" value="#FFD700">&#9724; Amarillo</option>
														<option style="color:#FF8C00;" value="#FF8C00">&#9724; Naranja</option>
														<option style="color:#FF0000;" value="#FF0000">&#9724; Rojo</option>
														<option style="color:#000;" value="#000">&#9724; Negro</option>

													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="start" class="col-sm-2 control-label">Fecha Inicial</label>
												<div class="col-sm-10">
													<input type="text" name="start" class="form-control" id="start" readonly>
												</div>
											</div>
											<div class="form-group">
												<label for="end" class="col-sm-2 control-label">Fecha Final</label>
												<div class="col-sm-10">
													<input type="text" name="end" class="form-control" id="end" readonly>
												</div>
											</div>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-primary">Guardar</button>
										</div>
									</form>
								</div>
							</div>
						</div>



						<!-- Modal -->
						<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<form class="form-horizontal" method="POST" action="editEventTitle.php">
										<div class="modal-header headerUpdate">
											<h4 class="modal-title" id="myModalLabel">Modificar Reservación</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											
										</div>
										<div class="modal-body">

											<div class="form-group">
												<label for="title" class="col-sm-2 control-label">Titulo</label>
												<div class="col-sm-10">
													<input type="text" name="title" class="form-control" id="title" placeholder="Titulo">
												</div>
											</div>
											<div class="form-group">
												<label for="color" class="col-sm-2 control-label">Color</label>
												<div class="col-sm-10">
													<select name="color" class="form-control" id="color">
														<option value="">Seleccionar</option>
														<option style="color:#0071c5;" value="#0071c5">&#9724; Azul oscuro</option>
														<option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquesa</option>
														<option style="color:#008000;" value="#008000">&#9724; Verde</option>						  
														<option style="color:#FFD700;" value="#FFD700">&#9724; Amarillo</option>
														<option style="color:#FF8C00;" value="#FF8C00">&#9724; Naranja</option>
														<option style="color:#FF0000;" value="#FF0000">&#9724; Rojo</option>
														<option style="color:#000;" value="#000">&#9724; Negro</option>

													</select>
												</div>
											</div>
											<div class="form-group"> 
												<div class="col-sm-offset-2 col-sm-10">
													<div class="checkbox">
														<label class="text-danger"><input type="checkbox"  name="delete"> Eliminar Reservación</label>
													</div>
												</div>
											</div>

											<input type="hidden" name="id" class="form-control" id="id">


										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
											<button type="submit" class="btn btn-primary">Guardar</button>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
					<!-- /.container -->

					<!-- jQuery Version 1.11.1 -->
					<script src="js/jquery.js"></script>

					<!-- Bootstrap Core JavaScript -->
					<script src="js/bootstrap.min.js"></script>

					<!-- FullCalendar -->
					<script src='js/moment.min.js'></script>
					<script src='js/fullcalendar/fullcalendar.min.js'></script>
					<script src='js/fullcalendar/fullcalendar.js'></script>
					<script src='js/fullcalendar/locale/es.js'></script>

					<script src="../js/plugins/pace.min.js"></script>
					<script src="../js/main.js"></script>
					<script>

						$(document).ready(function() {

							var date = new Date();
							var yyyy = date.getFullYear().toString();
							var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
							var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();

							$('#calendar').fullCalendar({
								header: {
									language: 'es',
									left: 'prev,next today',
									center: 'title',
									right: 'month,basicWeek,basicDay',

								},
								defaultDate: yyyy+"-"+mm+"-"+dd,
								editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event): 

				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['end'];
				}
				?>
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $event['title']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					color: '<?php echo $event['color']; ?>',
				},
			<?php endforeach; ?>
			]
		});

							function edit(event){
								start = event.start.format('YYYY-MM-DD HH:mm:ss');
								if(event.end){
									end = event.end.format('YYYY-MM-DD HH:mm:ss');
								}else{
									end = start;
								}

								id =  event.id;

								Event = [];
								Event[0] = id;
								Event[1] = start;
								Event[2] = end;

								$.ajax({
									url: 'editEventDate.php',
									type: "POST",
									data: {Event:Event},
									success: function(rep) {
										if(rep == 'OK'){
											alert('Evento se ha guardado correctamente');
										}else{
											alert('No se pudo guardar. Inténtalo de nuevo.'); 
										}
									}
								});
							}

						});

					</script>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</main>

</body>
</html>

