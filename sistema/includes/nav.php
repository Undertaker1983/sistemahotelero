<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-home fa-lg"></i> Inicio</a></li>
			
				<li class="principal">
					<a href="#"><i class="fas fa-database fa-lg"></i> Configuración <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="lista_habitaciones.php"><i class="fas fa-bed"></i> Habitaciones</a></li>
						<li><a href="lista_categorias.php"><i class="fas fa-clipboard-list"></i> Categorias</a></li>
						<li><a href="lista_pisos.php"><i class="fas fa-hotel"></i> Pisos</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="fullcalendar/index.php"><i class="fas fa-calendar-alt"></i> Reservas <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					
				</li>

				<li class="principal">
					<a href="#"><i class="fas fa-arrow-circle-up fa-lg"></i> INGRESOS <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						
						<li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Facturas de Venta</a></li>
						
						<li><a href="#"><i class="fas fa-dollar-sign"></i> Pagos recibidos</a></li>
						<li><a href="#"><i class="fas fa-file-invoice"></i> Notas de crédito</a></li>
						<li><a href="lista_cotizaciones.php"><i class="fas fa-file-alt"></i> Cotizaciones</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="#"><i class="fas fa-arrow-circle-down fa-lg"></i> GASTOS <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="#"><i class="fas fa-dollar-sign"></i> Pagos</a></li>
						<li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Facturas de proveedores</a></li>
						<li><a href="#"><i class="fas fa-file-invoice"></i> Notas de dédito</a></li>
						<li><a href="#"><i class="fas fa-shopping-cart"></i> Ordenes de compra</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="#"><i class="fas fa-users fa-lg"></i> CONTACTOS <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						
						<li><a href="lista_clientes.php"><i class="fas fa-address-book"></i> Clientes</a></li>
						<li><a href="lista_proveedores.php"><i class="fas fa-truck"></i> Proveedores</a></li>
					</ul>
				</li>
				<?php 
					if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
						
					
				 ?>
				<!--<li class="principal">
					<a href="#"><i class="fas fa-truck fa-lg"></i> Proveedores <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-truck"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="fas fa-address-book"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php } ?>-->
				<li class="principal">
					<a href="#"><i class="fas fa-cubes fa-lg"></i> INVENTARIO <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<?php 
					if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
						
					 ?>
						<li><a href="registro_producto.php"><i class="fas fa-plus"></i> Nuevo item</a></li>
						<?php } ?>
						<li><a href="lista_productos.php"><i class="fas fa-book"></i> Items de Venta</a></li>
						<li><a href="#"><i class="fas fa-book"></i> Valor de inventario</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-calculator fa-lg"></i> CHECK OUT <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					
				</li>
				
				<li class="principal">
					<a href="#"><i class="fas fa-chart-line fa-lg"></i> REPORTES <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_orden.php"><i class="fas fa-plus"></i> Reporte Diario</a></li>
						<li><a href="#"><i class="fas fa-pen"></i> Reporte De Caja</a></li>
						<li><a href="#"><i class="fas fa-clipboard"></i> Reporte Mensual</a></li>
					</ul>
				</li>
				
				<?php 
				if($_SESSION['rol'] == 1){
			 ?>
				<li class="principal">

					<a href="#"><i class="fas fa-cogs fa-lg"></i> Administración <span class="arrow"><i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="Configuraciones.php"><i class="fas fa-building"></i> Empresa</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i> Usuarios</a></li>
						<li><a href="backup.php"><i class="fas fa-database"></i> Copia de Seguridad</a></li>
					</ul>
				</li>
			<?php } ?>
			</ul>
		</nav>	
	