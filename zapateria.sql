-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-06-2022 a las 18:45:26
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zapateria`
--
CREATE DATABASE IF NOT EXISTS `zapateria` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `zapateria`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AplicaDescuento` (IN `clienteId` INT)  BEGIN
	/**
    * 	Cantidad minima y maxima de producto adquirido que requiere el cliente para que se
	*	le aplique un descuento.
	*/
	SET @quantMinMaxProduct= 5;

	SET @conteo= (SELECT COUNT(*) FROM (
		SELECT SUM(cantidad) as cantidad,id_cliente FROM ventas
		GROUP BY id_producto
		HAVING id_cliente = clienteId AND cantidad = @quantMinMaxProduct
	) AS __result);
    
    
    IF @conteo > 0 THEN
		SELECT clienteId as id_cliente, true as aplica_descuento;
	ELSE
		SELECT clienteId as id_cliente, false as aplica_descuento;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CantidadClientes` ()  BEGIN
	SELECT COUNT(*)	AS cantidad_clientes FROM clientes;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ComprarProducto` (IN `id_producto` INT(11), IN `clienteId` INT, `cantidad` INT(10) UNSIGNED)  BEGIN
	SET @idVendedor= (SELECT id_vendedor FROM vendedor ORDER BY RAND() LIMIT 1);
    
    /**
     *	Verificar que haya en stock dicho producto
    **/
    SET @stock= (SELECT p.stock FROM productos p WHERE p.id_producto = id_producto);
    
    IF @stock > 0 AND @stock >= cantidad THEN
		INSERT INTO ventas (cantidad, id_vendedor, id_cliente, id_producto)
		VALUES (cantidad, @idVendedor, clienteId, id_producto);
	ELSE
		signal sqlstate '58115' set message_text = 'Ya no hay stock disponible para este producto.';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `NuevoProducto` (IN `marca` VARCHAR(24), IN `modelo` VARCHAR(32), IN `precio` FLOAT, IN `talla` FLOAT, IN `tipo` VARCHAR(24), IN `stock` INT)  BEGIN
INSERT INTO productos (marca, modelo, precio, talla, tipo, stock) VALUES (marca, modelo, precio,talla,tipo,stock);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerGanancias` ()  BEGIN
	SELECT SUM(gananciaxproducto) AS ganancia_total FROM (
		SELECT (SUM(v.cantidad) * p.precio) AS gananciaxproducto from ventas v, productos p
		WHERE p.id_producto = v.id_producto
		GROUP BY v.id_producto
    ) AS x;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerInversion` ()  BEGIN
	SELECT SUM(inversionxproducto) AS inversion_total FROM (
		SELECT (SUM(c.cantidad) * p.precio) AS inversionxproducto from compras c, productos p
		WHERE p.id_producto = c.id_producto
		GROUP BY c.id_producto
    ) AS x;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProductosComprados` ()  BEGIN
	SELECT SUM(cantidad) AS productos_comprados FROM compras;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProductosDisponibles` ()  BEGIN
    SET @cantidadVentas= (SELECT SUM(cantidad) FROM zapateria.ventas);
	SET @cantidadCompras= (SELECT SUM(cantidad) FROM zapateria.compras);
    
    SELECT CAST((@cantidadCompras - @cantidadVentas) AS DECIMAL(0)) AS productos_disponibles;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProductosTalla` (IN `medida` FLOAT)  BEGIN
	SELECT * FROM productos WHERE medida = talla;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProductosVendidos` ()  BEGIN
	SELECT SUM(vendido) AS productos_vendidos FROM (
		SELECT SUM(cantidad) AS vendido FROM ventas
		GROUP BY id_producto
	) AS x;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TipoUsuario` (IN `_correo` VARCHAR(56), IN `_contra` TINYTEXT)  BEGIN
	SELECT COUNT(*) as existe FROM clientes WHERE correo= _correo AND contra= _contra;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VentasXDia` (IN `fecha` DATE)  BEGIN
	SELECT v.id_venta, c.nombre as nombre_cliente, CONCAT(p.marca,' (',p.modelo,')') AS nombre_producto, v.cantidad, p.precio, v.fecha_de_venta FROM ventas v
    JOIN clientes c ON c.id_clientes = v.id_cliente
    JOIN productos p ON p.id_producto = v.id_producto
    WHERE v.fecha_de_venta = fecha 
    ORDER BY v.id_venta DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VentasXMes` (IN `date_month` DATE)  BEGIN
	SELECT v.id_venta, c.nombre as nombre_cliente, CONCAT(p.marca,' (',p.modelo,')') AS nombre_producto, v.cantidad, p.precio, v.fecha_de_venta FROM ventas v
    JOIN clientes c ON c.id_clientes = v.id_cliente
    JOIN productos p ON p.id_producto = v.id_producto
    WHERE MONTH(v.fecha_de_venta) = MONTH(date_month)
    ORDER BY v.id_venta DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VentasXSemana` (IN `date_start` DATE, IN `date_end` DATE)  BEGIN
	SELECT v.id_venta, c.nombre as nombre_cliente, CONCAT(p.marca,' (',p.modelo,')') AS nombre_producto, v.cantidad, p.precio, v.fecha_de_venta FROM ventas v
    JOIN clientes c ON c.id_clientes = v.id_cliente
    JOIN productos p ON p.id_producto = v.id_producto
    WHERE v.fecha_de_venta >= date_start AND v.fecha_de_venta <= date_end
    ORDER BY v.id_venta DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerAlmacen` ()  BEGIN
	SELECT CONCAT(p1.marca, ' (', p1.modelo, ')') AS producto, p2.nombre AS proveedor, (SELECT stock FROM productos WHERE id_producto=c.id_producto LIMIT 1) AS cantidad, c.fecha_de_compra FROM compras c 
    JOIN ventas v ON v.id_producto = c.id_producto
    JOIN productos p1 ON p1.id_producto = c.id_producto
    JOIN proveedor p2 on p2.id_proveedor = c.id_proveedor
    GROUP BY c.id_producto
    ORDER BY c.id_compra DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerCategoria` ()  BEGIN
	SELECT DISTINCT tipo FROM zapateria.productos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerProductos` ()  BEGIN
	SELECT * FROM productos
    ORDER BY id_producto DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerProductosXTipo` (IN `_tipo` VARCHAR(24))  BEGIN
	SELECT * FROM productos 
    WHERE stock > 0 AND tipo = _tipo
    ORDER BY id_producto DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ver_stock` (IN `id_producto_` INT(11), OUT `stock` INT(10) UNSIGNED)  BEGIN
	SELECT p.stock
    INTO stock 
    FROM productos p
    WHERE p.id_producto = id_producto_;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_clientes` int(11) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `correo` varchar(56) NOT NULL,
  `contra` tinytext NOT NULL,
  `edad` date NOT NULL,
  `rfc` char(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_clientes`, `nombre`, `correo`, `contra`, `edad`, `rfc`) VALUES
(1, 'vedos', 'gvss@outlook.com', 'KoiWAr2lg9VJc8rETuZFv7XHmYJZnb61yLe9HvekaAI=', '1987-01-01', '00000000000000'),
(2, 'cetphin', 'cetphin@hotmail.com', 'ommq1JLkbph927gQwV6IwpEgEJctEIuH7oDCLviF6+g=', '1987-01-02', '00000000000000'),
(3, 'xosies', '', '', '2000-01-03', '00000000000000'),
(4, 'ozotl', '', '', '2000-01-04', '00000000000000'),
(5, 'qavias', 'qavias.cl@gmail.com', 'KoiWAr2lg9VJc8rETuZFv7XHmYJZnb61yLe9HvekaAI=', '2000-01-05', '00000000000000'),
(6, 'inos', '', '', '2000-01-06', '00000000000000'),
(7, 'dozdon', '', '', '2000-01-07', '00000000000000'),
(8, 'elone', '', '', '2000-01-08', '00000000000000'),
(9, 'ciasis', '', '', '2000-01-09', '00000000000000'),
(10, 'Eager Robin', '', '', '2000-01-10', '00000000000000'),
(11, 'White Swallow', '', '', '2000-01-11', '00000000000000'),
(12, 'White Spirit', '', '', '2000-01-12', '00000000000000'),
(13, 'Red Dagger', '', '', '2000-01-13', '00000000000000'),
(14, 'Gorgeous Lion', '', '', '2000-01-14', '00000000000000'),
(15, 'Dramatic Raccoon', 'racc.nn_dram@gmail.com', 'jaLGLeut6l6a0pAQD5ZfAu1JhSmJj4VX7CJ93nQAyoo=', '2000-01-15', '00000000000000');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cliente_con_mascompras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cliente_con_mascompras` (
`id_cliente` int(11)
,`nombre` varchar(32)
,`comprado_total` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `id_proveedor` tinyint(3) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_de_compra` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `cantidad`, `id_proveedor`, `id_producto`, `fecha_de_compra`) VALUES
(1, 11, 10, 10, '2022-06-07'),
(2, 17, 1, 2, '2022-06-07'),
(3, 9, 15, 9, '2022-06-07'),
(4, 19, 13, 8, '2022-06-07'),
(5, 6, 9, 6, '2022-06-07'),
(6, 8, 9, 7, '2022-06-07'),
(7, 17, 1, 1, '2022-06-07'),
(8, 15, 1, 2, '2022-06-07'),
(9, 13, 8, 5, '2022-06-07'),
(10, 14, 8, 4, '2022-06-07'),
(11, 7, 13, 11, '2022-06-07'),
(12, 11, 13, 12, '2022-06-07'),
(13, 9, 13, 13, '2022-06-07'),
(14, 10, 13, 14, '2022-06-07'),
(15, 9, 14, 15, '2022-06-07'),
(16, 2, 1, 3, '2022-06-07'),
(24, 10, 6, 14, '2022-06-15'),
(25, 15, 6, 23, '2022-06-15'),
(26, 15, 5, 24, '2022-06-15'),
(27, 10, 17, 10, '2022-06-15'),
(28, 8, 4, 15, '2022-06-15'),
(29, 5, 13, 14, '2022-06-20');

--
-- Disparadores `compras`
--
DELIMITER $$
CREATE TRIGGER `PRODUCTOS_COM` AFTER INSERT ON `compras` FOR EACH ROW BEGIN
SET @newComprasIdProducto= new.id_producto;
SET @newComprasCantidad= new.cantidad;

UPDATE productos SET 
productos.stock= productos.stock + @newComprasCantidad WHERE productos.id_producto = @newComprasIdProducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `existencia_actual`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `existencia_actual` (
`id_producto` int(11)
,`marca` varchar(24)
,`modelo` varchar(32)
,`comprado_total` decimal(32,0)
,`vendido_total` decimal(32,0)
,`stock_actual` decimal(33,0)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `marca` varchar(24) NOT NULL,
  `modelo` varchar(32) NOT NULL,
  `precio` float(6,2) NOT NULL,
  `talla` float UNSIGNED DEFAULT NULL,
  `tipo` varchar(24) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `marca`, `modelo`, `precio`, `talla`, `tipo`, `stock`) VALUES
(1, 'Decorplast', 'Vietnam', 1500.00, 6.5, 'Deportivo', 0),
(2, 'Decorplast', 'Viertnam', 1600.00, 8, 'Urbano', 0),
(3, 'Decorplast', 'Malla', 1999.59, 5, 'Deportivo', 0),
(4, 'Tenis Union', '2032-m', 550.23, 7, 'Futbol - Deportivo', 0),
(5, 'Tenis Union', '1212', 600.00, 8, 'Futbol - Deportivo', 0),
(6, 'Anzen Work Shoes', 'work shoes', 659.59, 7, 'Urbano', 0),
(7, 'Anzen Work Shoes', 'work shoes', 659.59, 5.5, 'Urbano', 0),
(8, 'Open Sport', 'Sport A', 404.59, 4.5, 'Deportivo', 0),
(9, 'Mis Amores', 'Azul Cielo Princesa', 429.99, 2.5, 'Slides', 0),
(10, 'Basf', '1', 196.99, 6.5, 'Deportivo', 9),
(11, 'Open Sport', 'CQ9356', 1399.99, 5, 'Deportivo', 0),
(12, 'Open Sport', '73800', 579.00, 6, 'Deportivo', 0),
(13, 'Open Sport', 'Blackwood 1952', 950.59, 8.5, 'Urbano', 0),
(14, 'Open Sport', 'ALASKA', 297.00, 7, 'Urbano', 0),
(15, 'Bony Bony', '2022', 350.00, 1.25, 'Zapato', 4),
(23, 'Senzul', 'Generico 1', 600.00, 6, 'Deportivo', 6),
(24, 'Producto 2', 'Modelo 1', 1000.00, 7, 'Tenis', 7);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `producto_con_mayorcosto`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `producto_con_mayorcosto` (
`id_proveedor` tinyint(3)
,`id_producto` int(11)
,`marca` varchar(24)
,`modelo` varchar(32)
,`precio` float(6,2)
,`talla` float unsigned
,`tipo` varchar(24)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `producto_masvendido`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `producto_masvendido` (
`id_producto` int(11)
,`marca` varchar(24)
,`modelo` varchar(32)
,`total_ventas` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `producto_mas_comprado`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `producto_mas_comprado` (
`id_proveedor` tinyint(3)
,`proveedor` varchar(32)
,`marca_producto` varchar(24)
,`modelo_producto` varchar(32)
,`cantidad` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` tinyint(3) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `telefono` varchar(16) DEFAULT NULL,
  `correo` varchar(48) DEFAULT NULL,
  `direccion` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `telefono`, `correo`, `direccion`) VALUES
(1, 'Decorplast México', '0000000000', 'decorplast.mx@gmail.com', 'Av. San Luis Tlatilco No. 34 México, Edo. Méx. . México.'),
(2, 'Viposa', '0000000000', 'viposa@gmail.com', '532 CEP 89500-000 Caçador, Santa Catarina . Brasil.'),
(3, 'Inovasuela', '0000000000', 'inovasuela@gmail.com', 'BLVD. HIDALGO LEON, GUANAJUATO . México.'),
(4, 'NEHU', '0000000000', 'nehu@gmail.com', 'SAN BERNARDO 300 PURISIMA, GUANAJUATO . México.'),
(5, 'Manufacturera Bigger', '0000000000', 'bigger.m@gmail.com', '137 SAN FRANCISCO DEL RINCON, GUANAJUATO . México.'),
(6, 'New Blessing', '0000000000', 'new.blessing@gmail.com', 'CRA 26 No. 16 - 30 sur BOGOTA, BOGOTA Colombia.'),
(7, 'Dist. Internacionales Alcrey', '0000000000', 'alcrey.internacional@gmail.com', 'CALLE CADENA 59 NEZAHUALCOYOTL., ESTADO DE MEXICO. México.'),
(8, 'Tenis Union', '0000000000', 'tenis.union@gmail.com', 'Michoacán 138 San Francisco del Rincón, Guanajuato . México.'),
(9, 'Anzen Work Shoes', '0000000000', 'anzen.shoes@gmail.com', 'Monterrey, Nuevo León México.'),
(10, 'Basf', '0000000000', 'basf.support@gmail.com', 'Insurgentes Sur No. 975 Distrito Federal . México.'),
(11, 'Tekno Worker', '0000000000', 'tekno.worker@gmail.com', 'FELIX U GOMEZ NORTE 1987 A MONTERREY, NUEVO LEON México.'),
(12, 'Uniformes en Linea', '0000000000', 'uniformes.support@gmail.com', 'Reforma No. 2914-A Monterrey, Nuevo León México.'),
(13, 'Open Sport', '0000000000', 'open.sport@gmail.com', 'Juárez Norte 97 Querétaro, Querétaro . México.'),
(14, 'Bony Bony', '0000000000', 'bonybony.support@gmail.com', '16 de eseptiembre 677 toluca, ciudad de mexico . México.'),
(15, 'México de Mis Amores', '0000000000', 'amores.shoes@gmail.com', 'Tlatelolco 607 Leon, Guanajuato . México.'),
(16, 'Arturo Calcaneo', '2292098172', 'aa.calcaneob@gmail.com', 'Fracc. Villa Rica I\r\nKaty Ripoll de Melo 1108'),
(17, 'Proveedor de Prueba', '2291774412', 'prueba@gmail.com', '....');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(2) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `correo` varchar(56) NOT NULL,
  `contra` tinytext NOT NULL,
  `edad` date NOT NULL,
  `sueldo` float(6,2) NOT NULL,
  `telefono` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `nombre`, `correo`, `contra`, `edad`, `sueldo`, `telefono`) VALUES
(1, 'Arturo Calcaneo', 'aa.calcaneob@gmail.com', '7Iuj8QVMLim7X91DvXMpYdPr2kg55FjvbVRbtrHF2u8=', '2000-09-27', 2500.00, '2292098172'),
(2, 'Jose Carmen', 'josec_mrn@gmail.com', 'chBlo59+T9Kvr1Tjq7b4CewWQ2sz4sjpTDQKjMon8zM=', '1997-11-01', 2000.00, '2292098170'),
(3, 'Rodolfo Martinez', '', '', '1997-11-02', 2000.00, '2292098171'),
(4, 'Gustavo Nieves', '', '', '1997-11-03', 1700.00, '2292098172'),
(5, 'Fatima Rubí', '', '', '1997-11-04', 2000.00, '2292098173'),
(6, 'Coni Gutierrez', '', '', '1997-11-05', 2000.00, '2292098174'),
(7, 'María Cano', '', '', '1997-11-06', 2000.00, '2292098175'),
(8, 'Mariano Lopez', '', '', '1997-11-07', 2000.00, '2292098176'),
(9, 'Lola Montaño', '', '', '1997-11-08', 2000.00, '2292098177'),
(10, 'Corina Ramirez', '', '', '1997-11-09', 2000.00, '2292098178'),
(11, 'Edwin Cruz', 'edwin.c@gmail.com', 'pLAUTOxU2wL0rvDMmhhb3v+ZjEicqDd2uzxCoEOlp2k=', '1997-11-10', 2000.00, '2292098179'),
(12, 'Maldonado Hernandez', '', '', '1997-11-11', 2000.00, '2292098180'),
(13, 'Phillip Gusteauf', '', '', '1997-11-12', 2000.00, '2292098181'),
(14, 'Eder Altamirano', '', '', '1997-11-13', 2000.00, '2292098182'),
(15, 'Enrique Segoviano', '', '', '1997-11-14', 2000.00, '2292098183');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_de_venta` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `cantidad`, `id_vendedor`, `id_cliente`, `id_producto`, `fecha_de_venta`) VALUES
(1, 1, 1, 2, 7, '2022-04-01'),
(2, 2, 1, 9, 6, '2022-04-02'),
(3, 1, 6, 7, 10, '2022-04-07'),
(4, 1, 10, 15, 15, '2022-04-07'),
(5, 1, 10, 15, 1, '2022-04-07'),
(6, 1, 5, 10, 3, '2022-04-07'),
(7, 1, 14, 8, 2, '2022-04-10'),
(8, 1, 5, 14, 9, '2022-04-10'),
(9, 1, 3, 12, 8, '2022-04-10'),
(10, 1, 7, 11, 14, '2022-04-10'),
(11, 1, 12, 3, 13, '2022-04-11'),
(12, 2, 12, 5, 12, '2022-04-11'),
(13, 2, 8, 6, 11, '2022-04-13'),
(14, 1, 9, 13, 4, '2022-04-13'),
(15, 3, 1, 5, 5, '2022-04-13'),
(17, 1, 1, 14, 5, '2022-05-26'),
(18, 1, 1, 14, 11, '2022-05-26'),
(21, 1, 1, 14, 5, '2022-05-26'),
(22, 1, 1, 14, 11, '2022-05-26'),
(24, 1, 1, 4, 23, '2022-06-15'),
(25, 1, 1, 7, 23, '2022-06-15'),
(32, 1, 11, 9, 23, '2022-06-15'),
(33, 1, 13, 14, 23, '2022-06-15'),
(34, 1, 10, 6, 14, '2022-06-15'),
(35, 1, 12, 9, 14, '2022-06-15'),
(36, 1, 15, 13, 14, '2022-06-15'),
(37, 1, 7, 1, 14, '2022-06-15'),
(38, 1, 3, 11, 14, '2022-06-15'),
(39, 1, 11, 5, 24, '2022-06-18'),
(40, 1, 1, 10, 15, '2022-06-18'),
(41, 1, 4, 12, 24, '2022-06-18'),
(42, 1, 9, 1, 24, '2022-06-19'),
(43, 1, 4, 1, 24, '2022-06-19'),
(44, 1, 1, 1, 24, '2022-06-19'),
(45, 1, 12, 1, 23, '2022-06-19'),
(46, 1, 6, 1, 23, '2022-06-20'),
(47, 1, 8, 1, 14, '2022-06-20'),
(48, 1, 14, 1, 14, '2022-06-20'),
(49, 1, 13, 1, 15, '2022-06-20'),
(50, 1, 2, 1, 10, '2022-06-20'),
(52, 1, 12, 1, 24, '2022-06-20'),
(53, 1, 4, 1, 23, '2022-06-20'),
(54, 1, 3, 1, 15, '2022-06-20'),
(55, 1, 4, 1, 24, '2022-06-20'),
(56, 1, 12, 1, 14, '2022-06-20'),
(57, 1, 2, 1, 23, '2022-06-20'),
(58, 1, 9, 1, 23, '2022-06-20'),
(59, 1, 14, 1, 24, '2022-06-20'),
(60, 1, 7, 1, 15, '2022-06-20'),
(61, 1, 8, 1, 14, '2022-06-20'),
(62, 1, 4, 1, 14, '2022-06-20'),
(63, 5, 4, 1, 14, '2022-06-20');

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `PRODUCTOS_VEN` AFTER INSERT ON `ventas` FOR EACH ROW BEGIN

SET @newCantidad= new.cantidad;
SET @newIdProducto= new.id_producto;

UPDATE productos SET productos.stock = productos.stock - @newCantidad WHERE productos.id_producto = @newIdProducto;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cliente_con_mascompras`
--
DROP TABLE IF EXISTS `cliente_con_mascompras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cliente_con_mascompras`  AS  select `c`.`id_clientes` AS `id_cliente`,`c`.`nombre` AS `nombre`,sum(`v`.`cantidad`) AS `comprado_total` from (`ventas` `v` join `clientes` `c` on(`c`.`id_clientes` = `v`.`id_cliente`)) group by `v`.`id_cliente` order by sum(`v`.`cantidad`) desc limit 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `existencia_actual`
--
DROP TABLE IF EXISTS `existencia_actual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `existencia_actual`  AS  select `p`.`id_producto` AS `id_producto`,`p`.`marca` AS `marca`,`p`.`modelo` AS `modelo`,sum(`c`.`cantidad`) AS `comprado_total`,sum(`v`.`cantidad`) AS `vendido_total`,sum(`c`.`cantidad`) - sum(`v`.`cantidad`) AS `stock_actual` from ((`compras` `c` join `productos` `p` on(`p`.`id_producto` = `c`.`id_producto`)) join `ventas` `v` on(`v`.`id_producto` = `c`.`id_producto`)) group by `c`.`id_producto` order by `c`.`id_producto` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `producto_con_mayorcosto`
--
DROP TABLE IF EXISTS `producto_con_mayorcosto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `producto_con_mayorcosto`  AS  select `c`.`id_proveedor` AS `id_proveedor`,`p`.`id_producto` AS `id_producto`,`p`.`marca` AS `marca`,`p`.`modelo` AS `modelo`,`p`.`precio` AS `precio`,`p`.`talla` AS `talla`,`p`.`tipo` AS `tipo` from (`compras` `c` join `productos` `p`) where `p`.`precio` = (select max(`productos`.`precio`) from `productos`) and `c`.`id_producto` = `p`.`id_producto` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `producto_masvendido`
--
DROP TABLE IF EXISTS `producto_masvendido`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `producto_masvendido`  AS  select `v`.`id_producto` AS `id_producto`,`p`.`marca` AS `marca`,`p`.`modelo` AS `modelo`,sum(`v`.`cantidad`) AS `total_ventas` from (`ventas` `v` join `productos` `p` on(`p`.`id_producto` = `v`.`id_producto`)) group by `v`.`id_producto` order by sum(`v`.`cantidad`) desc limit 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `producto_mas_comprado`
--
DROP TABLE IF EXISTS `producto_mas_comprado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `producto_mas_comprado`  AS  select `p1`.`id_proveedor` AS `id_proveedor`,`p1`.`nombre` AS `proveedor`,`p2`.`marca` AS `marca_producto`,`p2`.`modelo` AS `modelo_producto`,`c`.`cantidad` AS `cantidad` from ((`compras` `c` join `proveedor` `p1` on(`p1`.`id_proveedor` = `c`.`id_proveedor`)) join `productos` `p2` on(`c`.`id_producto` = `p2`.`id_producto`)) group by `c`.`cantidad` order by `c`.`cantidad` desc limit 1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_clientes`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `compras_ibfk_1` (`id_proveedor`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_producto` (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_clientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_clientes`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
