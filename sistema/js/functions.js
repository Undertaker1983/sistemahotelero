$(document).ready(function(){
    $('.btnMenu').click(function(e){
        e.preventDefault();
        if ($('nav').hasClass('viewMenu')) {
            $('nav').removeClass('viewMenu');
        }else{
            $('nav').addClass('viewMenu');
        }
    });
   
    $('nav ul li').click(function(){
        $('nav ul li ul').slideUp();
        $(this).children('ul').slideToggle();
    });

$(document).ready(function(){
    $('ul.tabs li a:first').addClass('active');
    $('.secciones article').hide();
    $('.secciones article:first').show();

    $('ul.tabs li a').click(function(){
        $('ul.tabs li a').removeClass('active');
        $(this).addClass('active');
        $('.secciones article').hide();

        var activeTab = $(this).attr('href');
        $(activeTab).show();
        return false;
    });
});

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

    });

//Modal for Editar Cliente
$('.edt_cliente').click(function(e) {
        e.preventDefault();
        var perso = $(this).attr('persona');
        var action = 'infoPersona';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,perso:perso},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                $('.modal-content').html('<form action="" method="post" name="form_edit_cliente" id="form_edit_cliente" onsubmit="event.preventDefault(); sendEditCliente();">'+
                '<div class="modal-header">'+
                '<h3 class="modal-title">Actualizar Cliente</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                /*'<div class="alert alertAddProduct"></div>'+*/
                '<input type="hidden" name="id" value="<?php echo $idpersona; ?>">'+
                '</div>'+
                '<div class="modal-body">'+
                '<label for="cedula" class="col-form-label">Número de Identificación</label>'+
                '<input type="text" name="cedula" id="cedula" placeholder="Numero de Identificación" value="'+info.cedula+'">'+
                '<label for="nombre" class="col-form-label">Nombre</label>'+
                '<input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="'+info.nombre+'">'+
                '<label for="telefono" class="col-form-label">Telefono</label>'+
                '<input type="text" name="telefono" id="telefono" placeholder="Telefono" value="'+info.telefono+'">'+
                '<label for="direccion" class="col-form-label">Direccion</label>'+
                '<input type="text" name="direccion" id="direccion" placeholder="Direccion" value="'+info.direccion+'">'+
                '<label for="correo" class="col-form-label">Correo electrónico</label>'+
                '<input type="email" name="correo" id="correo" placeholder="Correo electrónico" value="'+info.correo+'">'+
                '<input type="hidden" name="action" value="editarCliente" required>'+
                '</div>'+
                '<div class="modal-footer">'+
                '<button type="submit" class="btn_newsave"><i class="fas fa-save"></i> Guardar</button>'+
                '<a href="#" class="btn_ok closeModal" onclick="coloseModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                '</form>');
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });



        $('.modal').fadeIn();


    });

//Modal for Editar Habitacion
$('.edt_habitacion').click(function(e) {
    e.preventDefault();
    var hab = $(this).attr('hab');
    var action = 'infoHabitacion';
    var actpiso = 'infoPiso';

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,actpiso:actpiso,hab:hab},

        success: function(response){

            if (response != 'error') {
                var info = JSON.parse(response);
                $('.modal').html(
                    '<div class="modal-dialog">'+
                    '<div class="modal-content">'+
                    '<form action="" method="post" name="form_edit_habitacion" id="form_edit_habitacion" onsubmit="event.preventDefault(); sendEditarHabitacion();">'+
                    '<div class="modal-header headerUpdate">'+
                    '<h3 class="modal-title"> Actualizar Habitación</h3>'+
                    '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                    '</button>'+
                    '<div class="alert alertAddProduct"></div>'+
                    '</div>'+
                    '<div class="modal-body">'+
                    '<input type="hidden" name="id" value="'+info.idhabitacion+'">'+
                    '<div class="form-group">'+
                    '<label for="nombre" class="col-form-label">Nombre</label>'+
                    '<input type="text" class="form-control" name="nombre_habitacion" id="nombre_habitacion" placeholder="Nombre de Habitacion" value="'+info.nombre_habitacion+'">'+
                    '</div>'+
                    '<div class="form-group">'+
                    '<label for="piso" class="col-form-label">Piso</label>'+
                    '<select name="idpiso" class="form-control notItemOne">'+
                    '<option value="'+info.idpiso+'" selected>'+
                    '"'+info.nombre_piso+'"</option>'+
                    '</select>'+
                    '</div>'+
                    '<input type="hidden" name="action" value="editarHabitacion" required>'+
                    '</div>'+
                    '<div class="modal-footer">'+
                    '<button type="submit" class="btn btn-info btn_newsave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Actualizar</button>'+
                    '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                    '</form>'+
                    '</div>'+
                    '</div>'+
                    '</div>');
                
            }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

        });
    $('.modal').fadeIn();


});

//Modal for Editar Categoria
$('.edt_categoria').click(function(e) {
    e.preventDefault();
    var cat = $(this).attr('cat');
    var action = 'infoCategoria';

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,cat:cat},

        success: function(response){

            if (response != 'error') {
                var info = JSON.parse(response);
                $('.modal').html(
                    '<div class="modal-dialog">'+
                    '<div class="modal-content">'+
                    '<form action="" method="post" name="form_edit_categoria" id="form_edit_categoria" onsubmit="event.preventDefault(); sendEditarCategoria();">'+
                    '<div class="modal-header headerUpdate">'+
                    '<h3 class="modal-title"> Actualizar Categoria</h3>'+
                    '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                    '</button>'+
                    /*'<div class="alert alertAddProduct"></div>'+*/
                    '</div>'+
                    '<div class="modal-body">'+
                    '<input type="hidden" name="id" value="'+info.idcategoria+'">'+
                    '<label for="nombre" class="col-form-label">Nombre</label>'+
                    '<input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de Categoria" value="'+info.nombre_categoria+'">'+
                    '<input type="hidden" name="action" value="editarCategoria" required>'+
                    '</div>'+
                    '<div class="modal-footer">'+
                    '<button type="submit" class="btn btn-info"><i class="fa fa-fw fa-lg fa-check-circle"></i>Actualizar</button>'+
                    '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                    '</form>'+
                    '</div>'+
                    '</div>'+
                    '</div>');
                
            }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

        });
    $('.modal').fadeIn();


});

//Modal for Editar Piso
$('.edt_piso').click(function(e) {
        e.preventDefault();
        var pso = $(this).attr('pso');
        var action = 'infoPiso';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,pso:pso},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                $('.modal').html(
                '<div class="modal-dialog">'+
                '<div class="modal-content">'+    
                '<form action="" method="post" name="form_edit_piso" id="form_edit_piso" onsubmit="event.preventDefault(); sendEditPiso();">'+
                '<div class="modal-header headerUpdate">'+
                '<h3 class="modal-title"> Actualizar Piso</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                /*'<div class="alert alertAddProduct"></div>'+*/
                '</div>'+
                '<div class="modal-body">'+
                '<input type="hidden" name="id" value="'+info.idpiso+'">'+
                '<label for="nombre" class="col-form-label">Nombre</label>'+
                '<input type="text" class="form-control" name="nombre_piso" id="nombre_piso" placeholder="Nombre de Piso" value="'+info.nombre_piso+'">'+
                '<input type="hidden" name="action" value="editarPiso" required>'+
                '</div>'+
                '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-info"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>'+
                '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>');
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });

        $('.modal').fadeIn();

    });

//Modal for Add Cliente
$('.add_cliente').click(function(e) {
        e.preventDefault();
        //var prsona = $(this).attr('persona');
        //var action = 'infoPersona';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_cliente').serialize(),
            /*data: {action:action},*/
            success: function(response){
                
                if (response != 'error') {
                    //var info = JSON.parse(response);
                $('.modal').html(
                '<div class="modal-dialog modal-lg">'+
                '<div class="modal-content">'+    
                '<form action="" method="post" name="form_add_cliente" id="form_add_cliente" onsubmit="event.preventDefault(); sendDataCliente();">'+
                '<div class="modal-header headerRegister">'+
                '<h3 class="modal-title">Registrar Cliente</h1>'+
                /*'<div class="alert alertAddProduct"></div>'+*/
                '</div>'+
                '<div class="modal-body">'+
                '<div class="form-row">'+
                /*'<div class="form-group col-md-6">'+
                '<label for="tipo_contacto" class="col-form-label">Tipo de Contacto</label>'+
                '<select name="tipo_id" id="tipo_id" class="form-control notItemOne">'+
                '</select>'+
                /*'<select name="tipo_id" id="tipo_id" class="form-control notItemOne">'+
                '<option value="'+info.idtipo+'" selected>'+
                '"'+info.nombre+'"</option>'+
                 '</select>'+
                '</div>'+*/
                '<div class="form-group col-md-6">'+
                '<label for="cedula" class="col-form-label">Número de Identificación</label>'+
                '<input type="text" class="form-control" name="nit_cliente" id="nit_cliente" placeholder="Número de Identificación">'+
                '</div>'+
                '<div class="form-group col-md-6">'+
                '<label for="nombre" class="col-form-label">Nombre</label>'+
                '<input type="text" class="form-control" name="nom_cliente" id="nom_cliente" placeholder="Nombre completo">'+
                '</div>'+
                '</div>'+
                '<div class="form-row">'+
                '<div class="form-group col-md-6">'+
                '<label for="telefono" class="col-form-label">Teléfono</label>'+
                '<input type="text" class="form-control" name="tel_cliente" id="tel_cliente" placeholder="Teléfono">'+
                '</div>'+
                '<div class="form-group col-md-6">'+
                '<label for="correo" class="col-form-label">Correo electrónico</label>'+
                '<input type="email" class="form-control" name="cor_cliente" id="cor_cliente" placeholder="Correo electrónico">'+
                '</div>'+
                '</div>'+
                '<div class="form-row">'+
                '<label for="direccion" class="col-form-label">Dirección</label>'+
                '<textarea rows="2" class="form-control" name="dir_cliente" id="dir_cliente" placeholder="Dirección"></textarea>'+
                '<input type="hidden" name="action" value="addCliente" required>'+
                '</div>'+
                '</div>'+
                '<div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>'+
                '<a href="#" class="btn btn-secondary" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>');
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });



        $('.modal').fadeIn();


    });
/*Modal for Add Piso
$('.add_habitacion').click(function(e) {
        e.preventDefault();
        //var cliente = $(this).attr('product');
        //var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_habitacion').serialize(),

            success: function(response){
                
                if (response != 'error') {
                    //var info = JSON.parse(response);
                $('.modal').html(
                '<div class="modal-dialog headerRegister">'+
                '<div class="modal-content">'+ 
                '<form action="" method="post" name="form_add_habitacion" id="form_add_habitacion" onsubmit="event.preventDefault(); sendDataHabitacion();">'+
                '<div class="modal-header">'+
                '<h3 class="modal-title">Registrar Habitación</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                '<div class="alert alertAddProduct"></div>'+
                '</div>'+
                '<div class="modal-body">'+
                '<label for="nombre_habitacion" class="col-form-label">Nombre de Piso</label>'+
                '<input type="text" class="form-control" name="nombre_habitacion" id="nombre_habitacion" placeholder="Nombre de Habitación">'+
                '<input type="hidden" name="action" value="addHabitacion" required>'+
                '<div class="form-group">'+
                '<label for="piso" class="col-form-label">Piso</label>'+
                '<?php '+
                '$query_pisos = mysqli_query($conection, "SELECT * FROM pisos WHERE estado = 1 ORDER BY nombre_piso DESC");'+
                '$result_pisos = mysqli_num_rows($query_pisos);'+
                '?>'+
                '<select name="idpiso" id="idpiso" class="form-control">'+
                '<?php '+
                'if ($result_pisos > 0) {'+
                'while ($pisos = mysqli_fetch_array($query_pisos)) {'+
                '?>'+
                '<option value="<?php echo $pisos['+'idpiso'+']; ?>">'+
                '<?php echo $pisos['+'nombre_piso'+']; ?></option>'+
                '<?php'+
                '}'+
                '}'+
                '?>'+
                '</select>'+
                '</div>'+
                '</div>'+
                ' <div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary btn_newsave">Guardar</button>'+
                '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();">Cerrar</a>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>');
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });



        $('.modal').fadeIn();


    });*/

//Modal for Add Piso
$('.add_piso').click(function(e) {
        e.preventDefault();
        //var cliente = $(this).attr('product');
        //var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_piso').serialize(),

            success: function(response){
                
                if (response != 'error') {
                    //var info = JSON.parse(response);
                $('.modal').html(
                '<div class="modal-dialog">'+
                '<div class="modal-content">'+ 
                '<form action="" method="post" name="form_add_piso" id="form_add_piso" onsubmit="event.preventDefault(); sendDataPiso();">'+
                '<div class="modal-header headerRegister">'+
                '<h3 class="modal-title">Registrar Piso</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                '<div class="alert alertAddProduct"></div>'+
                '</div>'+
                '<div class="modal-body">'+
                '<label for="nombre_piso" class="col-form-label">Nombre de Piso</label>'+
                '<input type="text" class="form-control" name="nombre_piso" id="nombre_piso" placeholder="Nombre de Piso">'+
                '<input type="hidden" name="action" value="addPiso" required>'+
                '</div>'+
                ' <div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary btn_newsave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>'+
                '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>');
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });



        $('.modal').fadeIn();


    });

//Modal for Add Categoria
$('.add_categoria').click(function(e) {
        e.preventDefault();
        //var cliente = $(this).attr('product');
        //var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_categoria').serialize(),

            success: function(response){
                
                if (response != 'error') {
                    //var info = JSON.parse(response);
                $('.modal').html(
                '<div class="modal-dialog">'+
                '<div class="modal-content">'+ 
                '<form action="" method="post" name="form_add_categoria" id="form_add_categoria" onsubmit="event.preventDefault(); sendDataCategoria();">'+
                '<div class="modal-header headerRegister">'+
                '<h3 class="modal-title">Registrar Categoria</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                /*'<div class="alert alertAddProduct"></div>'+*/
                '</div>'+
                '<div class="modal-body">'+
                '<label for="nombre_categoria" class="col-form-label">Nombre de Categoria</label>'+
                '<input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de Categoria">'+
                '<input type="hidden" name="action" value="addCategoria" required>'+
                '</div>'+
                ' <div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary btn_newsave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Registrar</button>'+
                '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                '</form>'+
                '</div>');
                
                
                }
                //location.reload();
            },
            error: function(error){
                console.log(error);

            }

            });



        $('.modal').fadeIn();


    });

//Modal for Add Product
$('.add_product').click(function(e) {
        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                $('.modal').html(
                '<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                '<div class="modal-dialog">'+
                '<div class="modal-content">'+ 
                '<div class="modal-header headerRegister">'+
                '<h3 class="modal-title">Agregar Producto</h3>'+
                '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>'+
                '</button>'+
                '</div>'+
                '<div class="modal-body">'+
                '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                '<input type="number" class="form-control" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required><br>'+
                '<input type="text" class="form-control" name="precio" id="txtPrecio" placeholder="Precio del Producto" required>'+
                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required>'+
                '<input type="hidden" name="action" value="addProduct" required>'+
                '<div class="alert alertAddProduct"></div>'+
                '</div>'+
                ' <div class="modal-footer">'+
                '<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>Agregar</button>'+
                '<a href="#" class="btn btn-secondary closeModal" onclick="coloseModal();"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>'+
                '</form>'+
                '</div>'+
                '</div>'+
                '</div>');
                }
            },
            error: function(error){
                console.log(error);
            }

            });



        $('.modal').fadeIn();

    });

//Modal for delete Orden
$('.del_orden').click(function(e) {
        e.preventDefault();
        var orden = $(this).attr('orden');
        var action = 'infOrden';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,orden:orden},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                   
                    //$('#producto_id').val(info.codproducto);
                    //$('.nameProducto').html(info.descripcion);
                    $('.bodyModal').html('<form action="" method="post" name="form_del_orden" id="form_del_orden" onsubmit="event.preventDefault(); delOrden();">'+
                '<h1><i class="fas fa-tools" style="font-size: 45pt;"></i><br> Eliminar Orden de Reparación</h1>'+
                '<p>¿Está seguro de eliminar el siguiente registro?</p>'+
                '<h2 class="nameProducto">'+info.idorden+'</h2><br>'+
                '<p>Cliente: '+info.nombre+'</p>'+
                '<input type="hidden" name="orden_id" id="orden_id" value="'+info.idorden+'" required>'+
                '<input type="hidden" name="action" value="delOrden" required>'+
                '<div class="alert alertAddProduct"></div>'+
                '<a href="#" class="btn_cancel" onclick="coloseModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>'+
                '</form>');
                }
            },
            error: function(error){
                console.log(error);
            }

            });



        $('.modal').fadeIn();

    });


//Modal for delete Proveedor
$('.del_proveedor').click(function(e) {
        e.preventDefault();
        var provee = $(this).attr('proveedor');
        var action = 'infoProveedor';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,provee:provee},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                   
                    //$('#producto_id').val(info.codproducto);
                    //$('.nameProducto').html(info.descripcion);
                    $('.bodyModal').html('<form action="" method="post" name="form_del_proveedor" id="form_del_proveedor" onsubmit="event.preventDefault(); delProveedor();">'+
                '<h1><i class="fas fa-truck" style="font-size: 45pt;"></i><br> Eliminar Proveedor</h1>'+
                '<p>¿Está seguro de eliminar el siguiente registro?</p>'+
                '<h2 class="nameProducto">'+info.nombre_proveedor+'</h2><br>'+
                '<input type="hidden" name="proveedor_id" id="proveedor_id" value="'+info.idproveedor+'" required>'+
                '<input type="hidden" name="action" value="delProveedor" required>'+
                '<div class="alert alertAddProduct"></div>'+
                '<a href="#" class="btn_cancel" onclick="coloseModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>'+
                '</form>');
                }
            },
            error: function(error){
                console.log(error);
            }

            });



        $('.modal').fadeIn();

    });

//Modal for delete Usuario
$('.del_usuario').click(function(e) {
        e.preventDefault();
        var usuario_id = $(this).attr('usuario_id');
        var action = 'delUsuario';

Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+usuario_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {usuario_id:usuario_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+usuario_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });        
            
    });


//Modal for delete habitacion
$('.del_habitacion').click(function(e) {
    e.preventDefault();
    var habitacion_id = $(this).attr('habitacion_id');
    var action = 'delHabitacion';
    
    Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+habitacion_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {habitacion_id:habitacion_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+habitacion_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
});



//Modal for delete categoria
$('.del_categoria').click(function(e) {
    e.preventDefault();
    var categoria_id = $(this).attr('categoria_id');
    var action = 'delCategoria';
    
   Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+categoria_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {categoria_id:categoria_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+categoria_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
            

});

//Modal for delete piso
$('.del_piso').click(function(e) {
    e.preventDefault();
    var piso_id = $(this).attr('piso_id');
    var action = 'delPiso';

    Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+piso_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {piso_id:piso_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+piso_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });

});

//Modal for delete Cliente
$('.del_persona').click(function(e) {
        e.preventDefault();
        var persona_id = $(this).attr('persona_id');
        var action = 'delPersona';

        Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+persona_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {persona_id:persona_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+persona_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });

    });

//Modal act mantenimiento habitacion
$('.fin_mantenimiento').click(function(e) {
    e.preventDefault();
    var habitacion_id = $(this).attr('habitacion_id');
    var action = 'finMantenimiento';
    
    Swal.fire({
                            title: 'Está seguro de finalizar el mantenimiento de la habitación?',
                            //text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, finalizar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {habitacion_id:habitacion_id, action:action},
                                    
                                    success: function(data){
                                    //$('.row'+habitacion_id).remove() ;
                                    window.location.reload();
                                    Swal.fire(
                                    'En mantenimiento!',
                                    'La Habitación estará disponible.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se realizó mantenimiento.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
});

//Modal factivar limpieza habitacion
$('.act_limpieza').click(function(e) {
    e.preventDefault();
    var habitacion_id = $(this).attr('habitacion_id');
    var action = 'actLimpieza';
    
    Swal.fire({
                            title: 'Realizar la limpieza de la habitación?',
                            //text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, realizar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {habitacion_id:habitacion_id, action:action},
                                    
                                    success: function(data){
                                    //$('.row'+habitacion_id).remove() ;
                                    window.location.reload();
                                    Swal.fire(
                                    'En Limpieza!',
                                    'La Habitación estará en limpieza.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se realizó la limpieza.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
});


//Modal for finalizar limpieza habitacion
$('.limp_habitacion').click(function(e) {
    e.preventDefault();
    var habitacion_id = $(this).attr('habitacion_id');
    var action = 'lmpHabitacion';
    
    Swal.fire({
                            title: 'Está seguro de finalizar la limpieza a la habitación?',
                            //text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, finalizar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {habitacion_id:habitacion_id, action:action},
                                    
                                    success: function(data){
                                    //$('.row'+habitacion_id).remove() ;
                                    window.location.reload();
                                    Swal.fire(
                                    'Finalizado!',
                                    'La Habitación quedará disponible.',
                                    'success'

                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se finalizó la limpieza.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });
});


//Modal for delete Product
$('.del_product').click(function(e) {
        e.preventDefault();
        var producto_id = $(this).attr('producto_id');
        var action = 'delProduct';

        Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+producto_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {producto_id:producto_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+producto_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });

    });

//Modal for delete alojamiento
$('.del_alojamiento').click(function(e) {
        e.preventDefault();
        var estadia_id = $(this).attr('estadia_id');
        var action = 'delAlojamiento';

        Swal.fire({
                            title: 'Está seguro de eliminar el registro N° '+estadia_id+'?',
                            text: "Una vez eliminado, no se puede restaurar!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!'

                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    //async: true,
                                    data: {estadia_id:estadia_id, action:action},
                                    
                                    success: function(data){
                                    $('.row'+estadia_id).remove() ;
                                    Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                            )
                                       
                                                },
                    
                                            }); 
                            } else {
                                Swal.fire({
                                        title: 'Precaución',
                                        text: 'No se eliminó ningun registro.',
                                        icon: 'error'
                                        })
                                    }
                            
                            
                        });

    });

$('#search_proveedor').change(function(e){
    e.preventDefault();
    var sistema = getUrl();
    
    location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();

})

//Activa campos para registrar clientes
$('.btn_new_cliente').click(function(e){
    e.preventDefault();
    $('#nom_cliente').removeAttr('disabled');
    $('#tel_cliente').removeAttr('disabled');
    $('#dir_cliente').removeAttr('disabled');
    $('#cor_cliente').removeAttr('disabled');

    $('#div_registro_cliente').slideDown();
});

//Buscar cliente
$('#nit_cliente').keyup(function(e){
    e.preventDefault();
    var cl = $(this).val();
    var action = 'searchCliente';

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,cliente:cl},

            success: function(response){
               

               if (response == 0){
                    $('#idpersona').val('');
                    $('#nom_cliente').val('');
                    $('#dir_cliente').val('');
                    $('#cor_cliente').val('');
                    //Mostrar boton agregar
                    $('.btn_new_cliente').slideDown();

               }else{
                    var data  = $.parseJSON(response);
                    $('#idpersona').val(data.idpersona);
                    $('#nom_cliente').val(data.nombre);
                    $('#tel_cliente').val(data.telefono);
                    $('#dir_cliente').val(data.direccion);
                    $('#cor_cliente').val(data.correo);
                    //Ocultar boton agregar
                    $('.btn_new_cliente').slideUp();

                    //Bloquea campos
                    //$('#nom_cliente').attr('disabled','disabled');
                    $('#tel_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');
                    $('#cor_cliente').attr('disabled','disabled');
                    //Oculta boton agregar
                    $('#div_registro_cliente').slideUp();
                } 
               },
                error: function(error){
               }   
    });
});

function MostrarAlerta(titulo,descripcion,tipoAlerta){
    Swal.fire(
        titulo,
        descripcion,
        tipoAlerta
        );


            }
//Buscar cliente X nombre
/*$('#nom_cliente').keyup(function(e){
    e.preventDefault();
    
    var cli = $(this).val();
    var action = 'searchCliente2';

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,cliente:cli},

            success: function(response){
               

               if (response == 0){
                    $('#idpersona').val('');
                    //$('#nom_cliente').val('');
                    $('#dir_cliente').val('');
                    $('#cor_cliente').val('');
                    //$('#nit_cliente').val('');
                    $('#tel_cliente').val('');
                    //Mostrar boton agregar
                    //$('.btn_new_cliente').slideDown();

               }else{
                    var data  = $.parseJSON(response);
                    $('#idpersona').val(data.idcliente);
                    $('#nom_cliente').val(data.nombre);
                    $('#nit_cliente').val(data.cedula);
                    $('#tel_cliente').val(data.telefono);
                    $('#dir_cliente').val(data.direccion);
                    $('#cor_cliente').val(data.correo);
                    //Ocultar boton agregar
                    //$('.btn_new_cliente').slideUp();

                    //Bloquea campos
                    //$('#nom_cliente').attr('disabled','disabled');
                    $('#tel_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');
                    $('#cor_cliente').attr('disabled','disabled');
                    //$('#nit_cliente').attr('disabled','disabled');
                    //Oculta boton agregar
                    //$('#div_registro_cliente').slideUp();
                } 
               },
                error: function(error){
               }   
    });
});
*/


    //Crear Cliente -- VENTAS--
    $('#form_new_cliente_venta').submit(function(e){
        e.preventDefault();
            
            $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: $('#form_new_cliente_venta').serialize(),

            success: function(response){
               if (response != 'error') {
                //Agregar id a input hidden
                $('#idpersona').val(response);
                //Bloquea campos
                $('#nom_cliente').attr('disabled','disabled');
                $('#tel_cliente').attr('disabled','disabled');
                $('#dir_cliente').attr('disabled','disabled');
                $('#cor_cliente').attr('disabled','disabled');
               
               //Oculta boton agregar
               $('.btn_new_cliente').slideUp();
               //Oculta boton guardar
               $('#div_registro_cliente').slideUp();
               }

                
               },
                error: function(error){
               }   
            });
        });

 

            
 //Buscar Producto -- VENTAS--
 $('#txt_cod_producto').keyup(function(e){
        e.preventDefault();
        var producto = $(this).val();
        var action  = 'infoProducto';    
            
            if (producto != '') {

            $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,producto:producto},

            success: function(response){
               if (response != 'error') {
                var info = JSON.parse(response);
                $('#txt_descripcion').html(info.descripcion);
                $('#txt_existencia').html(info.existencia);
                $('#txt_cant_producto').val('1');
                $('#txt_precio').html(info.precio);
                $('#txt_precio_total').html(info.precio);
                
                //Activar cantidad
                $('#txt_cant_producto').removeAttr('disabled');

                //Mostar boton
                $('#add_product_venta').slideDown();
               }else{
                $('#txt_descripcion').html('-');
                $('#txt_existencia').html('-');
                $('#txt_cant_producto').val('0');
                $('#txt_precio').html('0.00');
                $('#txt_precio_total').html('0.00');

                //Bloquear cantidad
                $('#txt_cant_producto').attr('disabled,disabled');

                //Ocultar boton agregar
                $('#add_product_venta').slideUp();
               }
                
               },
                error: function(error){
               }   
            });
            }
        });

    //Validar cantidad del producto antes de agregar
    $('#txt_cant_producto').keyup(function(e){
        e.preventDefault();
        var precio_total = $(this).val() * $('#txt_precio').html();
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(precio_total);

        //Oculta el boton agregar si la cantidad es menor q 1
        if ( ($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia) ){
            $('#add_product_venta').slideUp();

        }else{
            $('#add_product_venta').slideDown();
        }
    });

  



    //Agregar producto al detalle
    $('#add_product_venta').click(function(e){
        e.preventDefault();
        if ($('#txt_cant_producto').val() > 0) {
            var codproducto = $('#txt_cod_producto').val();
            var cantidad    = $('#txt_cant_producto').val();
            var action      = 'addProductoDetalle';
            $.ajax({
                url     : 'ajax.php',
                type    : "POST",
                async   : true,
                data    : {action:action,producto:codproducto,cantidad:cantidad},

                success: function(response)
                {
                   if (response != 'error') 
                   {
                      var info = JSON.parse(response);
                      $('#detalle_venta').html(info.detalle);
                      $('#detalle_totales').html(info.totales);

                      $('#txt_cod_producto').val('');
                      $('#txt_descripcion').html('-');
                      $('#txt_existencia').html('-');
                      $('#txt_cant_producto').val('0');
                      $('#txt_precio').html('0.00');
                      $('#txt_precio_total').html('0.00'); 

                      //Bloquear cantidad
                      $('#txt_cant_producto').attr('disabled','disabled');

                      //Ocultar boton agregar
                      $('#add_product_venta').slideUp();

                   }else{
                    console.log('no hay datos');
                   }
                   viewProcesar();
                   viewCotizacion();
                },
                error: function(error){

                } 
            });
        }
    });

            //Anular Venta

                    $('#btn_anular_venta').click(function(e){
                        e.preventDefault();
                        var rows = $('#detalle_venta tr').length;

                        if (rows > 0) 
                        {
                             var action = 'anularVenta';

                             $.ajax({
                                    url     : 'ajax.php',
                                    type    : "POST",
                                    async   : true,
                                    data    : {action:action},

                                    success: function(response)
                                    {
                                        
                                        if (response != 'error') {
                                            location.reload();
                                        }
                                    },
                                    error: function(error){

                                    }

                            });
                        }
            }); 

//Anular Cotizacion

                    $('#btn_anular_cotizacion').click(function(e){
                        e.preventDefault();
                        var rows = $('#detalle_venta tr').length;

                        if (rows > 0) 
                        {
                             var action = 'anularCotizacion';

                             $.ajax({
                                    url     : 'ajax.php',
                                    type    : "POST",
                                    async   : true,
                                    data    : {action:action},

                                    success: function(response)
                                    {
                                        
                                        if (response != 'error') {
                                            location.reload();
                                        }
                                    },
                                    error: function(error){

                                    }

                            });
                        }
            }); 

//Registrar Consumo Habitacion
                $('#btn_consumo_hab').click(function(e){
                            e.preventDefault();
                            var rows = $('#detalle_venta tr').length;

                            if (rows > 0) 
                            {
                                 var action = 'procesarConsumo';
                                 var idalojamiento = $('#idalojamiento').val();
                                 $.ajax({
                                        url     : 'ajax.php',
                                        type    : "POST",
                                        async   : true,
                                        data    : {action:action,idalojamiento:idalojamiento},

                                        success: function(response)
                                        {
                                            
                                            if (response != 'error') {
                                                var info = JSON.parse(response);
                                                //console.log(info);
                                                //generarPDF(info.codpersona,info.nofactura)
                                                location.reload();
                                            }else{
                                                console.log('no hay datos');
                                            }
                                        },
                                        error: function(error){

                                        }

                                });
                            }
                });         



        //Facturar Venta
                $('#btn_facturar_venta').click(function(e){
                            e.preventDefault();
                            var rows = $('#detalle_venta tr').length;

                            if (rows > 0) 
                            {
                                 var action = 'procesarVenta';
                                 var codcliente = $('#idpersona').val();
                                 $.ajax({
                                        url     : 'ajax.php',
                                        type    : "POST",
                                        async   : true,
                                        data    : {action:action,codcliente:codcliente},

                                        success: function(response)
                                        {
                                            
                                            if (response != 'error') {
                                                var info = JSON.parse(response);
                                                //console.log(info);
                                                generarPDF(info.codpersona,info.nofactura)
                                                location.reload();
                                            }else{
                                                console.log('no hay datos');
                                            }
                                        },
                                        error: function(error){

                                        }

                                });
                            }
                }); 


//Procesar Cotizacion
                $('#btn_procesar_cotizacion').click(function(e){
                            e.preventDefault();
                            var rows = $('#detalle_venta tr').length;

                            if (rows > 0) 
                            {
                                 var action = 'procesarCotizacion';
                                 var codcliente = $('#idpersona').val();
                                 $.ajax({
                                        url     : 'ajax.php',
                                        type    : "POST",
                                        async   : true,
                                        data    : {action:action,codcliente:codcliente},

                                        success: function(response)
                                        {
                                            
                                            if (response != 'error') {
                                                var info = JSON.parse(response);
                                                //console.log(info);
                                                generarPDFcotizacion(info.codpersona,info.nocotizacion)
                                                location.reload();
                                            }else{
                                                console.log('no hay datos');
                                            }
                                        },
                                        error: function(error){

                                        }

                                });
                            }
                }); 


//Modal for Anular Factura
$('.anular_factura').click(function(e) {
        e.preventDefault();
        var nofactura = $(this).attr('fac');
        var action = 'infoFactura';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,nofactura:nofactura},

            success: function(response){
                
                if (response != 'error') {
                    var info = JSON.parse(response);
                   
                    
                    $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
                '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br> Anular Factura</h1><br>'+
                '<p>¿Está seguro de anular la siguiente factura?</p>'+
                '<p><strong>N°: '+info.nofactura+'</strong></p>'+
                '<p><strong>Monto: $ '+info.totalfactura+'</strong></p>'+
                '<p><strong>Fecha: '+info.fecha+'</strong></p>'+
                '<input type="hidden" name="action" value="anularFactura">'+
                '<input type="hidden" id="no_factura" name="no_factura" value="'+info.nofactura+'">'+

                '<div class="alert alertAddProduct"></div>'+
                '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Anular</button>'+
                '<a href="#" class="btn_cancel" onclick="coloseModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                '</form>');
                }
            },
            error: function(error){
                console.log(error);
            }

            });



        $('.modal').fadeIn();

    });

//Ver Cotizacion
        $('.view_cotizacion').click(function(e){
            e.preventDefault();
            var codCliente = $(this).attr('cl');
            var noFactura = $(this).attr('f');
            generarPDFcotizacion(codCliente,noFactura);
        });

    //Ver Factura
        $('.view_factura').click(function(e){
            e.preventDefault();
            var codCliente = $(this).attr('cl');
            var noFactura = $(this).attr('f');
            generarPDF(codCliente,noFactura);
        });

//Ver Orden
        $('.view_orden').click(function(e){
            e.preventDefault();
            var codCliente = $(this).attr('cli');
            var noOrden = $(this).attr('o');
            generarPDForden(codCliente,noOrden);
        });

//Ver Informe
        $('.view_informe').click(function(e){
            e.preventDefault();
            var codCliente = $(this).attr('cli');
            var noOrden = $(this).attr('o');
            generarPDFinforme(codCliente,noOrden);
        });        


        //Cambiar Password
        $('.newPass').keyup(function(){
            validPass();
        });
        
        //form cambiar contraseña
        $('#frmChangePass').submit(function(e){
            e.preventDefault();

            var passActual = $('#txtPassUser').val();
            var passNuevo = $('#txtNewPassUser').val();
            var confirmPassNuevo = $('#txtPassConfirm').val();
            var action = "changePassword";

            if (passNuevo != confirmPassNuevo) {
            $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
            $('.alertChangePass').slideDown();
            return false;
            }
            if (passNuevo.length < 6) {
            $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe ser de 6 caracteres como mínimo.</p>');
            $('.alertChangePass').slideDown();
            return false;
            }

            $.ajax({
                                        url     : 'ajax.php',
                                        type    : "POST",
                                        async   : true,
                                        data    : {action:action,passActual:passActual,passNuevo:passNuevo},

                                        success: function(response)
                                        {
                                           if (response != 'error') {
                                            var info = JSON.parse(response);
                                            if (info.cod == '00') {
                                                $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
                                                $('#frmChangePass')[0].reset();
                                            }else{
                                                $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');
                                            }
                                                $('.alertChangePass').slideDown();
                                           }

                                                                                       
                                        },
                                        error: function(error){

                                        }

                                });

        });


//Actualizar Datos Empresa
$('#frmEmpresa').submit(function(e){
    e.preventDefault();
    var intNit         = $('#txtNit').val();
    var strNombreEmp   = $('#txtNombre').val();
    var strRSocialEmp  = $('#txtRSocial').val();
    var intTelEmp      = $('#txTelEmpresa').val();
    var strEmailEmp    = $('#txtEmailEmpresa').val();
    var strDirEmp      = $('#txtDirEmpresa').val();
    var intIva         = $('#txtIva').val();

    if (intNit == '' || intTelEmp == '' || strEmailEmp == '' || strDirEmp == '' || intIva == '') {
        $('alertFormEmpresa').html('<p style="color:red;">Todos los campos son obligatorios.</p>')
        $('alertFormEmpresa').slideDown();
        return false;
    }

    $.ajax({
        url : 'ajax.php',
        type : "POST",
        async : true,
        data : $('#frmEmpresa').serialize(),
        beforeSend :function(){
            $('.alertFormEmpresa').slideUp();
            $('.alertFormEmpresa').html('');
            $('#frmEmpresa input').attr('disabled','disabled');
        },
        success : function (response)
        {
           
                var info = JSON.parse(response);
                if (info.cod == '00') {
                    $('.alertFormEmpresa').html('<p style="color: #23922d;">'+info.msg+'</p>');
                    $('.alertFormEmpresa').slideDown();

                }else{
                    $('.alertFormEmpresa').html('<p style="color:red;">'+info.msg+'</p>');
                }
                    $('.alertFormEmpresa').slideDown();
                    $('#frmEmpresa input').removeAttr('disabled');
        },
        error : function(error){

        }
    });


});


});// Fin del Ready

function habilita_registros(elemento){
  var camp1 = document.getElementById('razon_social');
  var camp2 = document.getElementById('entidad_financiera');
  //var camp3 = document.getElementById('tipo_cuenta');
  var camp4 = document.getElementById('numero_cuenta');
    
    h = elemento.value;
  
  if(h == "2"){
    camp1.disabled = false;
    camp2.disabled = false;
    camp4.disabled = false;
    //camp3.disabled = false;
  }else{
    camp1.disabled = true;
    camp2.disabled = true;
    camp4.disabled = true;
    //camp3.disabled = true;
   
  }

}

function habilitarcampos(elemento){
  var camp1 = document.getElementById('cantidad');
  var camp2 = document.getElementById('foto');
    /*var camp1 = $('#idtipo').val();
    var camp2 = $('#cantidad').val();
    var camp3 = $('#foto').val();*/
d = elemento.value;
  
  if(d == "2"){
    camp1.disabled = true;
    camp2.disabled = true;
  }else{
    camp1.disabled = false;
    camp2.disabled = false;
  }

}


function validPass(){
    var passNuevo = $('#txtNewPassUser').val();
    var confirmPassNuevo = $('#txtPassConfirm').val();
    if (passNuevo != confirmPassNuevo) {
        $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
    if (passNuevo.length < 6) {
        $('.alertChangePass').html('<p style="color:red;">La nueva contraseña deber ser de 6 caracteres como mínimo.</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
    $('.alertChangePass').html('');
    $('.alertChangePass').slideUp();
}


//Anular Factura
function anularFactura(){
    var noFactura = $('#no_factura').val();
    var action = 'anularFactura';

    $.ajax({
            url : 'ajax.php',
            type : "POST",
            async : true,
            data : {action:action,noFactura:noFactura},

            success : function(response)
            {
              if (response == 'error') {      
                $('.alertAddProduct').html('<p style="color:red;">Error al anular la factura.</p>'); 
                
                }else {
                 $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                 $('#form_anular_factura .btn_ok').remove();
                 $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></p>');
                 $('.alertAddProduct').html('<p>Factura Anulada.</p>');
                }
            },
            error : function (error){

            }
    })
}

function generarPDFcotizacion(cliente,factura){
    var ancho = 1000;
    var alto = 800;

    //Calcular posicion x,y para centrar la ventana
     var x = parseInt((window.screen.width/2) - (ancho / 2));
     var y = parseInt((window.screen.height/2) - (alto / 2));

     $url = 'factura/generaCotizacion.php?cl='+cliente+'&f='+factura;
     window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

function generarPDFinforme(cliente,orden){
    var ancho = 1000;
    var alto = 800;

    //Calcular posicion x,y para centrar la ventana
     var x = parseInt((window.screen.width/2) - (ancho / 2));
     var y = parseInt((window.screen.height/2) - (alto / 2));

     $url = 'factura/generaInforme.php?cli='+cliente+'&o='+orden;
     window.open($url,"Orden","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}


function generarPDForden(cliente,orden){
    var ancho = 1000;
    var alto = 800;

    //Calcular posicion x,y para centrar la ventana
     var x = parseInt((window.screen.width/2) - (ancho / 2));
     var y = parseInt((window.screen.height/2) - (alto / 2));

     $url = 'factura/generaOrden.php?cli='+cliente+'&o='+orden;
     window.open($url,"Orden","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}


function generarPDF(cliente,factura){
    var ancho = 1000;
    var alto = 800;

    //Calcular posicion x,y para centrar la ventana
     var x = parseInt((window.screen.width/2) - (ancho / 2));
     var y = parseInt((window.screen.height/2) - (alto / 2));

     $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
     window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}




function del_product_detalle(correlativo){
    var action = 'delProductoDetalle';
    var id_detalle = correlativo;

    $.ajax({
                url     : 'ajax.php',
                type    : "POST",
                async   : true,
                data    : {action:action,id_detalle:id_detalle},

                success: function(response)
                {
                  if (response != 'error') {
                    var info = JSON.parse(response);
                      $('#detalle_venta').html(info.detalle);
                      $('#detalle_totales').html(info.totales);

                      $('#txt_cod_producto').val('');
                      $('#txt_descripcion').html('-');
                      $('#txt_existencia').html('-');
                      $('#txt_cant_producto').val('0');
                      $('#txt_precio').html('0.00');
                      $('#txt_precio_total').html('0.00'); 

                      //Bloquear cantidad
                      $('#txt_cant_producto').attr('disabled','disabled');

                      //Ocultar boton agregar
                      $('#add_product_venta').slideUp();

                  }else {
                    $('#detalle_venta').html('');
                    $('#detalle_totales').html('');
                  }
                   viewProcesar();
                   viewCotizacion();
                },
                error: function(error){

                } 
            });
}

//Mostrar Ocultar boton procesar
function viewProcesar(){
    if ($('#detalle_venta tr').length > 0) {
        $('#btn_facturar_venta').show();
    }else{
        $('#btn_facturar_venta').hide();
    }
}

function viewCotizacion(){
    if ($('#detalle_venta tr').length > 0) {
        $('#btn_procesar_cotizacion').show();
    }else{
        $('#btn_procesar_cotizacion').hide();
    }
}

function searchForDetalle(id){
    var action = 'searchForDetalle';
    var user = id;

    $.ajax({
                url     : 'ajax.php',
                type    : "POST",
                async   : true,
                data    : {action:action,user:user},

                success: function(response)
                {
                  if (response != 'error') 
                   {
                      var info = JSON.parse(response);
                      $('#detalle_venta').html(info.detalles);
                      $('#detalle_totales').html(info.totales);

                      
                   }else{
                    console.log('no hay datos');
                   } 
                   viewProcesar();
                   viewCotizacion();
                },
                error: function(error){

                } 
            });

}


function getUrl(){
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

function sendDataCliente()
{
   $('.alertDataCliente').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_cliente').serialize(),

            success: function(response){
               if (response == 'error') {
                /*$('.alertAddProduct').html('<p style="color: red;">Error al registrar persona</p>');*/
                 Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error al registrar cliente!',
                  showConfirmButton: true,
              });
                $('#mensaje').html(data); 
       
               }else{
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                /*$('.alertAddProduct').html('<p>Persona registrada correctamente</p>');             */
               Swal.fire({
                    icon: 'success',
                    title: 'Guardando...',
                    text: 'Datos guardados correctamente',
                    showConfirmButton: true,
                    
                });
                $('#mensaje').html(data);
               } 
               location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendDataHabitacion()
{
   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_habitacion').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al registrar piso</p>');
                 
       
               }else{
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                $('.alertAddProduct').html('<p>Registrado correctamente</p>');             
               
               } 
               location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendDataPiso()
{
   $('.alertAddPiso').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_piso').serialize(),

            success: function(response){
               if (response == 'error') {
                /*$('.alertAddProduct').html('<p style="color: red;">Error al registrar piso</p>');*/
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error al registrar piso!',
                  showConfirmButton: true,
              });
                $('#mensaje').html(data);
               }else{
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                /*$('.alertAddProduct').html('<p>Registrado correctamente</p>');*/             
                Swal.fire({
                    icon: 'success',
                    title: 'Guardando...',
                    text: 'Datos guardados correctamente',
                    showConfirmButton: true,
                    
                });
                $('#mensaje').html(data);
               } 
               location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendDataCategoria()
{
   $('.alertAddCategoria').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_categoria').serialize(),

            success: function(response){
               if (response == 'error') {
            Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error al registrar categoria!',
                  showConfirmButton: true,
              });
                /*$('.alertAddProduct').html('<p style="color: red;">Error al registrar categoria</p>');*/
                 $('#mensaje').html(data);
       
               }else{
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                /*$('.alertAddProduct').html('<p>Registrada correctamente</p>');*/             
               Swal.fire({
                    icon: 'success',
                    title: 'Guardando...',
                    text: 'Datos guardados correctamente',
                    showConfirmButton: true,
                    
                });
               $('#mensaje').html(data);
               } 
               location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendDataProduct()
{
   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_add_product').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al agregar el producto</p>'); 
       
               }else{
                var info = JSON.parse(response);
                $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');
                $('.alertAddProduct').html('<p>Producto guardado correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}


function sendEditCliente()
{
   $('.alertEditCliente').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_edit_cliente').serialize(),

            success: function(response){
               if (response == 'error') {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error al actualizar cliente!',
                  showConfirmButton: true,
              });
                /*$('.alertAddProduct').html('<p style="color: red;">Error al actualizar piso</p>');*/
                 $('#mensaje').html(data);
       
               }else{
                Swal.fire({
                    icon: 'success',
                    title: 'Guardando...',
                    text: 'Datos actualizados correctamente',
                    showConfirmButton: true,
                    
                });
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                /*$('.alertAddProduct').html('<p>Actualizado correctamente</p>');*/             
               $('#mensaje').html(data);
               } 
               //location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendEditPiso()
{
   $('.alertEditPiso').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_edit_piso').serialize(),

            success: function(response){
               if (response == 'error') {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error al actualizar piso!',
                  showConfirmButton: true,
              });
                /*$('.alertAddProduct').html('<p style="color: red;">Error al actualizar piso</p>');*/
                 $('#mensaje').html(data);
       
               }else{
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizando Piso...',
                    text: 'Datos actualizados correctamente',
                    showConfirmButton: true,
                    
                });
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                /*$('.alertAddProduct').html('<p>Actualizado correctamente</p>');*/             
               $('#mensaje').html(data);
               } 
               //location.reload();
            },
            error: function(error){
                console.log(error);
            }

            });
}

function sendEditarCategoria()
{
 $('.alertAddProduct').html('');

 $.ajax({
    url: 'ajax.php',
    type: 'POST',
    async: true,
    data: $('#form_edit_categoria').serialize(),

    success: function(response){
     if (response == 'error') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Error al actualizar categoria!',
          footer: '<a href>Why do I have this issue?</a>'
      });

        /*$('.alertAddProduct').html('<p style="color: red;">Error al actualizar categoria</p>');*/
            $('#mensaje').html(data);

    }else{
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizando Categoria...',
                    text: 'Datos actualizados correctamente',
                    showConfirmButton: true,
                    
                });
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');
                $('.alertAddProduct').html('<p>Actualizado correctamente</p>');             
                $('.alertAddProduct').html('actualizado');*/
                $('#mensaje').html(data);

            } 
            /*return header("Location:../lista_pisos.php");*/
            /*location.reload();*/
        },
        error: function(error){
            console.log(error);
        }

    });
}

function sendEditarHabitacion()
{
 $('.alertAddProduct').html('');

 $.ajax({
    url: 'ajax.php',
    type: 'POST',
    async: true,
    data: $('#form_edit_habitacion').serialize(),

    success: function(response){
     if (response == 'error') {
        
        $('.alertAddProduct').html('<p style="color: red;">Error al actualizar habitacion</p>');


    }else{
                //var info = JSON.parse(response);
                /*$('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');*/
                $('.alertAddProduct').html('<p>Actualizado correctamente</p>');             

            } 
            /*return header("Location:../lista_pisos.php");*/
            /*location.reload();*/
        },
        error: function(error){
            console.log(error);
        }

    });
}


// Eliminar Categoria
function deleteCategoria()
{
    var prs = $('#categoria_id').val();

    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_del_categoria').serialize(),

        success: function(response){
         if (response == 'error') {
            $('.alertAddProduct').html('<p style="color: red;">Error al eliminar categoria</p>'); 

        }else{

            $('.row'+prs).remove() ;
            $('#form_del_categoria .btn_ok').remove();
            $('.alertAddProduct').html('<p style="color: green;">Categoria eliminada correctamente</p>');             
        } 

    },
    error: function(error){
        console.log(error);
    }

});
}

// Eliminar Orden
function delOrden()
{
    var ord = $('#orden_id').val();

   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_orden').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar orden</p>'); 
       
               }else{
                
                $('.row'+ord).remove() ;
                $('#form_del_orden .btn_ok').remove();
                $('.alertAddProduct').html('<p style="color: green;">Orden eliminada correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}


// Eliminar Proveedor
function delProveedor()
{
    var prov = $('#proveedor_id').val();

   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_proveedor').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar proveedor</p>'); 
       
               }else{
                
                $('.row'+prov).remove() ;
                $('#form_del_proveedor .btn_ok').remove();
                $('.alertAddProduct').html('<p style="color: green;">Proveedor eliminado correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}



// Eliminar Usuario
function delUsuario()
{
    var usr = $('#usuario_id').val();

   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_usuario').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar usuario</p>'); 
       
               }else{
                
                $('.row'+usr).remove() ;
                $('#form_del_usuario .btn_ok').remove();
                $('.alertAddProduct').html('<p style="color: green;">Usuario eliminado correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}

// Eliminar Cliente
function delPiso()
{
    var prs = $('#piso_id').val();

   $('.alertDelPiso').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_piso').serialize(),

            success: function(response){
               if (response == 'error') {
                /*$('.alertAddProduct').html('<p style="color: red;">Error al eliminar el persona</p>'); */
       
               }else{
                
                $('.row'+prs).remove() ;
                $('#form_del_piso .btn_ok').remove();
                /*$('.alertAddProduct').html('<p style="color: green;">Persona eliminado correctamente</p>');*/             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}

// Eliminar Cliente
function delPersona()
{
    var prs = $('#persona_id').val();

   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_persona').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar el persona</p>'); 
       
               }else{
                
                $('.row'+prs).remove() ;
                $('#form_del_persona .btn_ok').remove();
                $('.alertAddProduct').html('<p style="color: green;">Persona eliminado correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}



// Eliminar Producto
function delProduct()
{
    var pr = $('#producto_id').val();

   $('.alertAddProduct').html('');

    $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_del_product').serialize(),

            success: function(response){
               if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar el producto</p>'); 
       
               }else{
                
                $('.row'+pr).remove() ;
                $('#form_del_product .btn_ok').remove();
                $('.alertAddProduct').html('<p>Producto eliminado correctamente</p>');             
               } 
               
            },
            error: function(error){
                console.log(error);
            }

            });
}



function coloseModal(){
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val();
    $('.modal').fadeOut();
    location.reload();
}


