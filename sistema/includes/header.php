<?php 

	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
 ?>
	<header>
		<div class="header">
		<a href="#" class="btnMenu"><i class="fas fa-bars"></i></a>	
			<!--<h1>WTEC</h1>-->
			<img class="photologo" src="img/wtec.png" alt="Login">
			<div class="optionsBar">
				<!--<p><?php echo fechaC(); ?></p>
				<span>|</span>-->
				<span class="user"><?php echo $_SESSION['user']; ?></span>
				<!--.' -'.$_SESSION['rol'].' -'.$_SESSION['email']-->
				<img class="photouser" src="img/user1.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<?php include "nav.php"; ?>
	</header>

	<div class="modal">
		<div class="bodyModal">
		</div>

	</div>