-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-07-2022 a las 10:58:43
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
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_categoria`
--

INSERT INTO `t_categoria` (`id_categoria`, `categoria`) VALUES
(1, 'Test');

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
(2, 'ruta/de/la/imagen/nombre.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_producto`
--

CREATE TABLE `t_producto` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_media` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio_unitario` decimal(10,0) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `t_producto`
--

INSERT INTO `t_producto` (`id_producto`, `id_categoria`, `id_media`, `nombre`, `descripcion`, `precio_unitario`, `cantidad`) VALUES
(1, 1, 2, 'Test', 'Prueba', '60', 12);

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
-- Estructura Stand-in para la vista `vw_productos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_productos` (
`id_producto` int(11)
,`nombre` varchar(50)
,`descripcion` text
,`precio_unitario` decimal(10,0)
,`cantidad` int(11)
,`categoria` varchar(50)
,`imagen` varchar(150)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos`
--
DROP TABLE IF EXISTS `vw_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos`  AS SELECT `p`.`id_producto` AS `id_producto`, `p`.`nombre` AS `nombre`, `p`.`descripcion` AS `descripcion`, `p`.`precio_unitario` AS `precio_unitario`, `p`.`cantidad` AS `cantidad`, `c`.`categoria` AS `categoria`, `m`.`imagen` AS `imagen` FROM ((`t_categoria` `c` join `t_producto` `p` on(`p`.`id_categoria` = `c`.`id_categoria`)) join `t_media` `m` on(`p`.`id_media` = `m`.`id_media`))  ;

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
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `t_producto`
--
ALTER TABLE `t_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

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
