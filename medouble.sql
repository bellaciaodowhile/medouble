-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2025 a las 22:30:43
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medouble`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias_medicas`
--

CREATE TABLE `licencias_medicas` (
  `id` int(11) NOT NULL,
  `codigo_verificacion` varchar(50) NOT NULL,
  `rut_paciente` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `folio_licencia` varchar(50) NOT NULL,
  `lugar_otorgamiento` varchar(100) NOT NULL,
  `fecha_otorgamiento` date NOT NULL,
  `inst_salud_previsional` varchar(100) NOT NULL,
  `nombre_medico` varchar(100) NOT NULL,
  `rut_empleador` varchar(20) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `estado_tramitacion` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archivo_pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramitaciones`
--

CREATE TABLE `tramitaciones` (
  `id` int(11) NOT NULL,
  `licencia_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` varchar(50) NOT NULL,
  `entidad` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `licencias_medicas`
--
ALTER TABLE `licencias_medicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tramitaciones`
--
ALTER TABLE `tramitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licencia_id` (`licencia_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `licencias_medicas`
--
ALTER TABLE `licencias_medicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tramitaciones`
--
ALTER TABLE `tramitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tramitaciones`
--
ALTER TABLE `tramitaciones`
  ADD CONSTRAINT `tramitaciones_ibfk_1` FOREIGN KEY (`licencia_id`) REFERENCES `licencias_medicas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
