-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-01-2025 a las 17:20:37
-- Versión del servidor: 10.6.20-MariaDB-log
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbprofes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `POST_IMG`
--

CREATE TABLE `POST_IMG` (
  `id` int(11) NOT NULL,
  `img` text NOT NULL COMMENT 'nombres de las imagenes separados por comas',
  `type_opinion` tinyint(1) NOT NULL COMMENT '1 = opinion, 2 = response',
  `id_opinion_response` int(11) NOT NULL,
  `num_img` int(11) NOT NULL DEFAULT 0,
  `timestamp_create` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `POST_OPINION`
--

CREATE TABLE `POST_OPINION` (
  `id` int(11) NOT NULL,
  `teacher` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(250) NOT NULL,
  `id_form_grading` int(11) NOT NULL,
  `id_time_grading` int(11) NOT NULL,
  `id_accessibility` int(11) NOT NULL,
  `opinion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = no, 1 = si',
  `id_user` int(11) DEFAULT NULL,
  `num_img` int(11) NOT NULL DEFAULT 0,
  `timestamp_create` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp_update` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `POST_RESPONSE`
--

CREATE TABLE `POST_RESPONSE` (
  `id` int(11) NOT NULL,
  `id_opinion` int(11) NOT NULL,
  `opinion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = no, 1 = si',
  `id_user` int(11) DEFAULT NULL,
  `num_img` int(11) NOT NULL DEFAULT 0,
  `timestamp_create` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp_update` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REG_ACCESSIBILITY`
--

CREATE TABLE `REG_ACCESSIBILITY` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `REG_ACCESSIBILITY`
--

INSERT INTO `REG_ACCESSIBILITY` (`id`, `name`) VALUES
(1, 'Muy accesible'),
(2, 'Accesible'),
(3, 'Normal'),
(4, 'Difícil acceso'),
(5, 'Nada accesible'),
(6, 'No lo sé');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REG_FORM_GRADING`
--

CREATE TABLE `REG_FORM_GRADING` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `REG_FORM_GRADING`
--

INSERT INTO `REG_FORM_GRADING` (`id`, `name`) VALUES
(1, 'Muy simple'),
(2, 'Simple'),
(3, 'Justa'),
(4, 'Normal'),
(5, 'Estricta'),
(6, 'Muy estricta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REG_TIME_GRADING`
--

CREATE TABLE `REG_TIME_GRADING` (
  `id` int(11) NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `REG_TIME_GRADING`
--

INSERT INTO `REG_TIME_GRADING` (`id`, `name`) VALUES
(1, 'Muy lenta'),
(2, 'Lenta'),
(3, 'Normal'),
(4, 'Rápido'),
(5, 'Inmediato');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `REL_LIKES`
--

CREATE TABLE `REL_LIKES` (
  `id_user` int(11) NOT NULL,
  `type_opinion` tinyint(1) NOT NULL COMMENT '1 = opinion, 2 = response',
  `id_opinion_response` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SYS_ROLE`
--

CREATE TABLE `SYS_ROLE` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SYS_USER`
--

CREATE TABLE `SYS_USER` (
  `id` int(11) NOT NULL,
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_role` int(11) NOT NULL DEFAULT 3,
  `timestamp_create` timestamp NOT NULL DEFAULT current_timestamp(),
  `timestamp_update` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `VIEW_POST_STATS`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `VIEW_POST_STATS` (
`post_id` int(11)
,`num_likes` bigint(21)
,`num_responses` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `VIEW_RESPONSE_STATS`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `VIEW_RESPONSE_STATS` (
`response_id` int(11)
,`num_likes` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `VIEW_POST_STATS`
--
DROP TABLE IF EXISTS `VIEW_POST_STATS`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `VIEW_POST_STATS`  AS SELECT `po`.`id` AS `post_id`, count(distinct `rl`.`id_user`) AS `num_likes`, count(distinct `pr`.`id`) AS `num_responses` FROM ((`POST_OPINION` `po` left join `REL_LIKES` `rl` on(`rl`.`type_opinion` = 1 and `rl`.`id_opinion_response` = `po`.`id`)) left join `POST_RESPONSE` `pr` on(`pr`.`id_opinion` = `po`.`id`)) GROUP BY `po`.`id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `VIEW_RESPONSE_STATS`
--
DROP TABLE IF EXISTS `VIEW_RESPONSE_STATS`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `VIEW_RESPONSE_STATS`  AS SELECT `pr`.`id` AS `response_id`, count(distinct `rl`.`id_user`) AS `num_likes` FROM (`POST_RESPONSE` `pr` left join `REL_LIKES` `rl` on(`rl`.`type_opinion` = 2 and `rl`.`id_opinion_response` = `pr`.`id`)) GROUP BY `pr`.`id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `POST_IMG`
--
ALTER TABLE `POST_IMG`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `POST_OPINION`
--
ALTER TABLE `POST_OPINION`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `POST_RESPONSE`
--
ALTER TABLE `POST_RESPONSE`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `REG_ACCESSIBILITY`
--
ALTER TABLE `REG_ACCESSIBILITY`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `REG_FORM_GRADING`
--
ALTER TABLE `REG_FORM_GRADING`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `REG_TIME_GRADING`
--
ALTER TABLE `REG_TIME_GRADING`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `SYS_ROLE`
--
ALTER TABLE `SYS_ROLE`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `SYS_USER`
--
ALTER TABLE `SYS_USER`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `POST_IMG`
--
ALTER TABLE `POST_IMG`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `POST_OPINION`
--
ALTER TABLE `POST_OPINION`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `POST_RESPONSE`
--
ALTER TABLE `POST_RESPONSE`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `REG_ACCESSIBILITY`
--
ALTER TABLE `REG_ACCESSIBILITY`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `REG_FORM_GRADING`
--
ALTER TABLE `REG_FORM_GRADING`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `REG_TIME_GRADING`
--
ALTER TABLE `REG_TIME_GRADING`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `SYS_ROLE`
--
ALTER TABLE `SYS_ROLE`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SYS_USER`
--
ALTER TABLE `SYS_USER`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `SYS_USER`
--
ALTER TABLE `SYS_USER`
  ADD CONSTRAINT `SYS_USER_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `SYS_ROLE` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
