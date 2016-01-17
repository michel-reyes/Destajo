-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 27, 2015 at 01:04 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `destajo`
--

-- --------------------------------------------------------

--
-- Table structure for table `capacidad_bombeo_equipo`
--

CREATE TABLE IF NOT EXISTS `capacidad_bombeo_equipo` (
  `capacidad_bombeo_equipo_id` int(6) unsigned NOT NULL,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_modo_descarga_id` int(2) unsigned NOT NULL,
  `capacidad_bombeo` decimal(6,2) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `capacidad_bombeo_lugar_carga`
--

CREATE TABLE IF NOT EXISTS `capacidad_bombeo_lugar_carga` (
  `capacidad_bombeo_lugar_carga_id` int(6) unsigned NOT NULL,
  `capacidad_bombeo` decimal(6,2) unsigned NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carga_descarga`
--

CREATE TABLE IF NOT EXISTS `carga_descarga` (
  `carga_descarga_id` int(6) unsigned NOT NULL,
  `codigo` int(4) unsigned zerofill NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned NOT NULL,
  `fk_lugar_descarga_id` int(3) unsigned NOT NULL,
  `km_recorridos` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `PU` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Perimetro urbano',
  `C` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Carretera',
  `A` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Autopista',
  `T` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Terraplen',
  `CM` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Camino de tierra',
  `CT` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Carretera de montaña',
  `TM` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Terraplen de montaña',
  `CV` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Camino vecinal'
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `empresa_id` int(1) unsigned NOT NULL,
  `empresa` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `empresa`
--

INSERT INTO `empresa` (`empresa_id`, `empresa`) VALUES
(1, 'Camagüey');

-- --------------------------------------------------------

--
-- Table structure for table `entrada`
--

CREATE TABLE IF NOT EXISTS `entrada` (
  `entrada_id` int(6) unsigned NOT NULL,
  `fk_operario_id` int(3) unsigned NOT NULL,
  `fecha_incidencia` date NOT NULL,
  `hoja_de_ruta` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fk_capacidad_carga_id` int(6) unsigned DEFAULT NULL,
  `fk_producto_id` int(2) unsigned DEFAULT NULL,
  `fecha_captacion` date NOT NULL,
  `horas_de_viaje` decimal(6,2) DEFAULT NULL,
  `numero_de_viajes` int(2) unsigned DEFAULT NULL,
  `numero_de_entregas` int(2) unsigned DEFAULT NULL,
  `fk_modo_descarga_id` int(2) unsigned DEFAULT NULL,
  `litros_entregados` int(6) unsigned DEFAULT NULL,
  `fk_carga_descarga_id` int(6) unsigned DEFAULT NULL,
  `km_recorridos_carga` decimal(6,2) unsigned DEFAULT NULL COMMENT 'Km recorridos con cargas',
  `horas_interrupto` decimal(6,2) unsigned DEFAULT NULL,
  `horas_no_vinculado` decimal(6,2) unsigned DEFAULT NULL,
  `horas_nocturnidad_corta` decimal(6,2) unsigned DEFAULT NULL COMMENT 'De 7pm a 11pm 8 ctvs',
  `cuantia_horaria_nocturnidad_corta` decimal(6,3) unsigned DEFAULT NULL,
  `horas_nocturnidad_larga` decimal(6,2) unsigned DEFAULT NULL,
  `cuantia_horaria_nocturnidad_larga` decimal(6,3) unsigned DEFAULT NULL,
  `horas_capacitacion` decimal(6,2) unsigned DEFAULT NULL,
  `horas_movilizacion` decimal(6,2) unsigned DEFAULT NULL,
  `horas_feriado` decimal(6,2) unsigned DEFAULT NULL,
  `pago_feriado` tinyint(1) DEFAULT '0' COMMENT 'Pago que se le hace al trabajador VINCULADO, se paga doble el importe del viaje',
  `horas_ausencia` decimal(6,2) unsigned DEFAULT NULL,
  `fk_causa_ausencia_id` int(2) unsigned DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `importe_viaje` decimal(6,2) unsigned DEFAULT NULL,
  `cumplimiento_norma` decimal(6,2) unsigned DEFAULT NULL,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned DEFAULT NULL,
  `fk_municipio_id` int(6) unsigned DEFAULT NULL COMMENT 'lugar de descarga minorista',
  `km_totales_recorridos` decimal(6,2) unsigned DEFAULT NULL COMMENT 'minorista',
  `importe_viaje_progresivo_i` decimal(6,2) unsigned DEFAULT NULL,
  `importe_viaje_m` decimal(6,2) unsigned DEFAULT NULL COMMENT 'minorista',
  `cumplimiento_norma_minorista` decimal(6,2) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5024 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lugar_descarga_producto`
--

CREATE TABLE IF NOT EXISTS `lugar_descarga_producto` (
  `lugar_descarga_producto_id` int(10) unsigned NOT NULL,
  `fk_lugar_descarga_id` int(3) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=538 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_capacidad_carga`
--

CREATE TABLE IF NOT EXISTS `m_capacidad_carga` (
  `m_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_equipo_id` int(3) unsigned NOT NULL,
  `fk_cuna_id` int(3) unsigned DEFAULT NULL,
  `viajes_promedio` int(2) unsigned DEFAULT NULL,
  `capacidad_carga` int(6) unsigned NOT NULL,
  `tipo_de_producto` enum('Blanco','GLP','Negro') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Blanco',
  `entregas_promedio` int(2) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_categoria_operario`
--

CREATE TABLE IF NOT EXISTS `m_categoria_operario` (
  `m_categoria_operario_id` int(2) unsigned NOT NULL,
  `categoria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nomenclador` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `min_capacidad_carga` int(6) unsigned NOT NULL COMMENT 'minimo de la capacidad de carga del equipo',
  `max_capacidad_carga` int(6) unsigned NOT NULL COMMENT 'maximo de la capacidad de carga del equipo'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_categoria_operario`
--

INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES
(1, 'Chofer de gran porte', 'AA', 50000, 100000),
(2, 'Chofer A', 'A', 17000, 49999),
(3, 'Chofer B', 'B', 10000, 16999),
(4, 'Chofer C', 'C', 2001, 9999),
(5, 'Auxiliar general de la industria', 'F', 0, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `m_causa_ausencia`
--

CREATE TABLE IF NOT EXISTS `m_causa_ausencia` (
  `m_causa_ausencia_id` int(2) unsigned NOT NULL,
  `causa` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_causa_ausencia`
--

INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES
(1, 'Vacaciones'),
(2, 'Movilizaciones varias'),
(3, 'Enfermedad 3 días'),
(4, 'Licencia de maternidad'),
(5, 'Accidentes de trabajo'),
(6, 'Accidentes en el trayecto al trabajo'),
(7, 'Enfermedad común'),
(8, 'Ausencia autorizada'),
(9, 'Licencia sin sueldo'),
(10, 'Ausencia injustificada'),
(11, 'Autorizado legislación vigente'),
(12, 'Alta trabajador'),
(13, 'Día festivo y feriado');

-- --------------------------------------------------------

--
-- Table structure for table `m_claves_siscont`
--

CREATE TABLE IF NOT EXISTS `m_claves_siscont` (
  `m_claves_siscont_id` int(3) unsigned NOT NULL,
  `clave` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `unidad_medida` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_claves_siscont`
--

INSERT INTO `m_claves_siscont` (`m_claves_siscont_id`, `clave`, `sigla`, `valor`, `unidad_medida`) VALUES
(3, 'Clave de entrada de vinculacion', 'CEV', '0', NULL),
(4, 'Clave de entrada de nocturnidad corta', 'CENC', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_cuna`
--

CREATE TABLE IF NOT EXISTS `m_cuna` (
  `m_cuna_id` int(3) unsigned NOT NULL,
  `numero_operacional` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_equipo`
--

CREATE TABLE IF NOT EXISTS `m_equipo` (
  `m_equipo_id` int(3) unsigned NOT NULL,
  `numero_operacional` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_lugar_carga`
--

CREATE TABLE IF NOT EXISTS `m_lugar_carga` (
  `m_lugar_carga_id` int(3) unsigned NOT NULL,
  `lugar_carga` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_lugar_descarga`
--

CREATE TABLE IF NOT EXISTS `m_lugar_descarga` (
  `m_lugar_descarga_id` int(3) unsigned NOT NULL,
  `lugar_descarga` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `capacidad_bombeo_turbina_cliente` decimal(6,2) unsigned DEFAULT '0.00',
  `velocidad_media_a_k` decimal(6,2) unsigned DEFAULT NULL COMMENT 'minorista alcohol y kerosina',
  `velocidad_media_d` decimal(6,2) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_modo_descarga`
--

CREATE TABLE IF NOT EXISTS `m_modo_descarga` (
  `m_modo_descarga_id` int(2) unsigned NOT NULL,
  `modo` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_modo_descarga`
--

INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES
(1, 'Turbina del cliente'),
(2, 'Gravedad 2"'),
(3, 'Gravedad 3"'),
(4, 'Gravedad 4"'),
(5, 'Turbina del equipo');

-- --------------------------------------------------------

--
-- Table structure for table `m_normativa`
--

CREATE TABLE IF NOT EXISTS `m_normativa` (
  `m_normativa_id` int(10) unsigned NOT NULL,
  `normativa` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(6,3) unsigned NOT NULL,
  `unidad_medida` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_normativa`
--

INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES
(1, 'Normativa del tiempo preparativo conclusivo', 'TPC', '10.000', 'minutos'),
(2, 'Normativa del tiempo para la recogida y entrega de documento', 'TRED', '30.000', 'minutos'),
(3, 'Normativa de tiempo auxiliar', 'TA', '25.000', 'minutos'),
(4, 'Perímetro Urbano', 'PU', '25.000', 'km/h'),
(5, 'Carretera', 'C', '45.000', 'km/h'),
(6, 'Autopista', 'A', '55.000', 'km/h'),
(7, 'Terraplén', 'T', '28.000', 'km/h'),
(8, 'Camino de Tierra', 'CT', '18.000', 'km/h'),
(9, 'Carretera de Montaña', 'CM', '25.000', 'km/h'),
(10, 'Terraplén de Montaña', 'TM', '18.000', 'km/h'),
(11, 'Camino Vecinal', 'CV', '10.000', 'km/h'),
(12, 'Pago por entrega adicional (Mayorista)', 'PEA', '1.440', 'pesos'),
(13, 'Viajes promedios +1', 'VP+1', '2.000', 'pesos'),
(14, 'Viajes promedios +2', 'VP+2', '2.500', 'pesos'),
(15, 'Viajes promedios +3', 'VP+3', '2.800', 'pesos'),
(16, 'Cuantía horaria nocturnidad corta', 'CHNC', '0.092', 'centavos'),
(17, 'Cuantía horaria nocturnidad larga', 'CHNL', '0.184', 'centavos'),
(18, 'Normativa de tiempo de servicio', 'TS', '3.570', 'minutos'),
(19, 'Pago por entrega (Minorista)', 'PPE', '2.000', 'pesos'),
(20, 'Pago incrementado por descarga con turbina del equipo', 'CDTMA', '2.000', 'pesos');

-- --------------------------------------------------------

--
-- Table structure for table `m_operario`
--

CREATE TABLE IF NOT EXISTS `m_operario` (
  `m_operario_id` int(3) unsigned NOT NULL,
  `chapa` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fk_categoria_operario_id` int(2) unsigned NOT NULL,
  `ci` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_producto`
--

CREATE TABLE IF NOT EXISTS `m_producto` (
  `m_producto_id` int(2) unsigned NOT NULL,
  `producto` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('Blanco','GLP','Negro') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Blanco'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_producto`
--

INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES
(1, 'GLP', 'GLP'),
(2, 'Alcohol', 'Blanco'),
(3, 'Kerosina', 'Blanco'),
(4, 'Fuell', 'Negro'),
(5, 'Gasolina Regular', 'Blanco'),
(6, 'Gasolina Especial', 'Blanco'),
(7, 'Diesel', 'Blanco'),
(8, 'Crudo', 'Negro'),
(9, 'Turbo', 'Blanco'),
(10, 'Lubricantes', 'Blanco'),
(11, 'Nafta', 'Blanco'),
(12, 'BioMix', 'Blanco'),
(13, 'Aceite Usado', 'Negro');

-- --------------------------------------------------------

--
-- Table structure for table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `perfil_id` int(2) unsigned NOT NULL,
  `perfil` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `no_eliminar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: se puede eliminar, 1: no se puede eliminar'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `perfil`
--

INSERT INTO `perfil` (`perfil_id`, `perfil`, `descripcion`, `no_eliminar`) VALUES
(1, 'Administrador', 'Tiene control total sobre todos los modulos y acciones en el sitio.', 1),
(2, 'Técnico', 'Permite dar alta en todos los maestros.', 1),
(3, 'install', 'installer puede cargar una base de datos', 0);

-- --------------------------------------------------------

--
-- Table structure for table `perfil_permiso`
--

CREATE TABLE IF NOT EXISTS `perfil_permiso` (
  `fk_perfil_id` int(2) unsigned NOT NULL,
  `fk_permiso_id` int(2) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `perfil_permiso`
--

INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES
(1, 59),
(1, 60),
(1, 61),
(1, 58),
(1, 63),
(1, 64),
(1, 65),
(1, 62),
(1, 39),
(1, 40),
(1, 41),
(1, 38),
(1, 31),
(1, 32),
(1, 33),
(1, 30),
(1, 91),
(1, 90),
(1, 23),
(1, 24),
(1, 25),
(1, 22),
(1, 4),
(1, 5),
(1, 7),
(1, 6),
(1, 79),
(1, 80),
(1, 81),
(1, 78),
(1, 19),
(1, 20),
(1, 21),
(1, 18),
(1, 51),
(1, 52),
(1, 53),
(1, 50),
(1, 55),
(1, 56),
(1, 57),
(1, 54),
(1, 47),
(1, 48),
(1, 49),
(1, 46),
(1, 35),
(1, 36),
(1, 37),
(1, 34),
(1, 43),
(1, 44),
(1, 45),
(1, 42),
(1, 14),
(1, 13),
(1, 15),
(1, 12),
(1, 88),
(1, 89),
(1, 3),
(1, 16),
(1, 17),
(1, 1),
(1, 27),
(1, 28),
(1, 29),
(1, 26),
(1, 87),
(1, 86),
(1, 85),
(1, 84),
(1, 83),
(1, 82),
(1, 75),
(1, 76),
(1, 77),
(1, 74),
(1, 67),
(1, 66),
(1, 71),
(1, 70),
(1, 9),
(1, 10),
(1, 11),
(1, 8),
(3, 90),
(3, 78),
(3, 9),
(3, 8),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(2, 59),
(2, 60),
(2, 58),
(2, 63),
(2, 64),
(2, 62),
(2, 38),
(2, 30),
(2, 92),
(2, 93),
(2, 96),
(2, 95),
(2, 94),
(2, 90),
(2, 23),
(2, 22),
(2, 6),
(2, 79),
(2, 80),
(2, 78),
(2, 19),
(2, 18),
(2, 51),
(2, 50),
(2, 55),
(2, 54),
(2, 46),
(2, 34),
(2, 43),
(2, 44),
(2, 42),
(2, 88),
(2, 89),
(2, 26),
(2, 87),
(2, 86),
(2, 85),
(2, 84),
(2, 83),
(2, 82),
(2, 74),
(2, 67),
(2, 66),
(2, 71),
(2, 70),
(2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `periodo_pago`
--

CREATE TABLE IF NOT EXISTS `periodo_pago` (
  `periodo_pago_id` int(1) unsigned NOT NULL,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `perioro_pago_abierto` tinyint(1) NOT NULL DEFAULT '0',
  `fondo_horario` decimal(6,2) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `periodo_pago`
--

INSERT INTO `periodo_pago` (`periodo_pago_id`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`, `perioro_pago_abierto`, `fondo_horario`) VALUES
(1, '0', '0', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
  `permiso_id` int(2) unsigned NOT NULL,
  `nombre` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'estructura: Modulo.Permiso',
  `descripcion` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permiso`
--

INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES
(1, 'Permiso.Ver', 'Permite ver el listado de permisos.'),
(3, 'Permiso.Agregar', 'Permite agregar permisos.'),
(4, 'Empresa.Agregar', 'Permite agregar empresas.'),
(5, 'Empresa.Editar', 'Permite editar las empresas.'),
(6, 'Empresa.Ver', 'Permite ver las empresas.'),
(7, 'Empresa.Eliminar', 'Permite eliminar las empresas.'),
(8, 'Usuario.Ver', 'Permite ver los usuarios.'),
(9, 'Usuario.Agregar', 'Permite agregar los usuarios.'),
(10, 'Usuario.Editar', 'Permite editar a los usuarios.'),
(11, 'Usuario.Eliminar', 'Permite eliminar a los usuarios.'),
(12, 'Perfil.Ver', 'Permite ver los perfiles.'),
(13, 'Perfil.Editar', 'Permite editar los perfiles.'),
(14, 'Perfil.Agregar', 'Permite agregar perfiles.'),
(15, 'Perfil.Eliminar', 'Permite eliminar perfiles.'),
(16, 'Permiso.Editar', 'Permite editar los permisos.'),
(17, 'Permiso.Eliminar', 'Permite eliminar permisos.'),
(18, 'Equipo.Ver', 'Permite ver los equipos.'),
(19, 'Equipo.Agregar', 'Permite agregar equipos.'),
(20, 'Equipo.Editar', 'Permite editar equipos.'),
(21, 'Equipo.Eliminar', 'Permite eliminar equipos.'),
(22, 'Cuña.Ver', 'Permite ver cuñas.'),
(23, 'Cuña.Agregar', 'Permite agregar cu­ñas.'),
(24, 'Cuña.Editar', 'Permite editar cuñas.'),
(25, 'Cuña.Eliminar', 'Permite eliminar cuñas.'),
(26, 'Producto.Ver', 'Permite ver productos.'),
(27, 'Producto.Agregar', 'Permite agregar productos.'),
(28, 'Producto.Editar', 'Permite editar productos.'),
(29, 'Producto.Eliminar', 'Permite eliminar productos.'),
(30, 'CausaAusencia.Ver', 'Permite ver causas de ausencia'),
(31, 'CausaAusencia.Agregar', 'Permite agregar causas de ausencia.'),
(32, 'CausaAusencia.Editar', 'Permite editar causas de ausencia.'),
(33, 'CausaAusencia.Eliminar', 'Permite elimnar causas de ausencia.'),
(34, 'Normativa.Ver', 'Permite ver normativas.'),
(35, 'Normativa.Agregar', 'Permite agregar normativas.'),
(36, 'Normativa.Editar', 'Permite editar normativas.'),
(37, 'Normativa.Eliminar', 'Permite eliminar normativas.'),
(38, 'CategoriaOperario.Ver', 'Permite ver categorias del operario.'),
(39, 'CategoriaOperario.Agregar', 'Permite agregar categorias del operario.'),
(40, 'CategoriaOperario.Editar', 'Permite editar categorias del operario.'),
(41, 'CategoriaOperario.Eliminar', 'Permite eliminar categorias del operario.'),
(42, 'Operario.Ver', 'Permite ver operarios.'),
(43, 'Operario.Agregar', 'Permite agregar operarios.'),
(44, 'Operario.Editar', 'Permite editar operarios.'),
(45, 'Operario.Eliminar', 'Permite eliminar operarios.'),
(46, 'ModoDescarga.Ver', 'Permite ver modos de descarga.'),
(47, 'ModoDescarga.Agregar', 'Permite agregar modos de descarga.'),
(48, 'ModoDescarga.Editar', 'Permite editar modos de descarga.'),
(49, 'ModoDescarga.Eliminar', 'Permite eliminar modos de descarga.'),
(50, 'LugarCarga.Ver', 'Permite ver lugares de carga.'),
(51, 'LugarCarga.Agregar', 'Permite agregar lugares de carga.'),
(52, 'LugarCarga.Editar', 'Permite editar lugares de carga.'),
(53, 'LugarCarga.Eliminar', 'Permite eliminar lugares de carga.'),
(54, 'LugarDescarga.Ver', 'Permite ver lugares de descarga.'),
(55, 'LugarDescarga.Agregar', 'Permite agregar lugares de descarga.'),
(56, 'LugarDescarga.Editar', 'Permite editar lugares de descarga.'),
(57, 'LugarDescarga.Eliminar', 'Permite eliminar lugares de descarga.'),
(58, 'CapacidadCarga.Ver', 'Permite ver capacidad de carga.'),
(59, 'CapacidadCarga.Agregar', 'Permite agregar capacidad de carga.'),
(60, 'CapacidadCarga.Editar', 'Permite editar capacidad de carga.'),
(61, 'CapacidadCarga.Eliminar', 'Permite eliminar capacidad de carga.'),
(62, 'CargaDescarga.Ver', 'Permite ver carga y carga.'),
(63, 'CargaDescarga.Agregar', 'Permite agregar carga y descarga.'),
(64, 'CargaDescarga.Editar', 'Permite editar carga y descarga.'),
(65, 'CargaDescarga.Eliminar', 'Permite eliminar carga y descarga.'),
(66, 'TiempoCarga.Ver', 'Permite ver tiempo de carga.'),
(67, 'TiempoCarga.Calcular', 'Permite clacular el tiempo de carga.'),
(70, 'TiempoDescarga.Ver', 'Permite ver tiempo de descarga.'),
(71, 'TiempoDescarga.Calcular', 'Permite calcular tiempo de descarga.'),
(74, 'TarifaPago.Ver', 'Permite ver tarifas de pago.'),
(75, 'TarifaPago.Agregar', 'Permite agregar tarifas de pago.'),
(76, 'TarifaPago.Editar', 'Permite editar tarifas de pago.'),
(77, 'TarifaPago.Eliminar', 'Permite eliminar tarifas de pago.'),
(78, 'Entrada.Ver', 'Permite ver entradas.'),
(79, 'Entrada.Agregar', 'Permite agregar entradas.'),
(80, 'Entrada.Editar', 'Permite editar entradas.'),
(81, 'Entrada.Eliminar', 'Permite eliminar entradas.'),
(82, 'SalidaSalarioTrabajador.Ver', 'Permite ver la salida del salario por trabajador.'),
(83, 'SalidaSalarioTrabajador.Calcular', 'Permite calcular la salida del salario por trabajador.'),
(84, 'SalidaSalarioEquipo.Ver', 'Permite ver la salida del salario por equipo.'),
(85, 'SalidaSalarioEquipo.Calcular', 'Permite calcular la salida del salario por equipos.'),
(86, 'SalidaCumplimientoNorma.Ver', 'Permite ver la salida del cumplimiento de la norma.'),
(87, 'SalidaCumplimientoNorma.Calcular', 'Permite calcular la salida del cumplimiento de la norma.'),
(88, 'PeriodoPago.Abrir', 'Permite abrir el periodo de pago.\r\nNecesita tener permisos para ver la configuración'),
(89, 'PeriodoPago.Cerrar', 'Permite cerrar el periodo de pago.\r\nNecesita tener permisos para ver la configuración.'),
(90, 'Configurar.Ver', 'Permite tener acceso a configurar. Esto no quiere decir que tenga permisos en todos los detalles de la configuración.'),
(91, 'Configurar.Mantenimiento', 'Permite darle mantenimiento (Optimizar) a las tablas de la base de datos.'),
(92, 'ClavesSiscont.Agregar', 'Permite agregar claves del siscont'),
(93, 'ClavesSiscont.Editar', 'Permite editar claves del siscont'),
(94, 'ClavesSiscont.Ver', 'Permite ver claves del siscont'),
(95, 'ClavesSiscont.Eliminar', 'Permite eleiminar claves del siscont'),
(96, 'ClavesSiscont.EditarSigla', 'Permite Editar las siglas en el modulo Clave de Siscont');

-- --------------------------------------------------------

--
-- Table structure for table `salida_cumplimiento_norma`
--

CREATE TABLE IF NOT EXISTS `salida_cumplimiento_norma` (
  `salida_cumplimiento_norma_id` int(6) unsigned NOT NULL,
  `producto` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `cumplimiento_norma` decimal(6,2) unsigned DEFAULT NULL,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cumplimiento_norma_minorista` decimal(6,2) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salida_salario_equipo`
--

CREATE TABLE IF NOT EXISTS `salida_salario_equipo` (
  `salida_salario_equipo_id` int(6) unsigned NOT NULL,
  `numero_operacional_equipo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `numero_operacional_cuna` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_viaje` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salida_salario_trabajador`
--

CREATE TABLE IF NOT EXISTS `salida_salario_trabajador` (
  `salida_salario_trabajador_id` int(6) unsigned NOT NULL,
  `chapa` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `horas_viaje` decimal(6,2) DEFAULT NULL,
  `importe_viaje` decimal(6,2) DEFAULT NULL,
  `cumplimiento_norma` decimal(6,2) DEFAULT NULL COMMENT 'El promedio del cumplimineto de la norma',
  `horas_interrupto` decimal(6,3) DEFAULT NULL,
  `horas_no_vinculado` decimal(6,3) DEFAULT NULL,
  `horas_nocturnidad_corta` decimal(6,3) DEFAULT NULL,
  `cuantia_horaria_nocturnidad_corta` decimal(6,3) unsigned DEFAULT NULL,
  `horas_nocturnidad_larga` decimal(6,3) DEFAULT NULL,
  `cuantia_horaria_nocturnidad_larga` decimal(6,3) unsigned DEFAULT NULL,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `horas_viaje_m` decimal(6,2) unsigned DEFAULT NULL,
  `importe_viaje_m` decimal(6,2) unsigned DEFAULT NULL,
  `cumplimiento_norma_m` decimal(6,2) unsigned DEFAULT NULL,
  `horas_interrupto_m` decimal(6,2) unsigned DEFAULT NULL,
  `horas_no_vinculado_m` decimal(6,2) unsigned DEFAULT NULL,
  `horas_nocturnidad_corta_m` decimal(6,2) unsigned DEFAULT NULL,
  `cuantia_horaria_nocturnidad_corta_m` decimal(4,2) unsigned DEFAULT NULL,
  `horas_nocturnidad_larga_m` decimal(6,2) unsigned DEFAULT NULL,
  `cuantia_horaria_nocturnidad_larga_m` decimal(4,2) unsigned DEFAULT NULL,
  `ci` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tarifa_pago`
--

CREATE TABLE IF NOT EXISTS `tarifa_pago` (
  `tarifa_pago_id` int(3) unsigned NOT NULL,
  `fk_categoria_operario_id` int(2) unsigned NOT NULL,
  `tarifa_menor` decimal(6,5) NOT NULL COMMENT 'tarifa para viajes menores e igual a 90 Km',
  `tarifa_mayor` decimal(6,5) NOT NULL COMMENT 'tarifa para viajes mayores a 90 Km',
  `tarifa_completa` decimal(6,5) NOT NULL,
  `tarifa_interrupcion` decimal(6,5) DEFAULT '0.00000',
  `tarifa_horario_escala` decimal(6,5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tarifa_pago`
--

INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES
(1, 5, '3.99170', '4.17270', '1.99580', '1.91580', NULL),
(2, 4, '4.35370', '4.53470', '2.17680', '2.01680', NULL),
(3, 3, '4.40400', '4.58500', '2.20200', '2.02100', NULL),
(4, 2, '4.58500', '4.76600', '2.29250', '2.13260', NULL),
(5, 1, '4.70600', '4.88700', '2.35300', '2.16680', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tiempo_carga`
--

CREATE TABLE IF NOT EXISTS `tiempo_carga` (
  `tiempo_carga_id` int(10) unsigned NOT NULL,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned NOT NULL,
  `tiempo_carga` decimal(6,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8949 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiempo_descarga`
--

CREATE TABLE IF NOT EXISTS `tiempo_descarga` (
  `tiempo_descarga_id` int(10) unsigned NOT NULL,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  `fk_lugar_descarga_id` int(3) unsigned NOT NULL,
  `fk_modo_descarga_id` int(2) unsigned NOT NULL,
  `tiempo_descarga` decimal(6,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `update_id` int(4) unsigned NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `fichero` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'waiting',
  `date` varchar(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `usuario_id` int(2) unsigned NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'sirve para loguearse y para recuperar el password perdido',
  `nombre_login` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password_login` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_alta` date NOT NULL,
  `fk_empresa_id` int(1) unsigned NOT NULL,
  `fk_perfil_id` int(2) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `nombre`, `apellidos`, `email`, `nombre_login`, `password_login`, `fecha_alta`, `fk_empresa_id`, `fk_perfil_id`) VALUES
(2, 'Michel', 'Reyes', 'michel@trans.cupet.cu', 'michel', 'f4b5ceb3267cbcd20d2f692fcfc5980bae4d4e19', '2013-06-07', 1, 1),
(3, 'Rafael', 'Collazo León', 'rafael@cmg.trans.cupet.cu', 'rafael', 'ce15a454a5b2d8cbce9c67e43a29a15760081694', '1997-07-28', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `capacidad_bombeo_equipo`
--
ALTER TABLE `capacidad_bombeo_equipo`
  ADD PRIMARY KEY (`capacidad_bombeo_equipo_id`),
  ADD KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  ADD KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`);

--
-- Indexes for table `capacidad_bombeo_lugar_carga`
--
ALTER TABLE `capacidad_bombeo_lugar_carga`
  ADD PRIMARY KEY (`capacidad_bombeo_lugar_carga_id`),
  ADD KEY `fk_producto_id` (`fk_producto_id`),
  ADD KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`);

--
-- Indexes for table `carga_descarga`
--
ALTER TABLE `carga_descarga`
  ADD PRIMARY KEY (`carga_descarga_id`),
  ADD KEY `fk_lugar_descarga_id` (`fk_lugar_descarga_id`),
  ADD KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`);

--
-- Indexes for table `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`empresa_id`);

--
-- Indexes for table `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`entrada_id`),
  ADD KEY `fk_causa_ausencia_id` (`fk_causa_ausencia_id`),
  ADD KEY `fk_operario_id` (`fk_operario_id`),
  ADD KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`),
  ADD KEY `fk_producto_id` (`fk_producto_id`),
  ADD KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  ADD KEY `fk_carga_descarga_id` (`fk_carga_descarga_id`),
  ADD KEY `fk_municipio_id` (`fk_municipio_id`),
  ADD KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`);

--
-- Indexes for table `lugar_descarga_producto`
--
ALTER TABLE `lugar_descarga_producto`
  ADD PRIMARY KEY (`lugar_descarga_producto_id`),
  ADD KEY `id_producto` (`fk_producto_id`),
  ADD KEY `id_lugar_descarga` (`fk_lugar_descarga_id`);

--
-- Indexes for table `m_capacidad_carga`
--
ALTER TABLE `m_capacidad_carga`
  ADD PRIMARY KEY (`m_capacidad_carga_id`),
  ADD KEY `id_equipo` (`fk_equipo_id`),
  ADD KEY `id_cuna` (`fk_cuna_id`);

--
-- Indexes for table `m_categoria_operario`
--
ALTER TABLE `m_categoria_operario`
  ADD PRIMARY KEY (`m_categoria_operario_id`);

--
-- Indexes for table `m_causa_ausencia`
--
ALTER TABLE `m_causa_ausencia`
  ADD PRIMARY KEY (`m_causa_ausencia_id`);

--
-- Indexes for table `m_claves_siscont`
--
ALTER TABLE `m_claves_siscont`
  ADD PRIMARY KEY (`m_claves_siscont_id`);

--
-- Indexes for table `m_cuna`
--
ALTER TABLE `m_cuna`
  ADD PRIMARY KEY (`m_cuna_id`);

--
-- Indexes for table `m_equipo`
--
ALTER TABLE `m_equipo`
  ADD PRIMARY KEY (`m_equipo_id`);

--
-- Indexes for table `m_lugar_carga`
--
ALTER TABLE `m_lugar_carga`
  ADD PRIMARY KEY (`m_lugar_carga_id`);

--
-- Indexes for table `m_lugar_descarga`
--
ALTER TABLE `m_lugar_descarga`
  ADD PRIMARY KEY (`m_lugar_descarga_id`);

--
-- Indexes for table `m_modo_descarga`
--
ALTER TABLE `m_modo_descarga`
  ADD PRIMARY KEY (`m_modo_descarga_id`);

--
-- Indexes for table `m_normativa`
--
ALTER TABLE `m_normativa`
  ADD PRIMARY KEY (`m_normativa_id`);

--
-- Indexes for table `m_operario`
--
ALTER TABLE `m_operario`
  ADD PRIMARY KEY (`m_operario_id`),
  ADD KEY `fk_categoria_operario_id` (`fk_categoria_operario_id`);

--
-- Indexes for table `m_producto`
--
ALTER TABLE `m_producto`
  ADD PRIMARY KEY (`m_producto_id`);

--
-- Indexes for table `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`perfil_id`);

--
-- Indexes for table `perfil_permiso`
--
ALTER TABLE `perfil_permiso`
  ADD KEY `fk_permiso_id` (`fk_permiso_id`),
  ADD KEY `fk_perfil_id` (`fk_perfil_id`);

--
-- Indexes for table `periodo_pago`
--
ALTER TABLE `periodo_pago`
  ADD PRIMARY KEY (`periodo_pago_id`);

--
-- Indexes for table `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`permiso_id`);

--
-- Indexes for table `salida_cumplimiento_norma`
--
ALTER TABLE `salida_cumplimiento_norma`
  ADD PRIMARY KEY (`salida_cumplimiento_norma_id`);

--
-- Indexes for table `salida_salario_equipo`
--
ALTER TABLE `salida_salario_equipo`
  ADD PRIMARY KEY (`salida_salario_equipo_id`);

--
-- Indexes for table `salida_salario_trabajador`
--
ALTER TABLE `salida_salario_trabajador`
  ADD PRIMARY KEY (`salida_salario_trabajador_id`);

--
-- Indexes for table `tarifa_pago`
--
ALTER TABLE `tarifa_pago`
  ADD PRIMARY KEY (`tarifa_pago_id`),
  ADD KEY `fk_categoria_operario_id` (`fk_categoria_operario_id`);

--
-- Indexes for table `tiempo_carga`
--
ALTER TABLE `tiempo_carga`
  ADD PRIMARY KEY (`tiempo_carga_id`),
  ADD KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`),
  ADD KEY `fk_producto_id` (`fk_producto_id`),
  ADD KEY `tiempo_carga_id` (`tiempo_carga_id`),
  ADD KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`);

--
-- Indexes for table `tiempo_descarga`
--
ALTER TABLE `tiempo_descarga`
  ADD PRIMARY KEY (`tiempo_descarga_id`),
  ADD KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  ADD KEY `fk_lugar_descarga_id` (`fk_lugar_descarga_id`),
  ADD KEY `fk_producto_id` (`fk_producto_id`),
  ADD KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`update_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_perfil_id` (`fk_perfil_id`),
  ADD KEY `fk_empresa_id` (`fk_empresa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `capacidad_bombeo_equipo`
--
ALTER TABLE `capacidad_bombeo_equipo`
  MODIFY `capacidad_bombeo_equipo_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT for table `capacidad_bombeo_lugar_carga`
--
ALTER TABLE `capacidad_bombeo_lugar_carga`
  MODIFY `capacidad_bombeo_lugar_carga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `carga_descarga`
--
ALTER TABLE `carga_descarga`
  MODIFY `carga_descarga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=258;
--
-- AUTO_INCREMENT for table `empresa`
--
ALTER TABLE `empresa`
  MODIFY `empresa_id` int(1) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `entrada`
--
ALTER TABLE `entrada`
  MODIFY `entrada_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5024;
--
-- AUTO_INCREMENT for table `lugar_descarga_producto`
--
ALTER TABLE `lugar_descarga_producto`
  MODIFY `lugar_descarga_producto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=538;
--
-- AUTO_INCREMENT for table `m_capacidad_carga`
--
ALTER TABLE `m_capacidad_carga`
  MODIFY `m_capacidad_carga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `m_categoria_operario`
--
ALTER TABLE `m_categoria_operario`
  MODIFY `m_categoria_operario_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_causa_ausencia`
--
ALTER TABLE `m_causa_ausencia`
  MODIFY `m_causa_ausencia_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `m_claves_siscont`
--
ALTER TABLE `m_claves_siscont`
  MODIFY `m_claves_siscont_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `m_cuna`
--
ALTER TABLE `m_cuna`
  MODIFY `m_cuna_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `m_equipo`
--
ALTER TABLE `m_equipo`
  MODIFY `m_equipo_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `m_lugar_carga`
--
ALTER TABLE `m_lugar_carga`
  MODIFY `m_lugar_carga_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `m_lugar_descarga`
--
ALTER TABLE `m_lugar_descarga`
  MODIFY `m_lugar_descarga_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT for table `m_modo_descarga`
--
ALTER TABLE `m_modo_descarga`
  MODIFY `m_modo_descarga_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_normativa`
--
ALTER TABLE `m_normativa`
  MODIFY `m_normativa_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `m_operario`
--
ALTER TABLE `m_operario`
  MODIFY `m_operario_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `m_producto`
--
ALTER TABLE `m_producto`
  MODIFY `m_producto_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `perfil`
--
ALTER TABLE `perfil`
  MODIFY `perfil_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `periodo_pago`
--
ALTER TABLE `periodo_pago`
  MODIFY `periodo_pago_id` int(1) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `permiso`
--
ALTER TABLE `permiso`
  MODIFY `permiso_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `salida_cumplimiento_norma`
--
ALTER TABLE `salida_cumplimiento_norma`
  MODIFY `salida_cumplimiento_norma_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `salida_salario_equipo`
--
ALTER TABLE `salida_salario_equipo`
  MODIFY `salida_salario_equipo_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `salida_salario_trabajador`
--
ALTER TABLE `salida_salario_trabajador`
  MODIFY `salida_salario_trabajador_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tarifa_pago`
--
ALTER TABLE `tarifa_pago`
  MODIFY `tarifa_pago_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tiempo_carga`
--
ALTER TABLE `tiempo_carga`
  MODIFY `tiempo_carga_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8949;
--
-- AUTO_INCREMENT for table `tiempo_descarga`
--
ALTER TABLE `tiempo_descarga`
  MODIFY `tiempo_descarga_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `update_id` int(4) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `capacidad_bombeo_equipo`
--
ALTER TABLE `capacidad_bombeo_equipo`
  ADD CONSTRAINT `capacidad_bombeo_equipo_ibfk_1` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `capacidad_bombeo_equipo_ibfk_2` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `capacidad_bombeo_lugar_carga`
--
ALTER TABLE `capacidad_bombeo_lugar_carga`
  ADD CONSTRAINT `capacidad_bombeo_lugar_carga_ibfk_1` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `capacidad_bombeo_lugar_carga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `carga_descarga`
--
ALTER TABLE `carga_descarga`
  ADD CONSTRAINT `carga_descarga_ibfk_1` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `carga_descarga_ibfk_2` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE;

--
-- Constraints for table `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`fk_operario_id`) REFERENCES `m_operario` (`m_operario_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_3` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_4` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_5` FOREIGN KEY (`fk_carga_descarga_id`) REFERENCES `carga_descarga` (`carga_descarga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_6` FOREIGN KEY (`fk_causa_ausencia_id`) REFERENCES `m_causa_ausencia` (`m_causa_ausencia_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_7` FOREIGN KEY (`fk_municipio_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entrada_ibfk_8` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE;

--
-- Constraints for table `lugar_descarga_producto`
--
ALTER TABLE `lugar_descarga_producto`
  ADD CONSTRAINT `lugar_descarga_producto_ibfk_1` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lugar_descarga_producto_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_capacidad_carga`
--
ALTER TABLE `m_capacidad_carga`
  ADD CONSTRAINT `m_capacidad_carga_ibfk_1` FOREIGN KEY (`fk_equipo_id`) REFERENCES `m_equipo` (`m_equipo_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `m_capacidad_carga_ibfk_2` FOREIGN KEY (`fk_cuna_id`) REFERENCES `m_cuna` (`m_cuna_id`) ON UPDATE CASCADE;

--
-- Constraints for table `m_operario`
--
ALTER TABLE `m_operario`
  ADD CONSTRAINT `m_operario_ibfk_1` FOREIGN KEY (`fk_categoria_operario_id`) REFERENCES `m_categoria_operario` (`m_categoria_operario_id`) ON UPDATE CASCADE;

--
-- Constraints for table `perfil_permiso`
--
ALTER TABLE `perfil_permiso`
  ADD CONSTRAINT `perfil_permiso_ibfk_1` FOREIGN KEY (`fk_perfil_id`) REFERENCES `perfil` (`perfil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `perfil_permiso_ibfk_2` FOREIGN KEY (`fk_permiso_id`) REFERENCES `permiso` (`permiso_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tarifa_pago`
--
ALTER TABLE `tarifa_pago`
  ADD CONSTRAINT `tarifa_pago_ibfk_1` FOREIGN KEY (`fk_categoria_operario_id`) REFERENCES `m_categoria_operario` (`m_categoria_operario_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tiempo_carga`
--
ALTER TABLE `tiempo_carga`
  ADD CONSTRAINT `tiempo_carga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tiempo_carga_ibfk_3` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tiempo_descarga`
--
ALTER TABLE `tiempo_descarga`
  ADD CONSTRAINT `tiempo_descarga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tiempo_descarga_ibfk_3` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tiempo_descarga_ibfk_4` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tiempo_descarga_ibfk_5` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`fk_empresa_id`) REFERENCES `empresa` (`empresa_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`fk_perfil_id`) REFERENCES `perfil` (`perfil_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
