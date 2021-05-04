<?php 
	session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
	<title>Copia de Seguridad</title>
    
</head>
<body>
<?php include "includes/header.php"; ?>	
<section id="container">
<h1><i class="fas fa-database"></i> GENERAR COPIA DE SEGURIDAD</h1>
<center>
    <h3>Realizar Backup</h3>
    <a href="Function_Backup.php"><img src="img/download.jpg" height="100px" width="100px"></a>
</center>
<?php include "includes/footer.php"; ?>
</section>
</body>
</html>