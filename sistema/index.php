<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/header_admin.php"; ?>
  
  <title>WTEC</title>
</head>
<?php 
include "../conexion.php";
include "includes/nav_admin.php";
//Datos Empresa
$nit = '';
$nombreEmpresa = '';
$razonSocial = '';
$telEmpresa = '';
$emailEmpresa = '';
$dirEmpresa = '';
$iva = '';

$query_empresa = mysqli_query($conection,"SELECT * FROM configuracion");
$row_empresa = mysqli_num_rows($query_empresa);

if ($row_empresa > 0) {
  while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)) {
    $nit = $arrInfoEmpresa['cedula'];
    $nombreEmpresa = $arrInfoEmpresa['nombre'];
    $razonSocial = $arrInfoEmpresa['razon_social'];
    $telEmpresa = $arrInfoEmpresa['telefono'];
    $emailEmpresa = $arrInfoEmpresa['email'];
    $dirEmpresa = $arrInfoEmpresa['direccion'];
    $iva = $arrInfoEmpresa['iva'];
  }
}


$query_dash = mysqli_query($conection,"CALL dataDashboard();");
$result_dash = mysqli_num_rows($query_dash);
if ($result_dash > 0) {
  $data_dash = mysqli_fetch_assoc($query_dash);
  mysqli_close($conection);
}

?>
<main class="app-content">

  <div class="app-title">
    <h2>Panel de Control</h2>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="widget-small primary"><i class="icon fa fa-hotel fa-3x"></i>  
        <div class="info">
          <?php 
          if ($_SESSION['rol'] == 1 || $_SESSION['rol'] ==2 ) {
            # code...

            ?>
              <!--<a href="lista_usuarios.php">
                <p>-->
                  <h4>Reservaciones</h4>
                  <p><b><?= $data_dash['reservaciones'] ?></b></p>
              <!--</p>
              </a>-->
              <?php 
            }
            ?>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="widget-small info"><i class="icon fa fa-users fa-3x"></i>
          <div class="info">
            <!--<a href="lista_personas.php"> </a>-->
            <h4>Clientes</h4>
            <p><b><?= $data_dash['persona']; ?></p></b>
            <?php 
            if ($_SESSION['rol'] == 1 || $_SESSION['rol'] ==2 ) {
            # code...
              ?>
        <!--<a href="lista_proveedores.php">
          <i class="fas fa-truck color_proveedores"></i>
          <p>
            <strong class="color_proveedores">Proveedores</strong><br>
            <span class="color_proveedores"><?= $data_dash['proveedores']; ?></span>
          </p>
        </a>
        <?php 
          } 
          ?>-->
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="widget-small warning"><i class="icon fa fa-cubes fa-3x"></i>
        <div class="info">
          <!--<a href="lista_productos.php"></a>-->
          <h4>Productos</h4>
          <p><b><?= $data_dash['productos']; ?></p></b>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="widget-small danger"><i class="icon fa fa-shopping-cart fa-3x"></i>
        <div class="info">
          <!--<a href="ventas.php"></a>-->
          <h4>CHECK OUT/Ventas</h4>
          <p><b><?= $data_dash['ventas']; ?></p></b>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title">Información Personal</h3>
        <div class="tile-body">
          <div class="logoUser">
            <img src="img/logoUser.png" width="380" height="300">
          </div>  
          <div class="form-group">
           <label class="control-label col-md-3">Nombre: </label>
           <span> <?= $_SESSION['nombre']; ?></span>
         </div>
         <div class="form-group">
          <label class="control-label col-md-3">Correo:</label> 
          <span> <?= $_SESSION['email']; ?></span>
        </div>
        <h4>Datos Usuarios</h4>
        <div class="form-group">
          <label class="control-label col-md-3">Rol:</label> <span> <?= $_SESSION['rol_name']; ?></span>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">Usuario:</label> <span> <?= $_SESSION['user']; ?></span>
        </div>
        <h4>Cambiar Contraseña</h4>
        <form class="form-horizontal" action="" method="post" name="frmChangePass" id="frmChangePass">
          <div class="form-group">
            <input class="form-control" type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña Actual" required>
          </div>
          <div class="form-group">
            <input class="form-control newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva Contraseña" required>
          </div>
          <div class="form-group">
            <input class="form-control newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar Contraseña" required>
          </div>
          <div class="alertChangePass" style="display: none;">
          </div>
          <center>
            <button type="submit" class="btn btn-primary"></i> Cambiar Contraseña</button>
          </center>
        </div>
      </form>
    </div> 
  </div> 

  <?php if ($_SESSION['rol'] == 1) {?>


    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title">Datos de la Empresa</h3>
        <div class="tile-body">
          <div class="logoEmpresa">
            <img src="img/logoEmpresa.png" width="400" height="300">
          </div>

          <form class="form-horizontal" action="" method="post" name="frmEmpresa" id="frmEmpresa">
            <input type="hidden" name="action" value="updateDataEmpresa">
            <div class="form-group row">
              <label class="control-label col-md-3">N° Identificación:</label>
              <div class="col-md-8">  
                <input class="form-control" type="text" name="txtNit" placeholder="N° de Identificación de la Empresa" value="<?= $nit; ?>" required="">
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">Nombre:</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la Empresa" value="<?= $nombreEmpresa; ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">Razón Social:</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtRSocial" id="txtRSocial" placeholder="Razón Social" value="<?= $razonSocial; ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">Teléfono:</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de teléfono" value="<?= $telEmpresa ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">Correo Eléctronico:</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo Eléctronico" value="<?= $emailEmpresa; ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">Dirección:</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la Empresa" value="<?= $dirEmpresa; ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-md-3">IVA (%):</label> 
              <div class="col-md-8">
                <input class="form-control" type="text" name="txtIva" id="txtIva" placeholder="Impuesto al Valor Agregado (IVA)" value="<?= $iva; ?>" required>
              </div>
            </div>
            <div class="alertFormEmpresa" style="display: none;"></div>
            <center>
              <button type="submit" class="btn btn-primary"></i> Guardar Datos</button>
            </center>
          </div>
        </form>
      <?php } ?>
    </div>
  </div>
</div>
</div> 
</main>

<?php 
include "includes/footer_admin.php";
?>
