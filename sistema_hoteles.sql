-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 27-07-2025 a las 18:05:44
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_hoteles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, '1 estrella'),
(2, '2 estrellas'),
(3, '3 estrellas'),
(4, '4 estrellas'),
(5, '5 estrellas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costes_habitaciones`
--

DROP TABLE IF EXISTS `costes_habitaciones`;
CREATE TABLE IF NOT EXISTS `costes_habitaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `habitacion_id` int NOT NULL,
  `precio_por_noche` decimal(10,2) NOT NULL,
  `temporada` enum('alta','media','baja') COLLATE utf8mb4_unicode_ci DEFAULT 'media',
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  PRIMARY KEY (`id`),
  KEY `habitacion_id` (`habitacion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `costes_habitaciones`
--

INSERT INTO `costes_habitaciones` (`id`, `habitacion_id`, `precio_por_noche`, `temporada`, `moneda`) VALUES
(1, 1, 120.00, 'alta', 'USD'),
(2, 2, 80.00, 'media', 'USD'),
(3, 3, 60.00, 'baja', 'USD'),
(4, 20, 777.77, 'alta', 'USD'),
(5, 4, 45.00, 'baja', 'USD'),
(6, 5, 90.00, 'media', 'USD'),
(7, 6, 375.00, 'alta', 'USD'),
(8, 7, 85.00, 'media', 'USD'),
(9, 8, 95.00, 'media', 'USD'),
(10, 9, 360.00, 'alta', 'USD'),
(11, 10, 65.00, 'baja', 'USD'),
(12, 16, 7777.77, 'alta', 'USD'),
(13, 21, 85.99, 'baja', 'USD'),
(14, 22, 60.00, 'baja', 'USD'),
(15, 23, 600.00, 'alta', 'USD'),
(16, 24, 1000.00, 'baja', 'USD'),
(17, 25, 2.50, 'alta', 'USD'),
(18, 26, 2.00, 'alta', 'USD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

DROP TABLE IF EXISTS `habitaciones`;
CREATE TABLE IF NOT EXISTS `habitaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL,
  `tipo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacidad` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `hotel_id`, `tipo`, `capacidad`) VALUES
(1, 1, 'Suite frente al mar', 4),
(2, 1, 'Habitación doble', 2),
(3, 2, 'Cabaña familiar', 5),
(4, 2, 'Habitación individual', 1),
(5, 3, 'Habitación ejecutiva', 2),
(6, 3, 'Suite presidencial', 3),
(7, 4, 'Habitación matrimonial', 2),
(8, 4, 'Habitación colonial', 3),
(9, 5, 'Suite cafetera', 4),
(10, 5, 'Habitación estándar', 2),
(20, 17, 'Suite esencial', 7),
(21, 18, '5', 2),
(22, 19, 'Habitación Clasica', 2),
(23, 19, 'suite presidencial', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoteles`
--

DROP TABLE IF EXISTS `hoteles`;
CREATE TABLE IF NOT EXISTS `hoteles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT '0',
  `creado_por` int DEFAULT NULL,
  `provincia_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `fk_creado_por` (`creado_por`),
  KEY `fk_provincia_id` (`provincia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hoteles`
--

INSERT INTO `hoteles` (`id`, `nombre`, `descripcion`, `direccion`, `categoria_id`, `imagen`, `aprobado`, `creado_por`, `provincia_id`) VALUES
(1, 'Hotel Paraíso del Mar', 'Relájate frente al mar Caribe en un entorno exclusivo con playa privada, rodeado de naturaleza tropical en Isla Colón.', 'Playa Estrella, Isla Colón, Bocas del Toro', 5, 'paraiso_del_mar.jpg', 1, 3, 1),
(2, 'Hotel Sierra Verde', 'Vive la experiencia del ecoturismo entre montañas, senderos naturales y aire puro en el encantador Valle de Antón.', 'El Valle de Antón, Calle Los Helechos, Coclé', 4, 'sierra verde.jpg', 1, 3, 2),
(3, 'Hotel Ciudad Central', 'Comodidad ejecutiva en el corazón de la Zona Libre de Colón. Ideal para negocios y estadías corporativas.', 'Calle 3ra, Ciudad de Colón, frente a Zona Libre', 4, 'ciudad_central.jpg', 1, 3, 3),
(4, 'Hotel Colonial', 'Hospédate en una casa colonial restaurada, llena de historia y encanto en el centro de Penonomé.', 'Calle Real, Penonomé Centro, Coclé', 3, 'hotel colonial.jpg', 1, 3, 2),
(5, 'Hotel Ruta del Café', 'Descubre el mundo del café en un entorno natural privilegiado en Volcán, con vistas al majestuoso Barú.', 'Finca Cafetalera El Bambito, Volcán, Chiriquí', 4, 'ruta_cafe.jpg', 1, 3, 4),
(17, 'Hotel 7 palabras', 'Siente la verdadera esencia del descanso en un hotel rodeado de selva tropical, cultura y tranquilidad en Darién.', 'Carretera Panamericana, Metetí, Darién', 5, 'hotel_esencia_japonesa.jpg', 1, 1, 5),
(18, 'hotel bonito', 'Un refugio sencillo y acogedor en el corazón de Veraguas, ideal para desconectarte y explorar la región.', 'Calle Central, Santiago de Veraguas, Veraguas', 3, '6883a85b40335.jpeg', 1, 3, 10),
(19, 'hotel Panama', 'Disfruta del calor típico y la hospitalidad santeña en Las Tablas, capital del folklore panameño.', 'Calle Principal, Las Tablas, Los Santos', 2, '6883b709f0df0.jpg', 1, 3, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotel_instalacion`
--

DROP TABLE IF EXISTS `hotel_instalacion`;
CREATE TABLE IF NOT EXISTS `hotel_instalacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int DEFAULT NULL,
  `instalacion_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `instalacion_id` (`instalacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hotel_instalacion`
--

INSERT INTO `hotel_instalacion` (`id`, `hotel_id`, `instalacion_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 5),
(5, 1, 7),
(6, 2, 1),
(7, 2, 6),
(8, 2, 5),
(9, 3, 1),
(10, 3, 4),
(11, 3, 5),
(12, 3, 8),
(13, 4, 1),
(14, 4, 5),
(15, 5, 1),
(16, 5, 5),
(17, 5, 6),
(44, 17, 1),
(45, 17, 2),
(46, 17, 3),
(47, 17, 4),
(48, 17, 5),
(49, 17, 6),
(50, 17, 7),
(51, 17, 8),
(52, 18, 1),
(53, 18, 2),
(54, 18, 3),
(55, 19, 1),
(56, 19, 4),
(57, 19, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instalaciones`
--

DROP TABLE IF EXISTS `instalaciones`;
CREATE TABLE IF NOT EXISTS `instalaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `instalaciones`
--

INSERT INTO `instalaciones` (`id`, `nombre`) VALUES
(1, 'WiFi'),
(2, 'Piscina'),
(3, 'Spa'),
(4, 'Gimnasio'),
(5, 'Restaurante'),
(6, 'Estacionamiento'),
(7, 'Playa privada'),
(8, 'Centro de negocios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

DROP TABLE IF EXISTS `provincias`;
CREATE TABLE IF NOT EXISTS `provincias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`id`, `nombre`) VALUES
(1, 'Bocas del Toro'),
(2, 'Coclé'),
(3, 'Colón'),
(4, 'Chiriquí'),
(5, 'Darién'),
(6, 'Herrera'),
(7, 'Los Santos'),
(8, 'Panamá'),
(9, 'Panamá Oeste'),
(10, 'Veraguas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `hotel_id` int NOT NULL,
  `habitacion_id` int NOT NULL,
  `personas` int NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `habitacion_id` (`habitacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `hotel_id`, `habitacion_id`, `personas`, `total`, `fecha_entrada`, `fecha_salida`, `created_at`) VALUES
(3, 8, 1, 2, 1, NULL, '2025-01-01', '2025-01-02', '2025-07-25 14:09:23'),
(4, 2, 17, 20, 7, 24110.87, '2025-10-07', '2025-11-07', '2025-07-25 14:52:41'),
(6, 10, 19, 22, 2, 360.00, '2025-07-25', '2025-07-31', '2025-07-25 17:00:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','editor','cliente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cliente',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `correo`, `clave`, `sexo`, `rol`, `activo`, `created_at`) VALUES
(1, 'Jhon', 'Carlo', 'JhonSito', 'JhonCarloSito@gmail.com', '$2y$10$WnuekKza34qMow.NsvrXGeV7XkdoKHQ7O0OG3RpteGTvxMS.0Wb9e', 'M', 'cliente', 1, '2025-07-25 01:26:46'),
(2, 'Admin1', 'Principal', 'admin1', 'admin01@hotel.com', '$2y$10$gJZmyTjC6MiFz0u4LjAjRuw9e/8ckbFc9cEk9Rq7//1Akks/UDe2y', 'M', 'admin', 1, '2025-07-25 04:34:20'),
(3, 'Juan', 'Montruas', 'editor', 'editor@gmil.com', '$2y$10$D6JkMyC/2WuHKQv/NrIsC.hZLk966gxEWjjj/zIHKPMMiz97z9eji', 'F', 'editor', 1, '2025-07-25 04:35:38'),
(5, 'Yosue', 'Pineda', 'soladito', 'soldadito@gmail.com', '$2y$10$9Ai1KMvazxNuklfs9VLPTeAhZKq.CjJ5k1DUfTi.EOakqSY.wNJDm', 'M', 'editor', 1, '2025-07-25 09:29:22'),
(8, 'Alex', 'Pan', 'alexpan', 'alexpan@gmail.com', '$2y$10$L/PnMssNbvWqeigI8TfVEet8zKWyg7OW8vW0XzQ.zbfXWiIz.SwPu', 'M', 'cliente', 1, '2025-07-25 14:08:41'),
(10, 'Juan', 'Peréz', 'JuanPe', 'juanpereza@gmail.com', '$2y$10$IZHmftDp3wZg8nxPodkTx.KKmqFVSj0UCrLe19hY/4FdTW3irA5u6', 'M', 'cliente', 1, '2025-07-25 16:58:19'),
(11, 'Pepe', 'Pinzon', 'Peponcio', 'pepe@gmail.com', '$2y$10$J8Ho.vTeopLQXldZPH4F9ethW4EvZ6ohLe13zNPwXaF2nIO82YgGi', 'M', 'editor', 1, '2025-07-25 17:10:55'),
(12, 'Fulanito', 'Juares', 'jf', 'jf@gmail.com', '$2y$10$rCaSS/FfXcbXZwqinFw8Ruymq9nCa.ZApyXTwv75hA.obS7irNrWq', 'M', 'editor', 1, '2025-07-27 02:35:30'),
(13, 'Analia Solis', 'Pineda Peralta', 'Ana', 'analiaSolis@gmail.com', '$2y$10$TynZY5kD7i4qKjSeBIA4COTti6mHJvV4bJ19km/pV1vYhQuxKr1Ga', 'F', 'admin', 1, '2025-07-27 02:37:59'),
(14, 'José Peralta', 'Antonio Jhon', 'jhon', 'jhonJose@gmail.com', '$2y$10$63.3KiDumm57KQQLz.4Ao.beeMTomRCGnQS8XU9unjGCax2XY7ONq', 'M', 'cliente', 1, '2025-07-27 02:45:12'),
(15, 'Manuel del Carmen', 'Peralta Gomez', 'manuel', 'manuel@gamil.com', '$2y$10$CYRT0UzlwNhgTlBPWoP2zeCGEyebynFz/y05GA88kgMZp2NEFUlGi', 'M', 'editor', 1, '2025-07-27 03:35:35'),
(16, 'Angel de Elviondo', 'Garcia Perez', 'angelTrue', 'trueAngel@gmail.com', '$2y$10$6iOJ4bE32tE9fOqwNAUdnedf6UzhPSL.MFnBP5F4uDw5L4c6ihdSu', 'M', 'cliente', 1, '2025-07-27 03:37:23');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `hoteles`
--
ALTER TABLE `hoteles`
  ADD CONSTRAINT `fk_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_provincia_id` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hoteles_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `hotel_instalacion`
--
ALTER TABLE `hotel_instalacion`
  ADD CONSTRAINT `hotel_instalacion_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_instalacion_ibfk_2` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
