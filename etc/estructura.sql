-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-07-2012 a las 05:24:54
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `mvscl_ctipaume`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AuthAssignment`
--

CREATE TABLE IF NOT EXISTS `AuthAssignment` (
  `itemname` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  `userid` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  `bizrule` text COLLATE utf8_spanish2_ci,
  `data` text COLLATE utf8_spanish2_ci,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AuthItem`
--

CREATE TABLE IF NOT EXISTS `AuthItem` (
  `name` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_spanish2_ci,
  `bizrule` text COLLATE utf8_spanish2_ci,
  `data` text COLLATE utf8_spanish2_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AuthItemChild`
--

CREATE TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camionArrendado`
--

CREATE TABLE IF NOT EXISTS `camionArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `capacidad` decimal(12,2) NOT NULL,
  `pesoOVolumen` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `consumoPromedio` decimal(12,2) NOT NULL,
  `coeficienteDeTrato` decimal(12,2) NOT NULL,
  `produccionMinima` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camionPropio`
--

CREATE TABLE IF NOT EXISTS `camionPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `codigo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `capacidad` decimal(12,2) NOT NULL,
  `pesoOVolumen` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `consumoPromedio` decimal(12,2) NOT NULL,
  `coeficienteDeTrato` decimal(12,2) NOT NULL,
  `produccionMinima` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargaCombCamionArrendado`
--

CREATE TABLE IF NOT EXISTS `cargaCombCamionArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petroleoLts` decimal(12,2) NOT NULL,
  `kmCarguio` decimal(12,2) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioUnitario` int(11) NOT NULL,
  `valorTotal` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `tipoCombustible_id` int(11) NOT NULL,
  `supervisorCombustible_id` int(11) NOT NULL,
  `rCamionArrendado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cargaCombPropio_faena1` (`faena_id`),
  KEY `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id`),
  KEY `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id`),
  KEY `fk_cargaCombCamionArrendado_rCamionArrendado1` (`rCamionArrendado_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargaCombCamionPropio`
--

CREATE TABLE IF NOT EXISTS `cargaCombCamionPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petroleoLts` decimal(12,2) NOT NULL,
  `kmCarguio` decimal(12,2) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioUnitario` int(11) NOT NULL,
  `valorTotal` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `tipoCombustible_id` int(11) NOT NULL,
  `supervisorCombustible_id` int(11) NOT NULL,
  `rCamionPropio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cargaCombPropio_faena1` (`faena_id`),
  KEY `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id`),
  KEY `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id`),
  KEY `fk_cargaCombCamionPropio_rCamionPropio1` (`rCamionPropio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargaCombEquipoArrendado`
--

CREATE TABLE IF NOT EXISTS `cargaCombEquipoArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petroleoLts` decimal(12,2) NOT NULL,
  `hCarguio` decimal(12,2) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioUnitario` int(11) NOT NULL,
  `valorTotal` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `tipoCombustible_id` int(11) NOT NULL,
  `supervisorCombustible_id` int(11) NOT NULL,
  `rEquipoArrendado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cargaCombPropio_faena1` (`faena_id`),
  KEY `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id`),
  KEY `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id`),
  KEY `fk_cargaCombEquipoArrendado_rEquipoArrendado1` (`rEquipoArrendado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargaCombEquipoPropio`
--

CREATE TABLE IF NOT EXISTS `cargaCombEquipoPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petroleoLts` decimal(12,2) NOT NULL,
  `hCarguio` decimal(12,2) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioUnitario` int(11) NOT NULL,
  `valorTotal` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `tipoCombustible_id` int(11) NOT NULL,
  `supervisorCombustible_id` int(11) NOT NULL,
  `rEquipoPropio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cargaCombPropio_faena1` (`faena_id`),
  KEY `fk_cargaCombPropio_tipoCombustible1` (`tipoCombustible_id`),
  KEY `fk_cargaCombPropio_supervisorCombustible1` (`supervisorCombustible_id`),
  KEY `fk_cargaCombEquipoPropio_rEquipoPropio1` (`rEquipoPropio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chofer`
--

CREATE TABLE IF NOT EXISTS `chofer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `rut` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraRepuestoCamionArrendado`
--

CREATE TABLE IF NOT EXISTS `compraRepuestoCamionArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repuesto` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `montoNeto` int(11) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `unidad` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'U',
  `rCamionArrendado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compraRepuestoCamionArrendado_rCamionArrendado1` (`rCamionArrendado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraRepuestoCamionPropio`
--

CREATE TABLE IF NOT EXISTS `compraRepuestoCamionPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repuesto` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `montoNeto` int(11) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `unidad` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'U',
  `rCamionPropio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compraRepuestoCamionPropio_rCamionPropio1` (`rCamionPropio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraRepuestoEquipoArrendado`
--

CREATE TABLE IF NOT EXISTS `compraRepuestoEquipoArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repuesto` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `montoNeto` int(11) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `unidad` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'U',
  `rEquipoArrendado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compraRepuestoEquipoArrendado_rEquipoArrendado1` (`rEquipoArrendado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraRepuestoEquipoPropio`
--

CREATE TABLE IF NOT EXISTS `compraRepuestoEquipoPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repuesto` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `montoNeto` int(11) NOT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `unidad` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'U',
  `rEquipoPropio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compraRepuestoEquipoPropio_rEquipoPropio1` (`rEquipoPropio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destino`
--

CREATE TABLE IF NOT EXISTS `destino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipoArrendado`
--

CREATE TABLE IF NOT EXISTS `equipoArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `precioUnitario` int(11) NOT NULL,
  `horasMin` decimal(12,2) DEFAULT NULL,
  `valorHora` int(11) NOT NULL,
  `consumoEsperado` decimal(12,2) NOT NULL,
  `propietario_id` int(11) NOT NULL,
  `coeficienteDeTrato` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equipoArrendado_propietario1` (`propietario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipoPropio`
--

CREATE TABLE IF NOT EXISTS `equipoPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `codigo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `precioUnitario` int(11) NOT NULL,
  `horasMin` decimal(12,2) DEFAULT NULL,
  `consumoEsperado` decimal(12,2) NOT NULL,
  `valorHora` int(11) NOT NULL,
  `coeficienteDeTrato` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faena`
--

CREATE TABLE IF NOT EXISTS `faena` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeChofer`
--

CREATE TABLE IF NOT EXISTS `informeChofer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chofer` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `camion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `produccionDia` decimal(12,2) DEFAULT NULL,
  `produccionMinima` decimal(12,2) DEFAULT NULL,
  `coeficienteCombustible` decimal(12,2) DEFAULT NULL,
  `gastoCombustible` decimal(12,2) DEFAULT NULL,
  `diferencia` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeConsumoCamion`
--

CREATE TABLE IF NOT EXISTS `informeConsumoCamion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `consumoReal` decimal(12,2) DEFAULT NULL,
  `consumoGps` decimal(12,2) DEFAULT NULL,
  `consumoSugerido` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeConsumoMaquinaria`
--

CREATE TABLE IF NOT EXISTS `informeConsumoMaquinaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `consumo` decimal(12,2) DEFAULT NULL,
  `consumoEsperado` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeDetalleGastoCombustible`
--

CREATE TABLE IF NOT EXISTS `informeDetalleGastoCombustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `reporte` int(11) DEFAULT NULL,
  `operario` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `maquina` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `petroleoLts` decimal(12,2) DEFAULT NULL,
  `kmCarguio` decimal(12,2) DEFAULT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioUnitario` int(11) DEFAULT NULL,
  `valorTotal` decimal(12,2) DEFAULT NULL,
  `faena` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipoCombustible` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `supervisorCombustible` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeDetalleGastoRepuesto`
--

CREATE TABLE IF NOT EXISTS `informeDetalleGastoRepuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `reporte` int(11) DEFAULT NULL,
  `operario` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `maquina` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `repuesto` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `montoNeto` int(11) DEFAULT NULL,
  `guia` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `factura` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeGastoCombustible`
--

CREATE TABLE IF NOT EXISTS `informeGastoCombustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `centroGestion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `consumoLts` decimal(12,2) DEFAULT NULL,
  `consumoPesos` decimal(12,2) DEFAULT NULL,
  `fInicio` date DEFAULT NULL,
  `fFin` date DEFAULT NULL,
  `maquina_id` int(11) DEFAULT NULL,
  `operador_id` int(11) DEFAULT NULL,
  `centroGestion_id` int(11) DEFAULT NULL,
  `tipo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeGastoRepuesto`
--

CREATE TABLE IF NOT EXISTS `informeGastoRepuesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `consumoPesos` decimal(12,2) DEFAULT NULL,
  `fInicio` date DEFAULT NULL,
  `fFin` date DEFAULT NULL,
  `propiosOArrendados` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `maquina_id` int(11) DEFAULT NULL,
  `operador_id` int(11) DEFAULT NULL,
  `tipo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeOperario`
--

CREATE TABLE IF NOT EXISTS `informeOperario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `consumoPromedio` decimal(12,2) DEFAULT NULL,
  `horas` decimal(12,2) DEFAULT NULL,
  `valorHora` int(11) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `operario` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `coeficiente` decimal(12,2) DEFAULT NULL,
  `horasContratadas` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeProduccionCamiones`
--

CREATE TABLE IF NOT EXISTS `informeProduccionCamiones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `chofer` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `centroGestion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `totalTransportado` decimal(12,2) DEFAULT NULL,
  `produccion` decimal(12,2) DEFAULT NULL,
  `produccionReal` decimal(12,2) DEFAULT NULL,
  `diferencia` decimal(12,2) DEFAULT NULL,
  `totalCobro` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeProduccionMaquinaria`
--

CREATE TABLE IF NOT EXISTS `informeProduccionMaquinaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `centroGestion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pu` decimal(12,2) DEFAULT NULL,
  `horas` decimal(12,2) DEFAULT NULL,
  `produccion` decimal(12,2) DEFAULT NULL,
  `horasMin` decimal(12,2) DEFAULT NULL,
  `produccionMin` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informeResultados`
--

CREATE TABLE IF NOT EXISTS `informeResultados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operador` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `centroGestion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `produccion` decimal(12,2) DEFAULT NULL,
  `combustible` decimal(12,2) DEFAULT NULL,
  `repuesto` decimal(12,2) DEFAULT NULL,
  `resultado` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operador`
--

CREATE TABLE IF NOT EXISTS `operador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `rut` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `origen`
--

CREATE TABLE IF NOT EXISTS `origen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `origenDestino_faena`
--

CREATE TABLE IF NOT EXISTS `origenDestino_faena` (
  `origen_id` int(11) NOT NULL,
  `destino_id` int(11) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `pu` decimal(12,2) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kmRecorridos` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico` (`faena_id`,`destino_id`,`origen_id`),
  KEY `fk_origendestino_faena_origen1` (`origen_id`),
  KEY `fk_origendestino_faena_destino1` (`destino_id`),
  KEY `fk_origendestino_faena_faena1` (`faena_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietario`
--

CREATE TABLE IF NOT EXISTS `propietario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `rut` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rCamionArrendado`
--

CREATE TABLE IF NOT EXISTS `rCamionArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `reporte` int(11) NOT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `ordenCompra` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `camionArrendado_id` int(11) NOT NULL,
  `chofer_id` int(11) NOT NULL,
  `kmInicial` decimal(12,2) DEFAULT NULL,
  `kmFinal` decimal(12,2) DEFAULT NULL,
  `kmGps` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reporte_UNIQUE` (`reporte`),
  KEY `fk_rCamionArrendado_camionArrendado1` (`camionArrendado_id`),
  KEY `fk_rCamionArrendado_chofer1` (`chofer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rCamionPropio`
--

CREATE TABLE IF NOT EXISTS `rCamionPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `reporte` int(11) NOT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `camionPropio_id` int(11) NOT NULL,
  `chofer_id` int(11) NOT NULL,
  `kmInicial` decimal(12,2) DEFAULT NULL,
  `kmFinal` decimal(12,2) DEFAULT NULL,
  `kmGps` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reporte_UNIQUE` (`reporte`),
  KEY `fk_rCamionPropio_camionPropio1` (`camionPropio_id`),
  KEY `fk_rCamionPropio_chofer1` (`chofer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rEquipoArrendado`
--

CREATE TABLE IF NOT EXISTS `rEquipoArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `reporte` int(11) NOT NULL,
  `ordenCompra` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `hInicial` decimal(12,2) DEFAULT NULL,
  `hFinal` decimal(12,2) DEFAULT NULL,
  `horas` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `equipoArrendado_id` int(11) NOT NULL,
  `operador_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reporte_UNIQUE` (`reporte`),
  KEY `fk_rEquipoPropio_faena1` (`faena_id`),
  KEY `fk_rEquipoArrendado_equipoArrendado1` (`equipoArrendado_id`),
  KEY `fk_rEquipoArrendado_operador1` (`operador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rEquipoPropio`
--

CREATE TABLE IF NOT EXISTS `rEquipoPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `reporte` int(11) NOT NULL,
  `observaciones` text COLLATE utf8_spanish_ci,
  `equipoPropio_id` int(11) NOT NULL,
  `hInicial` decimal(12,2) DEFAULT NULL,
  `hFinal` decimal(12,2) DEFAULT NULL,
  `horas` decimal(12,2) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `operador_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reporte_UNIQUE` (`reporte`),
  KEY `fk_rEquipoPropio_equipoPropio1` (`equipoPropio_id`),
  KEY `fk_rEquipoPropio_faena1` (`faena_id`),
  KEY `fk_rEquipoPropio_operador1` (`operador_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisorCombustible`
--

CREATE TABLE IF NOT EXISTS `supervisorCombustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `rut` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoCombustible`
--

CREATE TABLE IF NOT EXISTS `tipoCombustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajeCamionArrendado`
--

CREATE TABLE IF NOT EXISTS `viajeCamionArrendado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nVueltas` int(11) NOT NULL,
  `totalTransportado` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `kmRecorridos` decimal(12,2) NOT NULL DEFAULT '0.00',
  `faena_id` int(11) NOT NULL,
  `rCamionArrendado_id` int(11) NOT NULL,
  `origendestino_faena_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_viajesCamionPropio_faena1` (`faena_id`),
  KEY `fk_viajeArrendado_rCamionArrendado1` (`rCamionArrendado_id`),
  KEY `fk_viajeCamionArrendado_origendestino_faena1` (`origendestino_faena_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajeCamionPropio`
--

CREATE TABLE IF NOT EXISTS `viajeCamionPropio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nVueltas` int(11) NOT NULL,
  `totalTransportado` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `kmRecorridos` decimal(12,2) NOT NULL DEFAULT '0.00',
  `rCamionPropio_id` int(11) NOT NULL,
  `faena_id` int(11) NOT NULL,
  `origendestino_faena_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_viajesCamionPropio_rCamionPropio1` (`rCamionPropio_id`),
  KEY `fk_viajesCamionPropio_faena1` (`faena_id`),
  KEY `fk_viajeCamionPropio_origendestino_faena1` (`origendestino_faena_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=21 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `AuthAssignment`
--
ALTER TABLE `AuthAssignment`
  ADD CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `AuthItemChild`
--
ALTER TABLE `AuthItemChild`
  ADD CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cargaCombCamionArrendado`
--
ALTER TABLE `cargaCombCamionArrendado`
  ADD CONSTRAINT `fk_cargaCombPropio_faena10` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_tipoCombustible10` FOREIGN KEY (`tipoCombustible_id`) REFERENCES `tipoCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_supervisorCombustible10` FOREIGN KEY (`supervisorCombustible_id`) REFERENCES `supervisorCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombCamionArrendado_rCamionArrendado1` FOREIGN KEY (`rCamionArrendado_id`) REFERENCES `rCamionArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cargaCombCamionPropio`
--
ALTER TABLE `cargaCombCamionPropio`
  ADD CONSTRAINT `fk_cargaCombPropio_faena1` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_tipoCombustible1` FOREIGN KEY (`tipoCombustible_id`) REFERENCES `tipoCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_supervisorCombustible1` FOREIGN KEY (`supervisorCombustible_id`) REFERENCES `supervisorCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombCamionPropio_rCamionPropio1` FOREIGN KEY (`rCamionPropio_id`) REFERENCES `rCamionPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cargaCombEquipoArrendado`
--
ALTER TABLE `cargaCombEquipoArrendado`
  ADD CONSTRAINT `fk_cargaCombPropio_faena110` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_tipoCombustible110` FOREIGN KEY (`tipoCombustible_id`) REFERENCES `tipoCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_supervisorCombustible110` FOREIGN KEY (`supervisorCombustible_id`) REFERENCES `supervisorCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombEquipoArrendado_rEquipoArrendado1` FOREIGN KEY (`rEquipoArrendado_id`) REFERENCES `rEquipoArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cargaCombEquipoPropio`
--
ALTER TABLE `cargaCombEquipoPropio`
  ADD CONSTRAINT `fk_cargaCombPropio_faena11` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_tipoCombustible11` FOREIGN KEY (`tipoCombustible_id`) REFERENCES `tipoCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombPropio_supervisorCombustible11` FOREIGN KEY (`supervisorCombustible_id`) REFERENCES `supervisorCombustible` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cargaCombEquipoPropio_rEquipoPropio1` FOREIGN KEY (`rEquipoPropio_id`) REFERENCES `rEquipoPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compraRepuestoCamionArrendado`
--
ALTER TABLE `compraRepuestoCamionArrendado`
  ADD CONSTRAINT `fk_compraRepuestoCamionArrendado_rCamionArrendado1` FOREIGN KEY (`rCamionArrendado_id`) REFERENCES `rCamionArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compraRepuestoCamionPropio`
--
ALTER TABLE `compraRepuestoCamionPropio`
  ADD CONSTRAINT `fk_compraRepuestoCamionPropio_rCamionPropio1` FOREIGN KEY (`rCamionPropio_id`) REFERENCES `rCamionPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compraRepuestoEquipoArrendado`
--
ALTER TABLE `compraRepuestoEquipoArrendado`
  ADD CONSTRAINT `fk_compraRepuestoEquipoArrendado_rEquipoArrendado1` FOREIGN KEY (`rEquipoArrendado_id`) REFERENCES `rEquipoArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compraRepuestoEquipoPropio`
--
ALTER TABLE `compraRepuestoEquipoPropio`
  ADD CONSTRAINT `fk_compraRepuestoEquipoPropio_rEquipoPropio1` FOREIGN KEY (`rEquipoPropio_id`) REFERENCES `rEquipoPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `equipoArrendado`
--
ALTER TABLE `equipoArrendado`
  ADD CONSTRAINT `fk_equipoArrendado_propietario1` FOREIGN KEY (`propietario_id`) REFERENCES `propietario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `origenDestino_faena`
--
ALTER TABLE `origenDestino_faena`
  ADD CONSTRAINT `fk_origendestino_faena_origen1` FOREIGN KEY (`origen_id`) REFERENCES `origen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_origendestino_faena_destino1` FOREIGN KEY (`destino_id`) REFERENCES `destino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_origendestino_faena_faena1` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rCamionArrendado`
--
ALTER TABLE `rCamionArrendado`
  ADD CONSTRAINT `fk_rCamionArrendado_camionArrendado1` FOREIGN KEY (`camionArrendado_id`) REFERENCES `camionArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rCamionArrendado_chofer1` FOREIGN KEY (`chofer_id`) REFERENCES `chofer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rCamionPropio`
--
ALTER TABLE `rCamionPropio`
  ADD CONSTRAINT `fk_rCamionPropio_camionPropio1` FOREIGN KEY (`camionPropio_id`) REFERENCES `camionPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rCamionPropio_chofer1` FOREIGN KEY (`chofer_id`) REFERENCES `chofer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rEquipoArrendado`
--
ALTER TABLE `rEquipoArrendado`
  ADD CONSTRAINT `fk_rEquipoPropio_faena10` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rEquipoArrendado_equipoArrendado1` FOREIGN KEY (`equipoArrendado_id`) REFERENCES `equipoArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rEquipoArrendado_operador1` FOREIGN KEY (`operador_id`) REFERENCES `operador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rEquipoPropio`
--
ALTER TABLE `rEquipoPropio`
  ADD CONSTRAINT `fk_rEquipoPropio_equipoPropio1` FOREIGN KEY (`equipoPropio_id`) REFERENCES `equipoPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rEquipoPropio_faena1` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rEquipoPropio_operador1` FOREIGN KEY (`operador_id`) REFERENCES `operador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `viajeCamionArrendado`
--
ALTER TABLE `viajeCamionArrendado`
  ADD CONSTRAINT `fk_viajesCamionPropio_faena10` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viajeArrendado_rCamionArrendado1` FOREIGN KEY (`rCamionArrendado_id`) REFERENCES `rCamionArrendado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viajeCamionArrendado_origendestino_faena1` FOREIGN KEY (`origendestino_faena_id`) REFERENCES `origenDestino_faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `viajeCamionPropio`
--
ALTER TABLE `viajeCamionPropio`
  ADD CONSTRAINT `fk_viajesCamionPropio_rCamionPropio1` FOREIGN KEY (`rCamionPropio_id`) REFERENCES `rCamionPropio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viajesCamionPropio_faena1` FOREIGN KEY (`faena_id`) REFERENCES `faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viajeCamionPropio_origendestino_faena1` FOREIGN KEY (`origendestino_faena_id`) REFERENCES `origenDestino_faena` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
