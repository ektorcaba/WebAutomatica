-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2021 a las 12:05:15
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `automatica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `spintitle` text NOT NULL,
  `spintext` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domains`
--

CREATE TABLE `domains` (
  `id` int(11) NOT NULL,
  `domain` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generated_spintext`
--

CREATE TABLE `generated_spintext` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `spintitle` text NOT NULL,
  `spintext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `keywords`
--

CREATE TABLE `keywords` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `amazon_term` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `indexed` int(11) DEFAULT NULL,
  `cache_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `url` text NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `domain_id`, `key`, `value`) VALUES
(1, 1, 'sitename', 'Web automatica'),
(2, 1, 'slogan', 'Una web automatica'),
(3, 1, 'main_color', '#ff5987'),
(4, 1, 'secondary_color', '#ccc'),
(5, 1, 'logo', '/assets/logo.svg'),
(6, 1, 'main_text', '<h1>Portada HTML</h1>'),
(7, 1, 'default_term', 'palabra clave'),
(8, 1, 'source_text', '{texto de prueba|texto principal}'),
(9, 1, 'description', 'Descripcion SEO para google'),
(10, 1, 'maintext_color', '#ffffff'),
(11, 1, 'secondarytext_color', '#222'),
(12, 1, 'link_color', '#222222'),
(13, 1, 'style_menu', 'vertical'),
(14, 1, 'num_category_results', '42'),
(15, 1, 'num_results', '36'),
(16, 1, 'num_related_results', '15'),
(17, 1, 'amazon_tag', 'domain.com.es-21'),
(18, 1, 'apikey', 'AAAAAAAAAAAAAAAAAAAAAAA'),
(19, 1, 'apisecret', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
(20, 1, 'cachedir', 'cache/'),
(21, 1, 'interlinking', '40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `generated_spintext`
--
ALTER TABLE `generated_spintext`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `generated_spintext`
--
ALTER TABLE `generated_spintext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
