-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-06-2012 a las 23:26:47
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ctipaume`
--

--
-- Volcado de datos para la tabla `authitem`
--

INSERT INTO `AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('administrador', 2, '', NULL, 'N;'),
('configureRoles', 0, 'configure app roles', NULL, 'N;'),
('gerencia', 2, NULL, NULL, NULL),
('operativo', 2, '', NULL, 'N;');

--
-- Volcado de datos para la tabla `authitemchild`
--

INSERT INTO `AuthItemChild` (`parent`, `child`) VALUES
('administrador', 'configureRoles');


--
-- Volcado de datos para la tabla `authassignment`
--

INSERT INTO `AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('administrador', '4', NULL, 'N;'),
('gerencia', '10', NULL, 'N;'),
('gerencia', '9', NULL, 'N;'),
('operativo', '7', NULL, 'N;');

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `nombre`, `clave`, `rol`, `user`) VALUES
(4, 'admin@mvs.cl', 'Sr.Admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'administrador', 'admin'),
(7, 'operativo@mvs.cl', 'Sr.Op', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'operativo', 'op1'),
(9, 'g@gmail.com', 'Sr.Gerente', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'gerencia', 'gerente'),
(10, 'ger@ger.cl', 'gerente2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'gerencia', 'gerente2');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
