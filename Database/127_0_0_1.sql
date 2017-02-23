-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2017 a las 22:41:44
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_access`
--
CREATE DATABASE IF NOT EXISTS `control_access` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `control_access`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesspoints`
--

CREATE TABLE `accesspoints` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `url` varchar(45) NOT NULL,
  `icon` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `assignedpoints`
--

CREATE TABLE `assignedpoints` (
  `id` int(11) NOT NULL,
  `Users_id` int(11) NOT NULL,
  `AccessPoints_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `description` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `description`) VALUES
(1, 'admin'),
(2, 'default');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `description` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `states`
--

INSERT INTO `states` (`id`, `description`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `identification` varchar(10) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `States_id` int(11) NOT NULL,
  `Roles_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `identification`, `firstName`, `lastName`, `email`, `mobile`, `password`, `States_id`, `Roles_id`) VALUES
(1, '1151962831', 'Steven', 'Ceballos', 'ti.ven97@hotmail.com', '3154233573', 'admin.123', 1, 1),
(2, '1234587963', 'Andres', 'Banguera', 'sinfocali@gmail.com', '3125478756', 'andres.123', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userslogin`
--

CREATE TABLE `userslogin` (
  `id` int(11) NOT NULL,
  `dateLogin` date NOT NULL,
  `hourLogin` datetime(6) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `operativeSystem` varchar(35) DEFAULT NULL,
  `browser` varchar(35) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `Users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesspoints`
--
ALTER TABLE `accesspoints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD UNIQUE KEY `url_UNIQUE` (`url`);

--
-- Indices de la tabla `assignedpoints`
--
ALTER TABLE `assignedpoints`
  ADD PRIMARY KEY (`id`,`Users_id`,`AccessPoints_id`),
  ADD KEY `fk_Users_has_AccessPoints_AccessPoints1_idx` (`AccessPoints_id`),
  ADD KEY `fk_Users_has_AccessPoints_Users1_idx` (`Users_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description_UNIQUE` (`description`);

--
-- Indices de la tabla `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description_UNIQUE` (`description`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`States_id`,`Roles_id`),
  ADD UNIQUE KEY `identification_UNIQUE` (`identification`),
  ADD KEY `fk_Users_States_idx` (`States_id`),
  ADD KEY `fk_Users_Roles1_idx` (`Roles_id`);

--
-- Indices de la tabla `userslogin`
--
ALTER TABLE `userslogin`
  ADD PRIMARY KEY (`id`,`Users_id`),
  ADD KEY `fk_UsersLogin_Users1_idx` (`Users_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesspoints`
--
ALTER TABLE `accesspoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `assignedpoints`
--
ALTER TABLE `assignedpoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `userslogin`
--
ALTER TABLE `userslogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `assignedpoints`
--
ALTER TABLE `assignedpoints`
  ADD CONSTRAINT `fk_Users_has_AccessPoints_AccessPoints1` FOREIGN KEY (`AccessPoints_id`) REFERENCES `accesspoints` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Users_has_AccessPoints_Users1` FOREIGN KEY (`Users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_Users_Roles1` FOREIGN KEY (`Roles_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Users_States` FOREIGN KEY (`States_id`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `userslogin`
--
ALTER TABLE `userslogin`
  ADD CONSTRAINT `fk_UsersLogin_Users1` FOREIGN KEY (`Users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
