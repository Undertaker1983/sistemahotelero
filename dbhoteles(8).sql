-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-05-2021 a las 06:08:05
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbhoteles`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)  BEGIN
    	DECLARE nueva_existencia int;
        DECLARE nuevo_total  decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        
        DECLARE actual_existencia int;
        DECLARE actual_precio decimal(10,2);
                
        SELECT precio,existencia INTO actual_precio,actual_existencia FROM producto WHERE codproducto = codigo;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        
        UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;
        
        SELECT nueva_existencia,nuevo_precio;
        
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))  BEGIN
	DECLARE precio_actual decimal (18,2);
    SELECT precio INTO precio_actual FROM producto WHERE codproducto = codigo;
    
    INSERT INTO detalle_temp(token_user,codproducto,cantidad,precio_venta) VALUES (token_user,codigo,cantidad,precio_actual);
    
    SELECT tmp.correlativo,tmp.codproducto,p.descripcion,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
    INNER JOIN producto p
    ON tmp.codproducto = p.codproducto
    WHERE tmp.token_user = token_user;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_factura` (`no_factura` INT)  BEGIN
    DECLARE existe_factura int;
    DECLARE registros int;
    DECLARE a int;
    
    DECLARE cod_producto int;
    DECLARE cant_producto int;
    DECLARE existencia_actual int;
    DECLARE nueva_existencia int;
    
    SET existe_factura = (SELECT COUNT(*) FROM factura WHERE nofactura = no_factura AND status = 1);
    
    IF existe_factura > 0 THEN
    	CREATE TEMPORARY TABLE tbl_tmp (id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, cod_prod BIGINT, cant_prod INT);
        
        SET a = 1;
        SET registros = (SELECT COUNT(*) FROM detallefactura WHERE nofactura = no_factura);
        
        IF registros > 0 THEN
        	INSERT INTO tbl_tmp (cod_prod,cant_prod) SELECT codproducto,cantidad FROM detallefactura WHERE nofactura = no_factura;
            
            WHILE a <= registros DO
            SELECT cod_prod,cant_prod INTO cod_producto,cant_producto FROM tbl_tmp WHERE id = a;
            SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = cod_producto;
            SET nueva_existencia = existencia_actual + cant_producto;
            UPDATE producto SET existencia = nueva_existencia WHERE codproducto = cod_producto;
            
            SET a = a + 1;
            
            END WHILE;
        	
            UPDATE factura SET status = 2 WHERE nofactura = no_factura;
            DROP TABLE tbl_tmp;
            SELECT * FROM factura WHERE nofactura = no_factura;
        END IF;
        
    ELSE
    	SELECT 0 factura;
    END IF;    
    
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dataDashboard` ()  BEGIN
    DECLARE usuarios int;
    DECLARE persona int;
    DECLARE productos int;
    DECLARE ventas int;
    DECLARE reservaciones int;
    
    SELECT COUNT(*) INTO usuarios FROM usuario WHERE estatus != 0;
    SELECT COUNT(*) INTO persona FROM personas WHERE status != 10;
    
    SELECT COUNT(*) INTO productos FROM producto WHERE status != 10;
    SELECT COUNT(*) INTO ventas FROM factura WHERE fecha >	 CURDATE() AND status != 10;
    SELECT COUNT(*) INTO reservaciones FROM events;
    
    SELECT usuarios,persona,productos,ventas,reservaciones;
    
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))  BEGIN
    DELETE FROM detalle_temp WHERE correlativo = id_detalle;
    
    SELECT tmp.correlativo,tmp.codproducto,p.descripcion,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
    INNER JOIN producto p
    ON tmp.codproducto = p.codproducto
    WHERE tmp.token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_consumo` (IN `cod_usuario` INT, IN `id_alojamiento` INT, IN `token` VARCHAR(50))  BEGIN
	DECLARE consumo INT;
    DECLARE registros INT;
    
    DECLARE total DECIMAL(10,2);
    DECLARE cant_pro INT;
    DECLARE id_pro INT;
        
    DECLARE nueva_existencia INT;
    DECLARE existencia_actual INT;
    
    DECLARE tmp_cod_producto INT;
    DECLARE tmp_cant_producto INT;
    DECLARE a INT;
    SET a = 1;
    
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,cod_prod BIGINT,cant_prod INT);
        
    SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
    
    IF registros > 0 THEN
    	INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto,cantidad FROM detalle_temp WHERE token_user = token;
        
        INSERT INTO consumo (usuario,idalojamiento) VALUES (cod_usuario,id_alojamiento);
        SET consumo = LAST_INSERT_ID();
    	
         INSERT INTO detallefactura (nofactura,codproducto,cantidad,precio_venta) SELECT (consumo) as nofactura, codproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
    	
        WHILE a <= registros DO
        	SELECT cod_prod,cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
        	
            SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
            
            SET nueva_existencia = existencia_actual - tmp_cant_producto;
            UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
            
            SET a=a+1;
            
        END WHILE;
    	SET total= (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
        UPDATE consumo SET precio_consumo = total WHERE idconsumo = consumo;
        SET id_pro = (SELECT codproducto FROM detalle_temp WHERE token_user = token);
        UPDATE consumo SET idproducto = id_pro WHERE idconsumo = consumo;
        SET cant_pro = (SELECT cantidad FROM detalle_temp WHERE token_user = token);
        UPDATE consumo SET cantidad = cant_pro WHERE idconsumo = consumo;
       
        
        DELETE FROM detalle_temp WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM consumo WHERE idconsumo = consumo;
    
    ELSE
    SELECT 0;
    END IF;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_cotizacion` (`cod_usuario` INT, `cod_cliente` INT, `token` VARCHAR(50))  BEGIN
	DECLARE cotizacion INT;
    DECLARE registros INT;
    
    DECLARE total DECIMAL(10,2);
    
    
    DECLARE tmp_cod_producto INT;
    DECLARE tmp_cant_producto INT;
    DECLARE a INT;
    SET a = 1;
    
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,cod_prod BIGINT,cant_prod INT);
        
    SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
    
    IF registros > 0 THEN
    	INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto,cantidad FROM detalle_temp WHERE token_user = token;
        
        INSERT INTO cotizacion (usuario,codpersona) VALUES (cod_usuario,cod_cliente);
        SET cotizacion = LAST_INSERT_ID();
    	
        INSERT INTO detallefactura (nofactura,codproducto,cantidad,precio_venta) SELECT (cotizacion) as nocotizacion, codproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
    	
	WHILE a <= registros DO
        	SELECT cod_prod,cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
	SET a=a+1;
            
        END WHILE;
        
    	SET total= (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
        UPDATE cotizacion SET totalcotizacion = total WHERE nocotizacion = cotizacion;
        
        DELETE FROM detalle_temp WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM cotizacion WHERE nocotizacion = cotizacion;
    
    ELSE
    SELECT 0;
    END IF;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(100))  BEGIN
	DECLARE factura INT;
    DECLARE registros INT;
    
    DECLARE total DECIMAL(10,2);
    
    DECLARE nueva_existencia INT;
    DECLARE existencia_actual INT;
    
    DECLARE tmp_cod_producto INT;
    DECLARE tmp_cant_producto INT;
    DECLARE a INT;
    SET a = 1;
    
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,cod_prod BIGINT,cant_prod INT);
        
    SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
    
    IF registros > 0 THEN
    	INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto,cantidad FROM detalle_temp WHERE token_user = token;
        
        INSERT INTO factura (usuario,codpersona) VALUES (cod_usuario,cod_cliente);
        SET factura = LAST_INSERT_ID();
    	
         INSERT INTO detallefactura (nofactura,codproducto,cantidad,precio_venta) SELECT (factura) as nofactura, codproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
    	
        WHILE a <= registros DO
        	SELECT cod_prod,cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
        	
            SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
            
            SET nueva_existencia = existencia_actual - tmp_cant_producto;
            UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
            
            SET a=a+1;
            
        END WHILE;
    	SET total= (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
        UPDATE factura SET totalfactura = total WHERE nofactura = factura;
        
        DELETE FROM detalle_temp WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM factura WHERE nofactura = factura;
    
    ELSE
    SELECT 0;
    END IF;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activos`
--

CREATE TABLE `activos` (
  `idactivos` int(10) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `activos`
--

INSERT INTO `activos` (`idactivos`, `nombre`, `status`) VALUES
(1, 'Activos corrientes', 1),
(2, 'Activos no corrientes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alojamiento`
--

CREATE TABLE `alojamiento` (
  `idalojamiento` int(20) NOT NULL,
  `idhabitacion` int(20) DEFAULT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `hora_ingreso` time DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cant_noches` int(11) DEFAULT NULL,
  `anticipo` decimal(10,2) DEFAULT '0.00',
  `cant_personas` int(11) DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado_pago` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `medio_pago` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alojamiento`
--

INSERT INTO `alojamiento` (`idalojamiento`, `idhabitacion`, `idpersona`, `fecha_ingreso`, `hora_ingreso`, `fecha_salida`, `precio`, `cant_noches`, `anticipo`, `cant_personas`, `hora_salida`, `estado_pago`, `medio_pago`, `estado`) VALUES
(11, 4, 20, '2021-04-18', '18:20:00', '2021-04-19', '20.00', 1, NULL, 1, '18:19:00', 'Falta Cancelar', 'Efectivo', 1),
(12, 19, 20, '2021-04-25', '17:23:00', '2021-04-26', '20.00', 1, NULL, 1, '17:23:00', 'Cancelado', 'Efectivo', 1),
(13, 4, 16, '2021-04-29', '23:00:00', '2021-04-30', '20.00', 1, '10.50', 1, '23:00:00', 'Cancelado', 'Efectivo', 1),
(14, 6, 26, '2021-05-01', '09:29:00', '2021-05-03', '75.00', 3, '0.00', 1, '09:30:00', 'Falta Cancelar', 'Efectivo', 1),
(15, 18, 28, '2021-05-01', '09:34:00', '2021-05-03', '50.00', 2, '0.00', 2, '09:35:00', 'Falta Cancelar', 'Efectivo', 1),
(16, 16, 19, '2021-05-01', '09:37:00', '2021-05-03', '60.00', 2, '40.00', 1, '09:38:00', 'Falta Cancelar', 'Efectivo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `idcategoria` int(11) NOT NULL,
  `nombre_categoria` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`idcategoria`, `nombre_categoria`, `estado`) VALUES
(1, 'Matrimonial', 1),
(2, 'Ocasional', 1),
(3, 'Familiar', 1),
(4, 'Doble', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(20) NOT NULL,
  `cedula` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `razon_social` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `iva` decimal(18,2) NOT NULL,
  `logo` text COLLATE utf8_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `cedula`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`, `logo`) VALUES
(1, '0926942137001', 'ROGERIO LOPES VAZ', 'HOTEL COPACABANA', '0982941956 - 0991234282', 'hotelcopacabana.ec@gmail.com', 'BARRIO 25 DE DICIEMBRE AV.2 CALLE 20 Y 21 ', '12.00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo`
--

CREATE TABLE `consumo` (
  `idconsumo` int(100) NOT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `idalojamiento` int(100) DEFAULT NULL,
  `cantidad` int(10) DEFAULT NULL,
  `precio_consumo` decimal(10,2) DEFAULT NULL,
  `estado` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `consumo`
--

INSERT INTO `consumo` (`idconsumo`, `idproducto`, `usuario`, `idalojamiento`, `cantidad`, `precio_consumo`, `estado`) VALUES
(7, 15, 2, 16, 1, '0.60', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `nocotizacion` int(100) NOT NULL,
  `codpersona` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` int(11) DEFAULT NULL,
  `totalcotizacion` decimal(10,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_activos`
--

CREATE TABLE `cuentas_activos` (
  `idcta` int(10) NOT NULL,
  `idactivos` int(5) NOT NULL,
  `nombre_cuenta` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cuentas_activos`
--

INSERT INTO `cuentas_activos` (`idcta`, `idactivos`, `nombre_cuenta`, `status`) VALUES
(1, 1, 'Efectivo y equivalentes de efectivo', 1),
(2, 1, 'Caja general', 1),
(3, 1, 'Bancos', 1),
(4, 1, 'Deudores comerciales y otras cuentas por cobrar', 1),
(5, 1, 'Cuentas por cobrar clientes', 1),
(6, 1, 'Avances y anticipos entregados', 1),
(7, 1, 'Impuestos a favor', 1),
(8, 1, ' Otro tipo de impuesto a favor', 1),
(9, 1, 'Retenciones a favor', 1),
(10, 1, 'Otro tipo de retención a favor', 1),
(11, 1, 'Otras cuentas por cobrar', 1),
(12, 1, ' Cuentas por cobrar empleados', 1),
(13, 1, 'Préstamos a terceros', 1),
(14, 1, 'Devoluciones a proveedores', 1),
(15, 1, 'Inventarios', 1),
(16, 1, 'Inversiones a corto plazo', 1),
(19, 1, ' Otros activos corrientes', 1),
(20, 2, 'Propiedad, planta y equipo (Activos fijos)', 1),
(21, 2, 'Otros Activos no corrientes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_contables`
--

CREATE TABLE `cuentas_contables` (
  `idcta` int(5) NOT NULL,
  `idegreso` int(5) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre_cuenta` varchar(300) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cuentas_contables`
--

INSERT INTO `cuentas_contables` (`idcta`, `idegreso`, `codigo`, `nombre_cuenta`, `descripcion`, `status`) VALUES
(1, 1, NULL, 'Costo de los servicios vendidos', NULL, 1),
(2, 1, NULL, 'Costos de la mercancia vendida', NULL, 1),
(3, 2, NULL, 'Depreciación de propiedad, planta y equipo', NULL, 1),
(4, 2, NULL, 'Deterioro de cuentas por cobrar', NULL, 1),
(5, 3, NULL, 'Gastos de personal', NULL, 1),
(6, 3, NULL, 'Gastos generales', NULL, 1),
(7, 3, NULL, 'Gastos por impuestos no acreditables', NULL, 1),
(8, 3, NULL, 'Impuestos de renta y complementarios', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleautorizacion`
--

CREATE TABLE `detalleautorizacion` (
  `codautorizacion` int(100) NOT NULL,
  `autorizacion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `f_inicio` date DEFAULT NULL,
  `f_max` date DEFAULT NULL,
  `serie` varchar(7) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) DEFAULT NULL,
  `codproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detallefactura`
--

INSERT INTO `detallefactura` (`correlativo`, `nofactura`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(1, 1, 14, 1, '0.90'),
(2, 2, 15, 1, '0.60'),
(3, 3, 14, 3, '0.90'),
(4, 4, 14, 1, '0.90'),
(5, 5, 15, 1, '0.60'),
(6, 6, 14, 1, '0.90'),
(7, 7, 15, 1, '0.60');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `idegreso` int(5) NOT NULL,
  `nombre` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`idegreso`, `nombre`, `status`) VALUES
(1, 'Costos de venta y operación', 1),
(2, 'Depreciaciones, amortizaciones y deterioros', 1),
(3, 'Gastos de administración', 1),
(4, 'Gastos por impuestos', 1),
(5, 'Otros gastos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(18,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `codproducto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(1, 14, '2021-04-18 17:11:52', 5, '0.90', 2),
(2, 15, '2021-05-01 11:54:50', 24, '0.60', 2),
(3, 14, '2021-05-02 11:51:11', 21, '0.90', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_spanish_ci DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `title`, `color`, `start`, `end`) VALUES
(3, '', '', '2021-03-04 00:00:00', '2021-03-05 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` int(11) DEFAULT NULL,
  `codpersona` int(11) DEFAULT NULL,
  `totalfactura` decimal(10,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `idhabitacion` int(11) NOT NULL,
  `idpiso` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `nombre_habitacion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `detalles` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `condicion` varchar(100) COLLATE utf8_spanish_ci DEFAULT 'Disponible',
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`idhabitacion`, `idpiso`, `idcategoria`, `nombre_habitacion`, `detalles`, `precio`, `condicion`, `estado`) VALUES
(1, 6, 1, '101', '', '20.00', 'Disponible', 0),
(2, 6, 2, '101', '', '10.00', 'Disponible', 0),
(3, 6, 1, '102', '', '20.00', 'Disponible', 0),
(4, 6, 1, '102', 'CAMA DE 1 1/2PLAZA', '20.00', 'Ocupado', 1),
(5, 6, 1, '101', 'CAMA 1 1/2 PLAZA', '20.00', 'Disponible', 1),
(6, 6, 1, '101', 'CAMA 1 1/2 PLAZA', '20.00', 'Ocupado', 1),
(7, 6, 1, '101', 'CAMA 1 1/2 PLAZA', '20.00', 'Disponible', 0),
(8, 6, 2, '101', 'CAMA 1 1/2 PLAZA', '10.00', 'Ocupado', 1),
(9, 6, 1, '101', 'CAMA 1 1/2 PLAZA', '20.00', 'Limpieza', 1),
(10, 6, 2, '101', 'CAMA 1 1/2 PLAZA', '10.00', 'Mantenimiento', 1),
(11, 6, 1, '101', 'CAMA 1 1/2 PLAZA', '20.00', 'Ocupado', 0),
(12, 6, 1, '102', 'CAMA 1 1/2 PLAZA', '20.00', 'Limpieza', 0),
(13, 6, 2, '103', '', '10.00', 'Ocupado', 0),
(14, 6, 1, '103', '', '20.00', 'Disponible', 0),
(15, 6, 1, '104', 'CAMA 1 1/2 PLAZA', '20.00', 'Disponible', 1),
(16, 6, 1, '105', 'CAMA 1 1/2 PLAZA', '20.00', 'Ocupado', 1),
(17, 6, 1, '102', 'prueba 123', '20.00', 'Disponible', 0),
(18, 7, 3, '206', 'Todos los servicios incluidos', '20.00', 'Ocupado', 1),
(19, 8, 2, '148', 'Todos los servicios incluidos', '10.00', 'Ocupado', 1),
(20, 6, 1, '58', 'Todos los servicios incluidos', '20.00', 'Limpieza', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeracion`
--

CREATE TABLE `numeracion` (
  `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `prefijo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `n_inicial` int(50) NOT NULL,
  `n_final` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `idpago` int(100) NOT NULL,
  `idalojamiento` int(100) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `tipo_comprobante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numero_comprobante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `total_pago` decimal(10,2) DEFAULT NULL,
  `fecha_emision` datetime DEFAULT NULL,
  `estado` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_recibido`
--

CREATE TABLE `pago_recibido` (
  `idpago` int(20) NOT NULL,
  `idpersona` int(20) NOT NULL,
  `detalle` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `idpersona` int(11) NOT NULL,
  `cedula` varchar(15) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` text,
  `correo` varchar(80) DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `razon_social` varchar(150) DEFAULT NULL,
  `entidad_financiera` varchar(100) DEFAULT NULL,
  `tipo_cuenta` varchar(30) DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `idtipo` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`idpersona`, `cedula`, `nombre`, `telefono`, `direccion`, `correo`, `dateadd`, `razon_social`, `entidad_financiera`, `tipo_cuenta`, `numero_cuenta`, `idtipo`, `usuario_id`, `status`) VALUES
(10, '1791715772001', 'ECONOFARM S.A', '', 'QUITO, KM 5  1/2 AV. DE LOS SHYRIS S/N', '', '2021-03-27 08:36:51', NULL, NULL, NULL, NULL, 1, 2, 1),
(11, '0990009732001', 'COMPAÃ‘IA GENERAL DE COMERCIO Y MANDATO S.A', '', 'GUAYAQUIL,AV. ELIAS  MUÃ‘OZ S/N AV.CARLOS LUIS PLAZA', '', '2021-03-27 08:38:07', NULL, NULL, NULL, NULL, 1, 2, 1),
(12, '1312572082', 'EDISON VELEZ', '', 'MANTA', '', '2021-03-27 08:39:01', NULL, NULL, NULL, NULL, 1, 2, 1),
(13, '1313839738', 'JUAN ZAMBRANO', '', 'MANTA', '', '2021-03-27 08:39:43', NULL, NULL, NULL, NULL, 1, 2, 1),
(14, '0932062573', 'VANESSA BUSTAMANTE', '', 'GUAYAQUIL', '', '2021-03-27 08:40:19', NULL, NULL, NULL, NULL, 1, 2, 1),
(15, '1792623669001', 'AIRTEC', '', 'QUITO', '', '2021-03-27 08:40:50', NULL, NULL, NULL, NULL, 1, 2, 1),
(16, '0921229456', 'HUANCAYO CHIQUITO ALEXANDRA VANESSA', '0994229643', 'GUAYAQUIL', '', '2021-03-27 08:41:23', '', '', '', '', 1, 2, 1),
(17, '01022074313', 'ORELLANA OCHOA SEGUNDO MARTIN', '', 'CUENCA', '', '2021-03-27 09:04:46', NULL, NULL, NULL, NULL, 1, 2, 1),
(18, '0926200361001', 'ZULUAGA GOMEZ JAIME ALCIDES ', '', 'CUENCA', '', '2021-03-27 09:05:50', NULL, NULL, NULL, NULL, 1, 2, 1),
(19, '0908690373001', ' REZABALA ROVELLO MONICA CECILIA', '', ' LA LIBERTAD', '', '2021-03-27 09:07:03', NULL, NULL, NULL, NULL, 1, 2, 1),
(20, ' 	0930087051', 'QUINTEROS NAREA JOSE MIGUEL', '', 'GAUAYAQUIL', '', '2021-03-27 09:08:03', NULL, NULL, NULL, NULL, 1, 2, 1),
(21, '0922113394', 'EVANGELISTA TUMBACO OMAR ROBERTO', '', 'GAUAYAQUIL', '', '2021-03-27 09:08:40', NULL, NULL, NULL, NULL, 1, 2, 1),
(22, '1722701594', 'CASTRO ROJAS EDUARDO VINICIO', '', 'QUITO', '', '2021-03-27 09:09:34', NULL, NULL, NULL, NULL, 1, 2, 1),
(23, '1803368149001', 'SANCHEZ CORDOVILLA CHRISTIAN ARTURO ', '', 'GUAYAQUIL Y SUCRE', '', '2021-03-27 09:11:13', NULL, NULL, NULL, NULL, 1, 2, 1),
(24, '0919583153', ' PINCAY RIVADENEIRA JORGE ANTONIO', '', 'GUAYAQUIL', '', '2021-03-27 09:12:58', NULL, NULL, NULL, NULL, 1, 2, 1),
(25, '1791300386001', ' KICLAR CAMARON S.A.', '', 'MACHALA YACH CLUB', '', '2021-03-27 09:13:57', NULL, NULL, NULL, NULL, 1, 2, 1),
(26, '0918117045', 'PALADINES MIÃ‘AN HERMELINDA JASSMIN', '', 'GUAYAQUIL', '', '2021-03-27 09:14:43', NULL, NULL, NULL, NULL, 1, 2, 1),
(27, '17127842102001', ' GORDILLO GRANDA ALEXIS PAUL', '3013440', 'PIQUERO Y SAN CRISTOBAL ', '', '2021-03-27 09:16:22', '', '', '', '', 1, 2, 1),
(28, '1316057411', 'MENDOZA ALAVA JULIO CESAR', '', 'MANTA', '', '2021-03-27 09:18:58', NULL, NULL, NULL, NULL, 1, 2, 1),
(29, '0925723264', ' 	CARVAJAL CHAVEZ PEDRO GUSTAVO', '', 'LA LIBERTAD', '', '2021-03-29 12:14:59', NULL, NULL, NULL, NULL, 1, 2, 1),
(30, '0925723264', ' 	CARVAJAL CHAVEZ PEDRO GUSTAVO', '', 'LA LIBERTAD', '', '2021-03-29 12:16:49', NULL, NULL, NULL, NULL, 1, 2, 1),
(31, ' 	1200735973', ' 	JIMENEZ LAJE MARIA LEONOR', '', 'GUAYAQUIL', '', '2021-03-29 12:28:35', NULL, NULL, NULL, NULL, 1, 2, 1),
(32, ' 	0924945090', 'GRANOBLE CHOEZ PEDRO JAVIER', '', 'GUAYAQUIL', '', '2021-03-29 12:29:43', NULL, NULL, NULL, NULL, 1, 2, 1),
(33, ' 	0924945090', 'GRANOBLE CHOEZ PEDRO JAVIER', '', 'GUAYAQUIL', '', '2021-03-29 12:30:53', NULL, NULL, NULL, NULL, 1, 2, 1),
(34, '0952556447', 'ZAMBRANO CASTAÃ‘EDA JOAN PEDRO', '', 'GUAYAQUIL, GUASMO SUR', '', '2021-03-29 12:32:47', NULL, NULL, NULL, NULL, 1, 2, 1),
(35, ' 	0923055560', 'CRUZ GUAIGUA YANETH DEL ROCIO', '', 'GUAYAQUIL', '', '2021-03-29 12:34:29', NULL, NULL, NULL, NULL, 1, 2, 0),
(36, '0963358320', 'CASTILLO SILVA JUNIOR JESUS', '', 'GUAYAQUIL', '', '2021-03-29 12:35:46', NULL, NULL, NULL, NULL, 1, 2, 1),
(37, '0990993564001', 'CONTROL INTERNACIONAL DEL ECUADOR C.A. UNICONTROL', '', 'GUAYAQUIL', '', '2021-03-29 12:37:31', NULL, NULL, NULL, NULL, 1, 2, 0),
(38, '0990993564001', 'CONTROL INTERNACIONAL DEL ECUADOR C.A. UNICONTROL', '', 'GUAYAS / GUAYAQUIL / PASCUALES / SOLAR 6-H', '', '2021-03-29 12:39:04', NULL, NULL, NULL, NULL, 1, 2, 1),
(39, ' 	0963785282', 'MARTINEZ SUBERO FRANK JOSE', '', 'GUAYAQUIL', '', '2021-03-29 12:40:48', NULL, NULL, NULL, NULL, 1, 2, 1),
(40, '0907654271001', 'GUZMAN ROSALES PEDRO ELIAS', '', 'DAULE, LA JOYA', '', '2021-03-29 15:06:39', NULL, NULL, NULL, NULL, 1, 2, 1),
(41, '1724248248', 'TUAREZ LOOR MARIO DANIEL', '022555212', 'QUITO PIO XII', '', '2021-03-29 15:31:16', 'TUAREZ LOOR MARIO DANIEL', '', '', '', 1, 2, 1),
(42, '	0917354524', 'ESPINOZA MENESES OSCAR DAVID', NULL, NULL, NULL, '2021-03-30 14:51:18', NULL, NULL, NULL, NULL, 1, 2, 1),
(43, '0923687791', 'Rolinda Soriana', '0999988857', 'Muey', 'rs2020@hotmail.com', '2021-03-30 15:01:23', '', '', '', '', 1, 2, 1),
(44, '0900687791', 'Valeria de Soriano', '2785697', '', 'valesoriano@hotmail.com', '2021-03-30 15:02:52', '', '', '', '', 1, 2, 1),
(45, 'Hotel 5 estrell', 'Hilton ', '2478963252', '', 'herrqw@hotmail.com', '2021-04-01 12:06:21', 'Hilton Colon', '', '', '', 2, 2, 1),
(46, '0999999999', 'corporacion el rosado', '', '', '', '2021-04-13 21:02:18', 'CORPORACION EL ROSADO', '', '', '', 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pisos`
--

CREATE TABLE `pisos` (
  `idpiso` int(11) NOT NULL,
  `nombre_piso` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pisos`
--

INSERT INTO `pisos` (`idpiso`, `nombre_piso`, `estado`) VALUES
(6, 'PRIMER PISO', 1),
(7, 'SEGUNDO PISO', 1),
(8, 'TERCER PISO', 1),
(9, 'TERRAZA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `idtipo` int(10) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `foto` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `idtipo`, `descripcion`, `proveedor`, `precio`, `existencia`, `dateadd`, `usuario_id`, `status`, `foto`) VALUES
(14, 1, 'Gaseosa', 46, '0.90', 20, '2021-04-18 17:11:52', 2, 1, 'img_producto.png'),
(15, 1, 'Pomita de Agua', 46, '0.60', 21, '2021-05-01 11:54:50', 2, 1, 'img_producto.png');

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
INSERT INTO entradas (codproducto,cantidad,precio,usuario_id)
VALUES (new.codproducto,new.existencia,new.precio,new.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `idtipo` int(11) NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`idtipo`, `tipo`, `status`) VALUES
(1, 'Cliente', 1),
(2, 'Proveedor', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `idtipo` int(10) NOT NULL,
  `producto_servicio` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`idtipo`, `producto_servicio`, `status`) VALUES
(1, 'Producto', 1),
(2, 'Servicio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estatus`) VALUES
(2, 'Diana Alfonzo', 'dianysro@gmail.com', 'Dianys', '8e185c1f1585b3530669966b63ad6bdb', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(50) NOT NULL,
  `idalojamiento` int(11) NOT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `totalventa` decimal(10,2) DEFAULT NULL,
  `usuario` int(11) NOT NULL,
  `estado` int(2) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activos`
--
ALTER TABLE `activos`
  ADD PRIMARY KEY (`idactivos`);

--
-- Indices de la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  ADD PRIMARY KEY (`idalojamiento`),
  ADD KEY `idhabitacion` (`idhabitacion`),
  ADD KEY `idpersona` (`idpersona`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consumo`
--
ALTER TABLE `consumo`
  ADD PRIMARY KEY (`idconsumo`),
  ADD KEY `idproducto` (`idproducto`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `idalojamiento` (`idalojamiento`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`nocotizacion`);

--
-- Indices de la tabla `cuentas_activos`
--
ALTER TABLE `cuentas_activos`
  ADD PRIMARY KEY (`idcta`),
  ADD KEY `idactivos` (`idactivos`);

--
-- Indices de la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD PRIMARY KEY (`idcta`),
  ADD KEY `idegreso` (`idegreso`);

--
-- Indices de la tabla `detalleautorizacion`
--
ALTER TABLE `detalleautorizacion`
  ADD PRIMARY KEY (`codautorizacion`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `nofactura` (`token_user`),
  ADD KEY `codproducto` (`codproducto`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`idegreso`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `codpersona` (`codpersona`) USING BTREE;

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`idhabitacion`),
  ADD KEY `idpiso` (`idpiso`),
  ADD KEY `idcategoria` (`idcategoria`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`idpago`),
  ADD KEY `idalojamiento` (`idalojamiento`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD PRIMARY KEY (`idpago`),
  ADD KEY `idpersona` (`idpersona`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`idpersona`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idtipo` (`idtipo`);

--
-- Indices de la tabla `pisos`
--
ALTER TABLE `pisos`
  ADD PRIMARY KEY (`idpiso`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idtipo` (`idtipo`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`idtipo`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`idtipo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `idalojamiento` (`idalojamiento`),
  ADD KEY `idproducto` (`idproducto`),
  ADD KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activos`
--
ALTER TABLE `activos`
  MODIFY `idactivos` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  MODIFY `idalojamiento` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `consumo`
--
ALTER TABLE `consumo`
  MODIFY `idconsumo` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `nocotizacion` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_activos`
--
ALTER TABLE `cuentas_activos`
  MODIFY `idcta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  MODIFY `idcta` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalleautorizacion`
--
ALTER TABLE `detalleautorizacion`
  MODIFY `codautorizacion` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `idegreso` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `idhabitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `idpago` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  MODIFY `idpago` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `pisos`
--
ALTER TABLE `pisos`
  MODIFY `idpiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  MODIFY `idtipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `idtipo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(50) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  ADD CONSTRAINT `alojamiento_ibfk_1` FOREIGN KEY (`idhabitacion`) REFERENCES `habitaciones` (`idhabitacion`),
  ADD CONSTRAINT `alojamiento_ibfk_2` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`idpersona`);

--
-- Filtros para la tabla `consumo`
--
ALTER TABLE `consumo`
  ADD CONSTRAINT `consumo_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`codproducto`),
  ADD CONSTRAINT `consumo_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `consumo_ibfk_4` FOREIGN KEY (`idalojamiento`) REFERENCES `alojamiento` (`idalojamiento`);

--
-- Filtros para la tabla `cuentas_activos`
--
ALTER TABLE `cuentas_activos`
  ADD CONSTRAINT `cuentas_activos_ibfk_1` FOREIGN KEY (`idactivos`) REFERENCES `activos` (`idactivos`);

--
-- Filtros para la tabla `cuentas_contables`
--
ALTER TABLE `cuentas_contables`
  ADD CONSTRAINT `cuentas_contables_ibfk_1` FOREIGN KEY (`idegreso`) REFERENCES `egresos` (`idegreso`);

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entradas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`codpersona`) REFERENCES `personas` (`idpersona`);

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`idcategoria`),
  ADD CONSTRAINT `habitaciones_ibfk_2` FOREIGN KEY (`idpiso`) REFERENCES `pisos` (`idpiso`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`idalojamiento`) REFERENCES `alojamiento` (`idalojamiento`),
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `pago_recibido`
--
ALTER TABLE `pago_recibido`
  ADD CONSTRAINT `pago_recibido_ibfk_1` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`idpersona`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`idtipo`) REFERENCES `tipo_persona` (`idtipo`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`idtipo`) REFERENCES `tipo_producto` (`idtipo`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idalojamiento`) REFERENCES `alojamiento` (`idalojamiento`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`codproducto`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
