-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2025 a las 02:47:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario_peps`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `costo_unitario` decimal(12,4) NOT NULL,
  `cantidad_inicial` decimal(12,2) NOT NULL,
  `cantidad_disponible` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id`, `producto_id`, `fecha`, `costo_unitario`, `cantidad_inicial`, `cantidad_disponible`) VALUES
(9, 12, '2025-11-14', 40.0000, 10.00, 9.00),
(10, 13, '2025-11-14', 45.0000, 15.00, 15.00),
(11, 14, '2025-11-14', 45.0000, 10.00, 10.00),
(12, 15, '2025-11-14', 60.0000, 10.00, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `proveedor_id` int(10) UNSIGNED DEFAULT NULL,
  `num_doc_compra` varchar(50) DEFAULT NULL,
  `tipo` enum('ENTRADA','SALIDA') NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` decimal(12,2) NOT NULL,
  `costo_unitario` decimal(12,4) NOT NULL,
  `precio_venta` decimal(12,4) DEFAULT NULL,
  `total_venta` decimal(14,4) DEFAULT NULL,
  `total` decimal(14,4) NOT NULL,
  `lote_id` int(11) DEFAULT NULL,
  `nota` varchar(255) DEFAULT NULL,
  `cliente_nombre` varchar(150) DEFAULT NULL,
  `cliente_nit` varchar(20) DEFAULT NULL,
  `num_doc_venta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `producto_id`, `proveedor_id`, `num_doc_compra`, `tipo`, `fecha`, `cantidad`, `costo_unitario`, `precio_venta`, `total_venta`, `total`, `lote_id`, `nota`, `cliente_nombre`, `cliente_nit`, `num_doc_venta`) VALUES
(18, 12, 6, 'FC-0001', 'ENTRADA', '2025-11-14 00:00:00', 10.00, 40.0000, NULL, NULL, 400.0000, 9, 'Compra de Proteína Whey', NULL, NULL, NULL),
(19, 13, 5, 'FC-0002', 'ENTRADA', '2025-11-14 00:00:00', 15.00, 45.0000, NULL, NULL, 675.0000, 10, 'Compra de Pre Entreno Lab Nutrition', NULL, NULL, NULL),
(20, 14, 4, 'FC-0003', 'ENTRADA', '2025-11-14 00:00:00', 10.00, 45.0000, NULL, NULL, 450.0000, 11, 'Compra de aminoácidos Nutrex ', NULL, NULL, NULL),
(21, 15, 7, 'FC-0004', 'ENTRADA', '2025-11-14 00:00:00', 10.00, 60.0000, NULL, NULL, 600.0000, 12, 'Compra de Creatina Muscle Tech', NULL, NULL, NULL),
(22, 12, NULL, NULL, 'SALIDA', '2025-11-14 00:00:00', 1.00, 40.0000, 80.0000, 80.0000, 40.0000, 9, 'Venta de Proteína Whey', 'Emely Alvarez', '1234-000001-123-4', 'FV-001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(120) NOT NULL,
  `unidad` varchar(20) DEFAULT 'unidad',
  `proveedor_id` int(10) UNSIGNED DEFAULT NULL,
  `stock` decimal(12,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `unidad`, `proveedor_id`, `stock`, `creado_en`, `imagen`) VALUES
(12, '0001', 'Whey protein Animal', 'lb', 6, 9.00, '2025-11-14 22:46:14', 'public/img/productos/1763160374_100-whey-protein-Animal.jpg'),
(13, '0002', 'Pre Entreno Lab Nutrition', 'lb', 5, 15.00, '2025-11-14 22:46:42', 'public/img/productos/1763160402_Pre Entreno  Lab Nutrition.png'),
(14, '0003', 'BPI Sports - BEST EAA - Essential Amino Acids EAA', 'lb', 4, 11.00, '2025-11-14 22:47:37', 'public/img/productos/1763160457_BPI Sports - BEST EAA - Essential Amino Acids EAA.jpg'),
(15, '0004', 'Muscle Tech Creatina Monohidrato Polvo Platino', 'lb', 7, 11.00, '2025-11-14 22:48:55', 'public/img/productos/1763160535_Muscle Tech Creatina Monohidrato Polvo Platino.jpg'),
(16, '0005', 'Nike Team Breathe - Camiseta para hombre, L', 'L', 8, 5.00, '2025-11-15 01:13:41', 'public/img/productos/1763169221_Nike Team Breathe - Camiseta para hombre, L.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_empresa` varchar(150) NOT NULL,
  `contacto_nombre` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre_empresa`, `contacto_nombre`, `telefono`, `email`, `direccion`, `creado_en`) VALUES
(4, 'Suplementos El Salvador', 'Suplementos El Salvador', '7852 2032', 'suplesal503@outlook.es', 'San Salvador, El Salvador', '2025-11-14 22:41:59'),
(5, 'Suplementos Xtreme - El Salvador ', 'Suplementos Xtreme - El Salvador ', '7924 8255', 'suplementosxtremesv@gmail.com', 'San Salvador, El Salvador', '2025-11-14 22:44:10'),
(6, 'Suplementos BodyFit El Salvador', 'Suplementos BodyFit El Salvador', '7883-4348', 'joe.argueta81@icloud.com', 'San Salvador, El Salvador', '2025-11-14 22:45:41'),
(7, 'GNC ', 'GNC El Salvador', '2264-9450', 'soporte@gnc.com.sv', 'P.º Gral. Escalón, San Salvador', '2025-11-14 22:48:08'),
(8, 'SportLine', 'SportLine', '2509-1700', 'atencion@sportline.com.sv', 'La Libertad, La Libertad, Antiguo Cuscatlán', '2025-11-15 01:12:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lotes_prod_fecha` (`producto_id`,`fecha`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lote_id` (`lote_id`),
  ADD KEY `idx_mov_prod_fecha` (`producto_id`,`fecha`),
  ADD KEY `idx_mov_tipo_fecha` (`tipo`,`fecha`),
  ADD KEY `fk_mov_proveedor` (`proveedor_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_productos_proveedor` (`proveedor_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `lotes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `fk_mov_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`lote_id`) REFERENCES `lotes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
