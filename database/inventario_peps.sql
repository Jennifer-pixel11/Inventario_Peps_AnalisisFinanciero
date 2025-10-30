-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2025 a las 02:57:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12
--base de datos
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
(1, 1, '2025-10-29', 80.0000, 20.00, 20.00),
(2, 2, '2025-10-29', 65.0000, 5.00, 0.00),
(3, 3, '2025-10-30', 90.0000, 15.00, 8.00),
(4, 2, '2025-10-29', 50.0000, 10.00, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('ENTRADA','SALIDA') NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` decimal(12,2) NOT NULL,
  `costo_unitario` decimal(12,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `lote_id` int(11) DEFAULT NULL,
  `nota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `producto_id`, `tipo`, `fecha`, `cantidad`, `costo_unitario`, `total`, `lote_id`, `nota`) VALUES
(1, 1, 'ENTRADA', '2025-10-29 00:00:00', 20.00, 80.0000, 1600.0000, 1, ''),
(2, 2, 'ENTRADA', '2025-10-29 00:00:00', 5.00, 65.0000, 325.0000, 2, ''),
(3, 2, 'SALIDA', '2025-10-29 00:00:00', 5.00, 65.0000, 325.0000, 2, ''),
(4, 3, 'ENTRADA', '2025-10-30 00:00:00', 15.00, 90.0000, 1350.0000, 3, ''),
(5, 3, 'SALIDA', '2025-10-30 00:00:00', 7.00, 90.0000, 630.0000, 3, ''),
(6, 2, 'ENTRADA', '2025-10-29 00:00:00', 10.00, 50.0000, 500.0000, 4, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(120) NOT NULL,
  `unidad` varchar(20) DEFAULT 'unidad',
  `stock` decimal(12,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `unidad`, `stock`, `creado_en`, `imagen`) VALUES
(1, '0001', 'Whey protein Animal', '20', 20.00, '2025-10-30 00:32:33', '/inventario_peps_web/public/uploads/img_6902b221b2acb4.84692897.jpg'),
(2, '0002', 'dymatize chocolate gourmet 1 6 libras', '10', 10.00, '2025-10-30 00:35:59', '/inventario_peps_web/public/uploads/img_6902b2ef1160a5.17570513.jpg'),
(3, '0003', 'Isopure Whey Isolate Protein Powder', '15', 8.00, '2025-10-30 01:28:25', '/inventario_peps_web/public/uploads/img_6902bf397c9792.37845204.jpg');

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
  ADD KEY `idx_mov_tipo_fecha` (`tipo`,`fecha`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`lote_id`) REFERENCES `lotes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
