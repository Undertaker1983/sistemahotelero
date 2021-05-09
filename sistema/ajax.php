<?php 
include "../conexion.php";
session_start();

if (!empty($_POST)) 
{

//extraer datos del orden
 if ($_POST['action'] == 'infOrden') 
 {
  $ordenes_id = $_POST['orden'];
  $queryord = mysqli_query($conection,"SELECT o.idorden,o.idpersona,p.idpersona,p.nombre FROM ordenes o 
    INNER JOIN personas p ON o.idpersona = p.idpersona
    WHERE o.idorden = $ordenes_id AND o.status = 1");
  mysqli_close($conection);
  $result4 = mysqli_num_rows($queryord);
  if ($result4 > 0) {
    $data4 = mysqli_fetch_assoc($queryord);
    echo json_encode($data4,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos del proveedor
if ($_POST['action'] == 'infoProveedor') 
{
  $proveedor_id = $_POST['provee'];
  $querypr = mysqli_query($conection,"SELECT idproveedor,nombre_proveedor FROM proveedor
    WHERE idproveedor = $proveedor_id AND status = 1");
  mysqli_close($conection);
  $result3 = mysqli_num_rows($querypr);
  if ($result3 > 0) {
    $data3 = mysqli_fetch_assoc($querypr);
    echo json_encode($data3,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos del piso
if ($_POST['action'] == 'infoPiso') 
{
  $piso_id = $_POST['pso'];
  $querypi = mysqli_query($conection,"SELECT idpiso,nombre_piso FROM pisos
    WHERE idpiso = $piso_id AND estado = 1");
  mysqli_close($conection);
  $resultp = mysqli_num_rows($querypi);
  if ($resultp > 0) {
    $datap = mysqli_fetch_assoc($querypi);
    echo json_encode($datap,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos de la categoria
if ($_POST['action'] == 'infoCategoria') 
{
  $categoria_id = $_POST['cat'];
  $querycat = mysqli_query($conection,"SELECT idcategoria,nombre_categoria FROM categorias
    WHERE idcategoria = $categoria_id AND estado = 1");
  mysqli_close($conection);
  $resultc = mysqli_num_rows($querycat);
  if ($resultc > 0) {
    $datac = mysqli_fetch_assoc($querycat);
    echo json_encode($datac,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos de la Habitacion
if ($_POST['action'] == 'infoHabitacion') 
{
  $habitacion_id = $_POST['hab'];
  $queryhab = mysqli_query($conection,"SELECT h.idhabitacion,h.nombre_habitacion,h.detalles,h.precio,h.estado,p.idpiso,p.nombre_piso,c.idcategoria,c.nombre_categoria FROM habitaciones h INNER JOIN
    pisos p ON h.idpiso = p.idpiso INNER JOIN
    categorias c ON h.idcategoria = c.idcategoria 
    WHERE h.idhabitacion = $habitacion_id AND h.estado = 1");
  mysqli_close($conection);
  $resulth = mysqli_num_rows($queryhab);
  if ($resulth > 0) {
    $datah = mysqli_fetch_assoc($queryhab);
    echo json_encode($datah,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}





//extraer datos del usuario
if ($_POST['action'] == 'infoUsuario') 
{
  $user_id = $_POST['usuario'];
  $queryus = mysqli_query($conection,"SELECT u.idusuario,u.nombre,u.usuario,r.rol
    FROM usuario u
    INNER JOIN
    rol r
    ON u.rol = r.idrol
    WHERE u.idusuario = $user_id AND estatus = 1");
  mysqli_close($conection);
  $result2 = mysqli_num_rows($queryus);
  if ($result2 > 0) {
    $data2 = mysqli_fetch_assoc($queryus);
    echo json_encode($data2,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos tipo de personas
if ($_POST['action'] == 'infoTipo') 
{
  $tipo_id = $_POST['person'];
  $queryt = mysqli_query($conection,"SELECT idtipo,tipo_persona FROM tipo_persona WHERE status = 1 ORDER BY idtipo ASC");
  mysqli_close($conection);
  $resultt = mysqli_num_rows($queryt);
  if ($resultt > 0) {
    $datat = mysqli_fetch_assoc($queryt);
    echo json_encode($datat,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}

//extraer datos del cliente
if ($_POST['action'] == 'infoPersona') 
{
  $persona_id = $_POST['persona'];
  $querycl = mysqli_query($conection,"SELECT p.idpersona,p.nombre,p.cedula,p.direccion,p.correo,p.idtipo,tp.idtipo,tp.tipo FROM personas p
    INNER JOIN tipo_persona tp
    ON p.idtipo = tp.idtipo
    WHERE p.idpersona=$persona_id AND status = 1");
  mysqli_close($conection);
  $result1 = mysqli_num_rows($querycl);
  if ($result1 > 0) {
    $data1 = mysqli_fetch_assoc($querycl);
    echo json_encode($data1,JSON_UNESCAPED_UNICODE);
    exit;
  }

  echo 'error';
  exit;
}




  	//extraer datos del producto
if ($_POST['action'] == 'infoProducto') 
{
  $producto_id = $_POST['producto'];
  $query = mysqli_query($conection,"SELECT codproducto,descripcion,existencia,precio FROM producto WHERE codproducto=$producto_id AND status = 1");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0) {
   $data = mysqli_fetch_assoc($query);
   echo json_encode($data,JSON_UNESCAPED_UNICODE);
   exit;
 }

 echo 'error';
 exit;
}

    //Agregar productos a la entrada
if ($_POST['action'] == 'addProduct') 
{  
  if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) 
  {
    $cantidad     = $_POST['cantidad'];
    $precio       = $_POST['precio'];
    $producto_id  = $_POST['producto_id'];
    $usuario_id   = $_SESSION['idUser'];

    $query_insert = mysqli_query($conection,"INSERT INTO entradas (codproducto,cantidad,precio,usuario_id) VALUES ($producto_id,$cantidad,$precio,$usuario_id)");

    if ($query_insert) 
    {
            # ejecutar procedimiento almmacenado
      $query_upd = mysqli_query($conection,"CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");
      $result_pro = mysqli_num_rows($query_upd);
      if ($result_pro > 0) 
      {
        $data =mysqli_fetch_assoc($query_upd);
        $data['producto_id']= $producto_id;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
    }else{
      echo 'error';
    }    
    mysqli_close($conection);
  }
  else{
    echo 'error';
  } 
  exit;
}

//Eliminar Alojamiento
if ($_POST['action'] == 'delAlojamiento') 
{  
  if (empty($_POST['estadia_id']) || !is_numeric($_POST['estadia_id'])){
    echo 'error'; 
  }else{

    $idalojamiento = $_POST['estadia_id'];
    $query_delete = mysqli_query($conection,"UPDATE alojamiento SET estado = 0 WHERE idalojamiento = $idalojamiento ");
    mysqli_close($conection);
    if($query_delete){
         //header("location: lista_habitaciones.php");
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

 //Eliminar Habitacion
if ($_POST['action'] == 'delHabitacion') 
{  
  if (empty($_POST['habitacion_id']) || !is_numeric($_POST['habitacion_id'])){
    echo 'error'; 
  }else{

    $idhabitacion = $_POST['habitacion_id'];
    $query_delete = mysqli_query($conection,"UPDATE habitaciones SET estado = 0 WHERE idhabitacion = $idhabitacion ");
    mysqli_close($conection);
    if($query_delete){
         //header("location: lista_habitaciones.php");
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Eliminar Categoria
if ($_POST['action'] == 'delCategoria') 
{  
  if (empty($_POST['categoria_id']) || !is_numeric($_POST['categoria_id'])){
    echo 'error'; 
  }else{

    $idcategoria = $_POST['categoria_id'];
    $query_delete = mysqli_query($conection,"UPDATE categorias SET estado = 0 WHERE idcategoria = $idcategoria ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Eliminar Piso
if ($_POST['action'] == 'delPiso') 
{  
  if (empty($_POST['piso_id']) || !is_numeric($_POST['piso_id'])){
    echo 'error'; 
  }else{

    $idpiso = $_POST['piso_id'];
    $query_delete = mysqli_query($conection,"UPDATE pisos SET estado = 0 WHERE idpiso = $idpiso");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Finalizar Mantenimiento

if ($_POST['action'] == 'finMantenimiento') 
{  
  if (empty($_POST['habitacion_id']) || !is_numeric($_POST['habitacion_id'])){
    echo 'error'; 
  }else{
    $condicion  = "Disponible";
    $idhabitacion = $_POST['habitacion_id'];
    $query_delete = mysqli_query($conection,"UPDATE habitaciones SET condicion = '$condicion' WHERE idhabitacion = $idhabitacion ");
    mysqli_close($conection);
    if($query_delete){
         //header("location: lista_habitaciones.php");
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  
//Activar Limpieza

if ($_POST['action'] == 'actLimpieza') 
{  
  if (empty($_POST['habitacion_id']) || !is_numeric($_POST['habitacion_id'])){
    echo 'error'; 
  }else{
    $condicion  = "Limpieza";
    $idhabitacion = $_POST['habitacion_id'];
    $query_delete = mysqli_query($conection,"UPDATE habitaciones SET condicion = '$condicion' WHERE idhabitacion = $idhabitacion ");
    mysqli_close($conection);
    if($query_delete){
         //header("location: lista_habitaciones.php");
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  


 //Finalizar Limpieza

if ($_POST['action'] == 'lmpHabitacion') 
{  
  if (empty($_POST['habitacion_id']) || !is_numeric($_POST['habitacion_id'])){
    echo 'error'; 
  }else{
    $condicion  = "Disponible";
    $idhabitacion = $_POST['habitacion_id'];
    $query_delete = mysqli_query($conection,"UPDATE habitaciones SET condicion = '$condicion' WHERE idhabitacion = $idhabitacion ");
    mysqli_close($conection);
    if($query_delete){
         //header("location: lista_habitaciones.php");
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  
//Eliminar Orden

if ($_POST['action'] == 'delOrden') 
{  
  if (empty($_POST['orden_id']) || !is_numeric($_POST['orden_id'])){
    echo 'error'; 
  }else{

    $idordenes = $_POST['orden_id'];
    $query_delete = mysqli_query($conection,"UPDATE ordenes SET status = 0 WHERE idorden = $idordenes ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Eliminar Proveedor

if ($_POST['action'] == 'delProveedor') 
{  
  if (empty($_POST['proveedor_id']) || !is_numeric($_POST['proveedor_id'])){
    echo 'error'; 
  }else{

    $idproveedores = $_POST['proveedor_id'];
    $query_delete = mysqli_query($conection,"UPDATE proveedor SET status = 0 WHERE idproveedor = $idproveedores ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  


//Eliminar Usuario

if ($_POST['action'] == 'delUsuario') 
{  
  if (empty($_POST['usuario_id']) || !is_numeric($_POST['usuario_id'])){
    echo 'error'; 
  }else{

    $idusuarios = $_POST['usuario_id'];
    $query_delete = mysqli_query($conection,"UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuarios ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Eliminar Cliente

if ($_POST['action'] == 'delPersona') 
{  
  if (empty($_POST['persona_id']) || !is_numeric($_POST['persona_id'])){
    echo 'error'; 
  }else{

    $idpersona = $_POST['persona_id'];
    $query_delete = mysqli_query($conection,"UPDATE personas SET status = 0 WHERE idpersona = $idpersona ");
    mysqli_close($conection);
    if($query_delete){
     echo 'ok';
   }else{
     echo 'error';
   }

 }
 echo 'error';
 exit;
}  

//Eliminar Producto

if ($_POST['action'] == 'delProduct') 
{  
  if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
    echo 'error'; 
  }else{

    $idproducto = $_POST['producto_id'];
    $query_delete = mysqli_query($conection,"UPDATE producto SET status = 0 WHERE codproducto = $idproducto ");
    mysqli_close($conection);
    if($query_delete){
      echo 'ok';
    }else{
      echo 'error';
    }

  }
  echo 'error';
  exit;
}
  //Buscar Cliente
if ($_POST['action'] == 'searchCliente') 
{
 if (!empty($_POST['cliente'])){
   $nit = $_POST['cliente'];

   $query = mysqli_query($conection,"SELECT * FROM personas WHERE cedula like '$nit' AND status = 1");
   mysqli_close($conection);
   $result = mysqli_num_rows($query);

   $data = '';
   if ($result > 0) 
   {
    $data = mysqli_fetch_assoc($query);

  }else{
    $data = 0;
  }
  echo json_encode($data,JSON_UNESCAPED_UNICODE);
}   
exit;
}



//Buscar Cliente x nombre
if ($_POST['action'] == 'searchCliente2') 
{
 if (!empty($_POST['cliente'])){
   $ncliente = mysqli_real_escape_string($conection,($_POST['cliente']));
        //$ncliente = $_POST['cliente'];

   $query_nombre = mysqli_query($conection,"SELECT * FROM personas WHERE nombre like '$ncliente' AND status = 1");
   mysqli_close($conection);
   $result_nombre = mysqli_num_rows($query_nombre);

   $data = '';
   if ($result_nombre > 0) 
   {
    $data = mysqli_fetch_assoc($query_nombre);

  }else{
    $data = 0;
  }
  echo json_encode($data,JSON_UNESCAPED_UNICODE);
}   
exit;
}  






//Registrar clientes --Ventas--
if ($_POST['action'] == 'addCliente'){
  $nit           = $_POST['nit_cliente'];
  $nombre       = $_POST['nom_cliente'];
  $telefono     = $_POST['tel_cliente'];
  $direccion    = $_POST['dir_cliente'];
  $correo       = $_POST['cor_cliente'];
  $idtipo       = $_POST['idtipo'];
  $usuario_id   = $_SESSION['idUser'];

  $query_insert = mysqli_query($conection,"INSERT INTO personas(cedula,nombre,telefono,direccion,correo,idtipo,usuario_id) VALUES('$nit','$nombre','$telefono','$direccion','$correo','1','$usuario_id')");

  if($query_insert){
    $codCliente = mysqli_insert_id($conection);
    $msg = $codCliente;
  }else{
    $msg = 'error';
  }
  mysqli_close($conection);
  echo $msg;
  exit;
}


//Registrar Pisos--
if ($_POST['action'] == 'addPiso'){
  $nombre       = $_POST['nombre_piso'];

  $query_insert = mysqli_query($conection,"INSERT INTO pisos(nombre_piso,estado) VALUES('$nombre','1')");

  if($query_insert){
    $codPiso = mysqli_insert_id($conection);
    $msg = $codPiso;
  }else{
    $msg = 'error';
  }
  mysqli_close($conection);
  echo $msg;
  exit;
}            
//Registrar Categorias--
if ($_POST['action'] == 'addCategoria'){
  $nombre       = $_POST['nombre_categoria'];

  $query_insert = mysqli_query($conection,"INSERT INTO categorias(nombre_categoria,estado) VALUES('$nombre','1')");

  if($query_insert){
    $codCategoria = mysqli_insert_id($conection);
    $msg = $codCategoria;
  }else{
    $msg = 'error';
  }
  mysqli_close($conection);
  echo $msg;
  exit;
} 

//Editar Habitaciones--
if ($_POST['action'] == 'editarHabitacion')
{    
  if(!empty($_POST))
  {
    $alert='';
    if (empty($_POST['nombre_habitacion']))
    {
      $alert='<p class="msg_error">Los campos son obligatorios.</p>';
    }else{
      $idhabitacion       = $_POST['id'];
      $idpiso             = $_POST['idpiso'];
      $idcategoria        = $_POST['idcategoria'];
      $nombre_habitacion  = $_POST['nombre_habitacion'];  
      $detalles           = $_POST['detalles'];
      $precio             = $_POST['precio'];          
      $query_upd = mysqli_query($conection,"UPDATE habitaciones SET idpiso = $idpiso, idcategoria = $idcategoria, nombre_habitacion = '$nombre_habitacion', detalles = '$detalles', precio = '$precio' WHERE idhabitacion = $idhabitacion");

      if($query_upd){
        $alert='<p class="msg_save">Registro actualizado correctamente.</p>';
      }else{
        $alert='<p class="msg_error">Error al actualizar el registro.</p>';
      }

    }
  }
}      

//Editar Categorias--
if ($_POST['action'] == 'editarCategoria')
{    
  if(!empty($_POST))
  {
    $alert='';
    if (empty($_POST['nombre_categoria']))
    {
      $alert='<p class="msg_error">Los campos son obligatorios.</p>';
    }else{
      $idcategoria       = $_POST['id'];
      $nombre_categoria  = $_POST['nombre_categoria'];            
      $query_upd = mysqli_query($conection,"UPDATE categorias SET nombre_categoria = '$nombre_categoria' WHERE idcategoria = $idcategoria");

      if($query_upd){
        $alert='<p class="msg_save">Registro actualizado correctamente.</p>';
      }else{
        $alert='<p class="msg_error">Error al actualizar el registro.</p>';
      }

    }
  }
}      

//Editar Pisos--
if ($_POST['action'] == 'editarPiso')
{    
  if(!empty($_POST))
  {
    $alert='';
    if (empty($_POST['nombre_piso']))
    {
      $alert='<p class="msg_error">Los campos son obligatorios.</p>';
    }else{
      $idpiso       = $_POST['id'];
      $nombre_piso  = $_POST['nombre_piso'];            
      $query_upd = mysqli_query($conection,"UPDATE pisos SET nombre_piso = '$nombre_piso' WHERE idpiso = $idpiso");

      if($query_upd){
        $alert='<p class="msg_save">Registro actualizado correctamente.</p>';
      }else{
        $alert='<p class="msg_error">Error al actualizar el piso.</p>';
      }

    }
  }
}                                 

//Editar Clientes--
if ($_POST['action'] == 'editarCliente')
{    
  if(!empty($_POST))
  {
    $alert='';
    if (empty($_POST['nombre']))
    {
      $alert='<p class="msg_error">Los campos son obligatorios.</p>';
    }else{
      $idpersona          = $_POST['id'];
      $idtipo             = $_POST['idtipo'];
      $nombre             = $_POST['nombre'];
      $telefono           = $_POST['telefono'];  
      $direccion          = $_POST['direccion'];
      $correo             = $_POST['correo'];          
      $query_upd = mysqli_query($conection,"UPDATE personas SET idpersona = $idpersona, idtipo = $idtipo, nombre = '$nombre', telefono = '$telefono', direccion = '$direccion' correo = '$correo'WHERE idpersona = $idpersona");

      if($query_upd){
        $alert='<p class="msg_save">Registro actualizado correctamente.</p>';
      }else{
        $alert='<p class="msg_error">Error al actualizar el registro.</p>';
      }

    }
  }
} 

       // Agregar producto al detalle temporal 
if ($_POST['action'] == 'addProductoDetalle') 
{
  if (empty($_POST['producto']) || empty($_POST['cantidad'])) 
  {
    echo 'error';
  }else{
    $codproducto = $_POST['producto'];
    $cantidad    = $_POST['cantidad'];
    $token      = md5($_SESSION['idUser']);

    $query_iva  = mysqli_query($conection,"SELECT iva FROM configuracion");
    $result_iva = mysqli_num_rows($query_iva);

    $query_detalle_temp = mysqli_query($conection,"CALL add_detalle_temp($codproducto,$cantidad,'$token')");
    $result =  mysqli_num_rows($query_detalle_temp);

    $detalleTabla = '';
    $sub_total    = 0;
    $iva          = 0;
    $total        = 0;
    $arrayData    =  array();

    if ($result > 0) {
      if ($result_iva > 0) {
        $info_iva = mysqli_fetch_assoc($query_iva);
        $iva = $info_iva['iva'];

      }
      while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
        $precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);
        $detalleTabla .= '<tr>
        <td>'.$data['codproducto'].'</td>
        <td colspan="2">'.$data['descripcion'].'</td>
        <td class="textcenter">'.$data['cantidad'].'</td>
        <td class="textright">'.$data['precio_venta'].'</td>
        <td class="textright">'.$precioTotal.'</td>
        <td class="">
        <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i></a>
        </td> 
        </tr>';


      }
      $sniva = round(1 + ($iva / 100),2);
      $impuesto = round($sub_total / $sniva, 2);
      $tl_sniva = round($sub_total - $impuesto, 2);
      $total = round($tl_sniva + $impuesto, 2);

      $detalleTotales = '<tr>
      <td colspan="5" class="textright1">SUBTOTAL.</td>
      <td class="textright">'.$impuesto.'</td>
      </tr>
      <tr>
      <td colspan="5" class="textright1">IVA ('.$iva.'%)</td>
      <td class="textright">'.$tl_sniva.'</td>
      </tr>
      <tr>
      <td colspan="5" class="textright2">TOTAL.</td>
      <td class="textright">'.$total.'</td>
      </tr>';
      $arrayData['detalle'] = $detalleTabla;
      $arrayData['totales'] = $detalleTotales;  

      echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);                  

    }else{
      echo 'error';
    }
    mysqli_close($conection);  
  }
  exit;
}


           // Extrae datos del detalle_temp 
if ($_POST['action'] == 'searchForDetalle') 
{
  if (empty($_POST['user'])) 
  {
    echo 'error';
  }else{

    $token      = md5($_SESSION['idUser']);

    $query = mysqli_query($conection,"SELECT tmp.correlativo,tmp.token_user,tmp.cantidad,tmp.precio_venta, p.codproducto,p.descripcion FROM detalle_temp tmp 
      INNER JOIN producto p
      ON tmp.codproducto = p.codproducto
      WHERE token_user = '$token'");

    $result =  mysqli_num_rows($query);


    $query_iva  = mysqli_query($conection,"SELECT iva FROM configuracion");
    $result_iva = mysqli_num_rows($query_iva);



    $detalleTabla = '';
    $sub_total    = 0;
    $iva          = 0;
    $total        = 0;
    $arrayData    =  array();

    if ($result > 0) {
      if ($result_iva > 0) {
        $info_iva = mysqli_fetch_assoc($query_iva);
        $iva = $info_iva['iva'];

      }
      while ($data = mysqli_fetch_assoc($query)) {
        $precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
        $sub_total = round($sub_total + $precioTotal, 2);
        $total = round($total + $precioTotal, 2);
        $detalleTabla .= ' <tr>
        <td>'.$data['codproducto'].'</td>
        <td colspan="2">'.$data['descripcion'].'</td>
        <td class="textcenter">'.$data['cantidad'].'</td>
        <td class="textright">'.$data['precio_venta'].'</td>
        <td class="textright">'.$precioTotal.'</td>
        <td class="">
        <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i></a>
        </td> 
        </tr>';


      }
      $sniva = round(1 + ($iva / 100),2);
      $impuesto = round($sub_total / $sniva, 2);
      $tl_sniva = round($sub_total - $impuesto, 2);
      $total = round($tl_sniva + $impuesto, 2);

      $detalleTotales = '<tr>
      <td colspan="5" class="textright">SUBTOTAL.</td>
      <td class="textright">'.$impuesto.'</td>
      </tr>
      <tr>
      <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
      <td class="textright">'.$tl_sniva.'</td>
      </tr>
      <tr>
      <td colspan="5" class="textright">TOTAL.</td>
      <td class="textright">'.$total.'</td>
      </tr>';
      $arrayData['detalle'] = $detalleTabla;
      $arrayData['totales'] = $detalleTotales;  

      echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);                  

    }else{
      echo 'error';
    }
    mysqli_close($conection);  
  }
  exit;
}   

          //Eliminar producto del detalle  
if ($_POST['action'] == 'delProductoDetalle') 
{
 if (empty($_POST['id_detalle'])) 
 {
  echo 'error';
}else{

  $id_detalle = $_POST['id_detalle'];
  $token      = md5($_SESSION['idUser']);

  $query_iva  = mysqli_query($conection,"SELECT iva FROM configuracion");
  $result_iva = mysqli_num_rows($query_iva);

  $query_detalle_temp =  mysqli_query($conection,"CALL del_detalle_temp($id_detalle,'$token')");

  $result =  mysqli_num_rows($query_detalle_temp);

  $detalleTabla = '';
  $sub_total    = 0;
  $iva          = 0;
  $total        = 0;
  $arrayData    =  array();

  if ($result > 0) {
    if ($result_iva > 0) {
      $info_iva = mysqli_fetch_assoc($query_iva);
      $iva = $info_iva['iva'];

    }
    while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
      $precioTotal = round($data['cantidad'] * $data['precio_venta'],2);
      $sub_total = round($sub_total + $precioTotal, 2);
      $total = round($total + $precioTotal, 2);
      $detalleTabla .= ' <tr>
      <td>'.$data['codproducto'].'</td>
      <td colspan="2">'.$data['descripcion'].'</td>
      <td class="textcenter">'.$data['cantidad'].'</td>
      <td class="textright">'.$data['precio_venta'].'</td>
      <td class="textright">'.$precioTotal.'</td>
      <td class="">
      <a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="fa fa-trash"></i></a>
      </td> 
      </tr>';


    }
    $sniva = round(1 + ($iva / 100),2);
    $impuesto = round($sub_total / $sniva, 2);
    $tl_sniva = round($sub_total - $impuesto, 2);
    $total = round($tl_sniva + $impuesto, 2);

    $detalleTotales = '<tr>
    <td colspan="5" class="textright1">SUBTOTAL.</td>
    <td class="textright">'.$impuesto.'</td>
    </tr>
    <tr>
    <td colspan="5" class="textright1">IVA ('.$iva.'%)</td>
    <td class="textright">'.$tl_sniva.'</td>
    </tr>
    <tr>
    <td colspan="5" class="textright2">TOTAL.</td>
    <td class="textright">'.$total.'</td>
    </tr>';
    $arrayData['detalle'] = $detalleTabla;
    $arrayData['totales'] = $detalleTotales;  

    echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);                  

  }else{
    echo 'error';
  }
  mysqli_close($conection);  
}
exit;
} 

    //Anular Venta
if ($_POST['action'] == 'anularVenta') {
  $token  = md5($_SESSION['idUser']);
  $query_del = mysqli_query($conection,"DELETE FROM detalle_temp WHERE token_user = '$token'");
  mysqli_close($conection);
  if ($query_del) {
    echo 'ok';
  }else{
    echo 'error';
  }
  exit;
}        
 //Anular Cotizacion
if ($_POST['action'] == 'anularCotizacion') {
  $token  = md5($_SESSION['idUser']);
  $query_del = mysqli_query($conection,"DELETE FROM detalle_temp WHERE token_user = '$token'");
  mysqli_close($conection);
  if ($query_del) {
    echo 'ok';
  }else{
    echo 'error';
  }
  exit;
}        

 //Procesar Consumo Habitacion
if ($_POST['action'] == 'procesarConsumo') {      

  if (empty($_POST['idalojamiento'])) {
   $alert='<p class="msg_error">El campo es obligatorio.</p>';
  }else{
    $idalojamiento = $_POST['idalojamiento'];
  }
  $token = md5($_SESSION['idUser']);
  $usuario = $_SESSION['idUser'];
  //$estado_pago = $_POST['estado_pago'];

  $query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = '$token'");
  $result = mysqli_num_rows($query);
  if ($result > 0) {
    $query_procesar = mysqli_query($conection,"CALL procesar_consumo($usuario,$idalojamiento,'$token')");
    $result_detalle = mysqli_num_rows($query_procesar);
    //$updconsumo_ = mysqli_query($conection,"UPDATE ");
    if ($result_detalle  > 0) {
      $data = mysqli_fetch_assoc($query_procesar);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }else{
      echo 'error';
    }
  }else{
    echo 'error';
  }

  mysqli_close($conection);
  exit;
}


      //Procesar Venta
if ($_POST['action'] == 'procesarVenta') {      

  if (empty($_POST['codcliente'])) {
    $codcliente = 387;
  }else{
    $codcliente = $_POST['codcliente'];
  }
  $token = md5($_SESSION['idUser']);
  $usuario = $_SESSION['idUser'];

  $query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = '$token'");
  $result = mysqli_num_rows($query);
  if ($result > 0) {
    $query_procesar = mysqli_query($conection,"CALL procesar_venta($usuario,$codcliente,'$token')");
    $result_detalle = mysqli_num_rows($query_procesar);

    if ($result_detalle  > 0) {
      $data = mysqli_fetch_assoc($query_procesar);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }else{
      echo 'error';
    }
  }else{
    echo 'error';
  }

  mysqli_close($conection);
  exit;
}

//Procesar Cotizacion
if ($_POST['action'] == 'procesarCotizacion') {      

  if (empty($_POST['codcliente'])) {
    $codcliente = 387;
  }else{
    $codcliente = $_POST['codcliente'];
  }
  $token = md5($_SESSION['idUser']);
  $usuario = $_SESSION['idUser'];

  $query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = '$token'");
  $result = mysqli_num_rows($query);
  if ($result > 0) {
    $query_procesar = mysqli_query($conection,"CALL procesar_cotizacion($usuario,$codcliente,'$token')");
    $result_detalle = mysqli_num_rows($query_procesar);

    if ($result_detalle  > 0) {
      $data = mysqli_fetch_assoc($query_procesar);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }else{
      echo 'error';
    }
  }else{
    echo 'error';
  }

  mysqli_close($conection);
  exit;
}


      //Info Factura
if ($_POST['action'] == 'infoFactura') {
  if (!empty($_POST['nofactura'])) {
    $nofactura = $_POST['nofactura'];
    $query = mysqli_query($conection,"SELECT * FROM factura WHERE nofactura = '$nofactura' AND status = 1");

    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }
  }
  echo "error";
  exit;
}

//Info Cotizacion
if ($_POST['action'] == 'infoCotizacion') {
  if (!empty($_POST['nocotizacion'])) {
    $nofactura = $_POST['nocotizacion'];
    $query = mysqli_query($conection,"SELECT * FROM cotizacion WHERE nocotizacion = '$nofactura' AND status = 1");

    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }
  }
  echo "error";
  exit;
}


      //Anular Factura
if ($_POST['action'] == 'anularFactura') {
  if (!empty($_POST['noFactura'])) {
    $noFactura = $_POST['noFactura'];

    $query_anular = mysqli_query($conection,"CALL anular_factura($noFactura)");
    mysqli_close($conection);
    $result = mysqli_num_rows($query_anular);
    if ($result > 0) {
      $data = mysqli_fetch_assoc($query_anular);
      echo json_encode($data,JSON_UNESCAPED_UNICODE);
      exit;
    }
  }
  echo "error";
  exit;
}


      //Cambiar contraseña
if ($_POST['action'] == 'changePassword') {

  if (!empty($_POST['passActual']) && !empty($_POST['passNuevo'])) {
    $password = md5($_POST['passActual']);
    $newPass  = md5($_POST['passNuevo']);
    $idUser   = $_SESSION['idUser'];
    $code     = '';
    $msg      = '';
    $arrData  = array();

    $query_user = mysqli_query($conection,"SELECT * FROM usuario
      WHERE clave = '$password' AND idusuario = $idUser");
    $result = mysqli_num_rows($query_user);

    if ($result > 0) {
      $query_update = mysqli_query($conection,"UPDATE usuario SET clave = '$newPass' WHERE idusuario = $idUser");
                //mysql_close($conection);

      if ($query_update) {
        $code = '00';
        $msg = "Su contraseña se ha actualizado con éxito.";
      }else{
        $code = '2';
        $msg = "No es posible cambiar su contraseña.";
      }
    }else{
      $code = '1';
      $msg = "La contraseña actual es incorrecta.";
    }
    $arrData = array('cod' => $code, 'msg' => $msg);
    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);

  }else{
    echo "error";
  }

  exit;
}

//Actualizar Datos Empresa
if ($_POST['action'] == 'updateDataEmpresa') {
  if (empty($_POST['txtNit']) || empty($_POST['txtNombre']) || empty($_POST['txtRSocial']) || empty($_POST['txtTelEmpresa']) || empty($_POST['txtEmailEmpresa']) || empty($_POST['txtDirEmpresa']) || empty($_POST['txtIva'])) {
    $code = '1';
    $msg = "Todos los campos son obligatorios";
  }else{
    $intNit       = $_POST['txtNit'];
    $strNombre    = $_POST['txtNombre'];
    $strRSocial   = $_POST['txtRSocial'];
    $intTel       = $_POST['txtTelEmpresa'];
    $strEmail     = $_POST['txtEmailEmpresa'];
    $strDir       = $_POST['txtDirEmpresa'];
    $strIva       = $_POST['txtIva']; 

    $queryUpd = mysqli_query($conection,"UPDATE configuracion SET cedula = '$intNit',nombre = '$strNombre',razon_social = '$strRSocial', telefono= '$intTel', email = '$strEmail', direccion = '$strDir', iva = '$strIva' WHERE id = 1");
    mysqli_close($conection);
    if ($queryUpd) {
      $code = '00';
      $msg = "Datos actualizados correctamente.";
    }else {
      $code = '2';
      $msg = "Error al actualizar datos.";
    }

  }
  $arrData = array('cod' => $code, 'msg' => $msg );
  echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
  exit;  
}      


}

exit;


?>