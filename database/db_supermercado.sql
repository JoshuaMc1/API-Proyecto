-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2022 a las 04:22:47
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_supermercado`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_actualizar_media` (IN `nMediaID` INT, IN `nPathImage` VARCHAR(150))  DETERMINISTIC UPDATE
    t_media
SET
    imagen = nPathImage
WHERE
    id_media = nMediaID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_actualizar_producto` (IN `nProductID` INT, IN `nCategory` INT, IN `nName` VARCHAR(50), IN `nDescription` TEXT, IN `nUnitPrice` DECIMAL(10,2), IN `nQuantity` INT)  DETERMINISTIC UPDATE
    t_producto 
SET
    id_categoria = nCategory,
    nombre = nName,
    descripcion = nDescription,
    precio_unitario = nUnitPrice,
    cantidad = nQuantity
WHERE
    id_producto = nProductID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_crear_producto` (IN `nidCategory` INT, IN `nidMedia` INT, IN `nName` VARCHAR(50), IN `nDescription` TEXT, IN `nUnirPrice` DECIMAL(10,2), IN `nQuantity` INT)  DETERMINISTIC INSERT INTO t_producto(
    id_categoria,
    id_media,
    nombre,
    descripcion,
    precio_unitario,
    cantidad
)
VALUES(
    nidCategory,
    nidMedia,
    nName,
    nDescription,
    nUnirPrice,
    nQuantity
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_eliminar_producto` (IN `nProductId` INT)  DETERMINISTIC UPDATE
    t_producto
SET
    status = '0'
WHERE
    id_producto = nProductId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_guardar_categoria` (IN `nCategory` VARCHAR(50))  DETERMINISTIC INSERT INTO t_categoria(categoria)
VALUES(nCategory)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_guardar_imagen` (IN `nPathImage` VARCHAR(150))  DETERMINISTIC INSERT INTO t_media(imagen)
VALUES(nPathImage)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_obtener_producto` (IN `nProductId` INT)  DETERMINISTIC SELECT
    p.id_producto AS id_producto,
    p.nombre AS nombre,
    p.descripcion AS descripcion,
    p.precio_unitario AS precio_unitario,
    p.cantidad AS cantidad,
    c.categoria AS categoria,
    m.imagen AS imagen
FROM
    (
        (
            t_categoria c
        JOIN t_producto p
        ON
            (
                p.id_categoria = c.id_categoria
            )
        )
    JOIN t_media m
    ON
        (p.id_media = m.id_media)
    )
WHERE p.id_producto = nProductId$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_categoria`
--

CREATE TABLE `t_categoria` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_categoria`
--

INSERT INTO `t_categoria` (`id_categoria`, `categoria`, `status`) VALUES
(1, 'Test', 1),
(2, 'Verduras', 1),
(3, 'Bebidas', 1),
(4, 'Frutas', 1),
(5, 'Higiene Personal', 1),
(6, 'Automedicación', 1),
(7, 'Alimentos Preparados', 1),
(8, 'Bebidas Alcohólicas', 1),
(9, 'Harinas y Pan', 1),
(10, 'Confitería/Dulcería', 1),
(11, 'Abarrotes', 1),
(12, 'Lácteos', 1),
(13, 'Categoria de Prueba 2', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_detalle_factura`
--

CREATE TABLE `t_detalle_factura` (
  `id_detalle` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(50) NOT NULL,
  `impuesto` decimal(10,0) NOT NULL,
  `descuento` decimal(10,0) NOT NULL,
  `sub_total` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `id_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_factura`
--

CREATE TABLE `t_factura` (
  `id_factura` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_genero`
--

CREATE TABLE `t_genero` (
  `id_genero` int(11) NOT NULL,
  `genero` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_info_usuarios`
--

CREATE TABLE `t_info_usuarios` (
  `id_info` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `nombre_de_usuario` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `edad` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_media`
--

CREATE TABLE `t_media` (
  `id_media` int(11) NOT NULL,
  `imagen` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_media`
--

INSERT INTO `t_media` (`id_media`, `imagen`) VALUES
(1, 'http://localhost/www/API-Proyecto/uploads/nophoto.jpg'),
(2, 'http://localhost/www/API-PROYECTO/uploads/Pepsi-OuiWYWc3tY/imagen.jpg'),
(3, 'http://localhost/www/API-PROYECTO/uploads/Tomate-vDNGU2UQUz/imagen.jpg'),
(4, 'http://localhost/www/API-PROYECTO/uploads/Tomate-16McCytkEn/imagen.jpg'),
(5, 'http://localhost/www/API-PROYECTO/uploads/Manzana-roja-VbbON1nFJ0/imagen.jpg'),
(6, 'http://localhost/www/API-PROYECTO/uploads/Manzana-verde-6Xj5QWIQlS/imagen.jpg'),
(7, 'http://localhost/www/API-PROYECTO/uploads/Pera-blK3yk75EV/imagen.jpg'),
(8, 'http://localhost/www/API-PROYECTO/uploads/Sandia-NgBtM6x9A2/imagen.jpg'),
(9, 'http://localhost/www/API-PROYECTO/uploads/Repollo-5LrabQhxhQ/imagen.jpg'),
(10, 'http://localhost/www/API-PROYECTO/uploads/Lechuga-rfJAwj4YPU/imagen.jpg'),
(11, 'http://localhost/www/API-PROYECTO/uploads/Pasta-Dental-Colgate-MYmOQYd09i/imagen.jpg'),
(12, 'http://localhost/www/API-PROYECTO/uploads/Azulcar-El-Cañal-kUt1DDURjQ/imagen.jpg'),
(13, 'http://localhost/www/API-PROYECTO/uploads/Mayonesa-Jgux0d1wo2/imagen.jpg'),
(14, 'http://localhost/www/API-PROYECTO/uploads/Leche-Sula-LPTpufRTyC/imagen.jpg'),
(15, 'http://localhost/www/API-PROYECTO/uploads/Leche-Condensada-La-Lechera-236QJgdlPT/imagen.jpg'),
(16, 'http://localhost/www/API-PROYECTO/uploads/Caramelos-de-leche-Diana-NFdwddcKin/imagen.jpg'),
(17, 'http://localhost/www/API-PROYECTO/uploads/Naranjas-Frescas-2MYIVl9ydU/imagen.jpg'),
(18, 'http://localhost/www/API-PROYECTO/uploads/Aguacate-nR2LlhGbR9/imagen.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_producto`
--

CREATE TABLE `t_producto` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_media` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_producto`
--

INSERT INTO `t_producto` (`id_producto`, `id_categoria`, `id_media`, `nombre`, `descripcion`, `precio_unitario`, `cantidad`, `status`) VALUES
(1, 1, 1, 'Test', 'Primer ejemplo test', '60.00', 12, 0),
(2, 3, 2, 'Pepsi', 'Refresco', '17.50', 70, 1),
(3, 2, 3, 'Tomate', 'Producto fresco', '7.29', 89, 1),
(4, 2, 4, 'Tomate', 'Producto fresco', '7.29', 89, 0),
(5, 4, 5, 'Manzana roja', 'Producto fresco', '16.30', 50, 1),
(6, 4, 6, 'Manzana verde', 'Producto fresco', '16.30', 50, 1),
(7, 4, 7, 'Pera', 'Producto fresco', '19.45', 50, 1),
(8, 4, 8, 'Sandia', 'Producto fresco', '27.36', 20, 1),
(9, 2, 9, 'Repollo', 'Producto fresco', '27.36', 32, 1),
(10, 2, 10, 'Lechuga', 'Producto fresco', '20.00', 37, 1),
(11, 5, 11, 'Pasta Dental Colgate', 'Pasta dental triple acción Colgate', '24.50', 46, 1),
(12, 11, 12, 'Azulcar El Cañal', 'AZUCAR CANAL 920GRMS', '10.50', 23, 1),
(13, 11, 13, 'Mayonesa', 'Mayonesa Baldom', '17.80', 34, 1),
(14, 12, 14, 'Leche Sula', 'Leche deslactosada', '21.30', 56, 1),
(15, 12, 15, 'Leche Condensada La Lechera', 'Leche condensada', '15.00', 21, 1),
(16, 10, 16, 'Caramelos de leche Diana', '', '10.00', 27, 1),
(17, 4, 17, 'Naranjas Frescas', '', '7.50', 40, 1),
(18, 4, 18, 'Aguacate', 'Aguacate fresco', '6.50', 37, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_rol`
--

CREATE TABLE `t_rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_status`
--

CREATE TABLE `t_status` (
  `id_status` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuarios`
--

CREATE TABLE `t_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_usuarios`
--

INSERT INTO `t_usuarios` (`id_usuario`, `correo`, `clave`, `status`) VALUES
(1, 'joshua15mclean@gmail.com', '123456', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_venta`
--

CREATE TABLE `t_venta` (
  `id_venta` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_categorias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_categorias` (
`id` int(11)
,`categoria` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_productos` (
`id_producto` int(11)
,`nombre` varchar(50)
,`descripcion` text
,`precio_unitario` decimal(10,2)
,`cantidad` int(11)
,`categoria` varchar(50)
,`imagen` varchar(150)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_categorias`
--
DROP TABLE IF EXISTS `vw_categorias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_categorias`  AS SELECT `t_categoria`.`id_categoria` AS `id`, `t_categoria`.`categoria` AS `categoria` FROM `t_categoria` WHERE `t_categoria`.`status` = '1''1'  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos`
--
DROP TABLE IF EXISTS `vw_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos`  AS SELECT `p`.`id_producto` AS `id_producto`, `p`.`nombre` AS `nombre`, `p`.`descripcion` AS `descripcion`, `p`.`precio_unitario` AS `precio_unitario`, `p`.`cantidad` AS `cantidad`, `c`.`categoria` AS `categoria`, `m`.`imagen` AS `imagen` FROM ((`t_categoria` `c` join `t_producto` `p` on(`p`.`id_categoria` = `c`.`id_categoria`)) join `t_media` `m` on(`p`.`id_media` = `m`.`id_media`)) WHERE `p`.`status` = 1 ORDER BY `p`.`id_producto` ASC  ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `t_categoria`
--
ALTER TABLE `t_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `t_detalle_factura`
--
ALTER TABLE `t_detalle_factura`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `t_factura`
--
ALTER TABLE `t_factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `t_genero`
--
ALTER TABLE `t_genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `t_info_usuarios`
--
ALTER TABLE `t_info_usuarios`
  ADD PRIMARY KEY (`id_info`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `t_media`
--
ALTER TABLE `t_media`
  ADD PRIMARY KEY (`id_media`);

--
-- Indices de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_media` (`id_media`);

--
-- Indices de la tabla `t_rol`
--
ALTER TABLE `t_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `t_status`
--
ALTER TABLE `t_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indices de la tabla `t_usuarios`
--
ALTER TABLE `t_usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `t_venta`
--
ALTER TABLE `t_venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_categoria`
--
ALTER TABLE `t_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `t_detalle_factura`
--
ALTER TABLE `t_detalle_factura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_factura`
--
ALTER TABLE `t_factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_genero`
--
ALTER TABLE `t_genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `t_info_usuarios`
--
ALTER TABLE `t_info_usuarios`
  MODIFY `id_info` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_media`
--
ALTER TABLE `t_media`
  MODIFY `id_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `t_rol`
--
ALTER TABLE `t_rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `t_status`
--
ALTER TABLE `t_status`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `t_usuarios`
--
ALTER TABLE `t_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `t_venta`
--
ALTER TABLE `t_venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t_detalle_factura`
--
ALTER TABLE `t_detalle_factura`
  ADD CONSTRAINT `t_detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `t_factura` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detalle_factura_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `t_producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detalle_factura_ibfk_3` FOREIGN KEY (`id_status`) REFERENCES `t_status` (`id_status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_factura`
--
ALTER TABLE `t_factura`
  ADD CONSTRAINT `t_factura_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `t_venta` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_info_usuarios`
--
ALTER TABLE `t_info_usuarios`
  ADD CONSTRAINT `t_info_usuarios_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `t_genero` (`id_genero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_info_usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `t_rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_info_usuarios_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_producto`
--
ALTER TABLE `t_producto`
  ADD CONSTRAINT `t_producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `t_categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_producto_ibfk_2` FOREIGN KEY (`id_media`) REFERENCES `t_media` (`id_media`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_venta`
--
ALTER TABLE `t_venta`
  ADD CONSTRAINT `t_venta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `t_info_usuarios` (`id_info`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
