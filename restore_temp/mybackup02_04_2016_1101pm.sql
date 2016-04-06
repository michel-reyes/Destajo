#
# TABLE STRUCTURE FOR: capacidad_bombeo_equipo
#

DROP TABLE IF EXISTS `capacidad_bombeo_equipo`;

CREATE TABLE `capacidad_bombeo_equipo` (
  `capacidad_bombeo_equipo_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_modo_descarga_id` int(2) unsigned NOT NULL,
  `capacidad_bombeo` decimal(6,2) unsigned NOT NULL,
  PRIMARY KEY (`capacidad_bombeo_equipo_id`),
  KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`),
  CONSTRAINT `capacidad_bombeo_equipo_ibfk_1` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `capacidad_bombeo_equipo_ibfk_2` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `capacidad_bombeo_equipo` (`capacidad_bombeo_equipo_id`, `fk_capacidad_carga_id`, `fk_modo_descarga_id`, `capacidad_bombeo`) VALUES ('1', '1', '2', '2.00');
INSERT INTO `capacidad_bombeo_equipo` (`capacidad_bombeo_equipo_id`, `fk_capacidad_carga_id`, `fk_modo_descarga_id`, `capacidad_bombeo`) VALUES ('2', '1', '3', '2.00');
INSERT INTO `capacidad_bombeo_equipo` (`capacidad_bombeo_equipo_id`, `fk_capacidad_carga_id`, `fk_modo_descarga_id`, `capacidad_bombeo`) VALUES ('3', '1', '4', '2.00');
INSERT INTO `capacidad_bombeo_equipo` (`capacidad_bombeo_equipo_id`, `fk_capacidad_carga_id`, `fk_modo_descarga_id`, `capacidad_bombeo`) VALUES ('4', '1', '5', '2.00');


#
# TABLE STRUCTURE FOR: capacidad_bombeo_lugar_carga
#

DROP TABLE IF EXISTS `capacidad_bombeo_lugar_carga`;

CREATE TABLE `capacidad_bombeo_lugar_carga` (
  `capacidad_bombeo_lugar_carga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `capacidad_bombeo` decimal(6,2) unsigned NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  PRIMARY KEY (`capacidad_bombeo_lugar_carga_id`),
  KEY `fk_producto_id` (`fk_producto_id`),
  KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`),
  CONSTRAINT `capacidad_bombeo_lugar_carga_ibfk_1` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `capacidad_bombeo_lugar_carga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('1', '1.00', '1', '13');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('2', '2.00', '1', '2');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('3', '2.00', '1', '12');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('4', '2.00', '1', '8');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('5', '2.00', '1', '7');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('6', '2.00', '1', '4');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('7', '2.00', '1', '6');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('8', '2.00', '1', '5');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('9', '2.00', '1', '1');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('10', '2.00', '1', '3');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('11', '2.00', '1', '10');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('12', '2.00', '1', '11');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('13', '3.00', '1', '9');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('27', '4.00', '2', '2');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('28', '4.00', '2', '12');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('29', '4.00', '2', '8');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('30', '4.00', '2', '7');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('31', '4.00', '2', '4');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('32', '4.00', '2', '6');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('33', '4.00', '2', '5');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('34', '4.00', '2', '1');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('35', '4.00', '2', '3');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('36', '4.00', '2', '10');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('37', '4.00', '2', '11');
INSERT INTO `capacidad_bombeo_lugar_carga` (`capacidad_bombeo_lugar_carga_id`, `capacidad_bombeo`, `fk_lugar_carga_id`, `fk_producto_id`) VALUES ('38', '4.00', '2', '9');


#
# TABLE STRUCTURE FOR: carga_descarga
#

DROP TABLE IF EXISTS `carga_descarga`;

CREATE TABLE `carga_descarga` (
  `carga_descarga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `CV` decimal(6,2) unsigned DEFAULT '0.00' COMMENT 'Camino vecinal',
  PRIMARY KEY (`carga_descarga_id`),
  KEY `fk_lugar_descarga_id` (`fk_lugar_descarga_id`),
  KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`),
  CONSTRAINT `carga_descarga_ibfk_1` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE,
  CONSTRAINT `carga_descarga_ibfk_2` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `carga_descarga` (`carga_descarga_id`, `codigo`, `fk_lugar_carga_id`, `fk_lugar_descarga_id`, `km_recorridos`, `PU`, `C`, `A`, `T`, `CM`, `CT`, `TM`, `CV`) VALUES ('1', '0001', '2', '2', '100.00', '4.00', '6.00', '6.00', '6.00', '66.00', '6.00', '6.00', '6.00');
INSERT INTO `carga_descarga` (`carga_descarga_id`, `codigo`, `fk_lugar_carga_id`, `fk_lugar_descarga_id`, `km_recorridos`, `PU`, `C`, `A`, `T`, `CM`, `CT`, `TM`, `CV`) VALUES ('2', '0002', '1', '1', '112.00', '4.00', '44.00', '4.00', '4.00', '4.00', '4.00', '4.00', '44.00');
INSERT INTO `carga_descarga` (`carga_descarga_id`, `codigo`, `fk_lugar_carga_id`, `fk_lugar_descarga_id`, `km_recorridos`, `PU`, `C`, `A`, `T`, `CM`, `CT`, `TM`, `CV`) VALUES ('3', '0003', '1', '1', '1398.00', '4.00', '6.00', '654.00', '46.00', '613.00', '4.00', '6.00', '65.00');


#
# TABLE STRUCTURE FOR: empresa
#

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `empresa_id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `empresa` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `empresa` (`empresa_id`, `empresa`) VALUES ('1', 'Camagüey');


#
# TABLE STRUCTURE FOR: entrada
#

DROP TABLE IF EXISTS `entrada`;

CREATE TABLE `entrada` (
  `entrada_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `cumplimiento_norma_minorista` decimal(6,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`entrada_id`),
  KEY `fk_causa_ausencia_id` (`fk_causa_ausencia_id`),
  KEY `fk_operario_id` (`fk_operario_id`),
  KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`),
  KEY `fk_producto_id` (`fk_producto_id`),
  KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  KEY `fk_carga_descarga_id` (`fk_carga_descarga_id`),
  KEY `fk_municipio_id` (`fk_municipio_id`),
  KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`),
  CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`fk_operario_id`) REFERENCES `m_operario` (`m_operario_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_3` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_4` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_5` FOREIGN KEY (`fk_carga_descarga_id`) REFERENCES `carga_descarga` (`carga_descarga_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_6` FOREIGN KEY (`fk_causa_ausencia_id`) REFERENCES `m_causa_ausencia` (`m_causa_ausencia_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_7` FOREIGN KEY (`fk_municipio_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE,
  CONSTRAINT `entrada_ibfk_8` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `entrada` (`entrada_id`, `fk_operario_id`, `fecha_incidencia`, `hoja_de_ruta`, `fk_capacidad_carga_id`, `fk_producto_id`, `fecha_captacion`, `horas_de_viaje`, `numero_de_viajes`, `numero_de_entregas`, `fk_modo_descarga_id`, `litros_entregados`, `fk_carga_descarga_id`, `km_recorridos_carga`, `horas_interrupto`, `horas_no_vinculado`, `horas_nocturnidad_corta`, `cuantia_horaria_nocturnidad_corta`, `horas_nocturnidad_larga`, `cuantia_horaria_nocturnidad_larga`, `horas_capacitacion`, `horas_movilizacion`, `horas_feriado`, `pago_feriado`, `horas_ausencia`, `fk_causa_ausencia_id`, `observaciones`, `importe_viaje`, `cumplimiento_norma`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`, `fk_lugar_carga_id`, `fk_municipio_id`, `km_totales_recorridos`, `importe_viaje_progresivo_i`, `importe_viaje_m`, `cumplimiento_norma_minorista`) VALUES ('1', '2', '2016-04-02', '1', '1', '2', '2016-04-02', '1.00', '1', '1', '1', '12', '1', '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, '28.69', '1302.91', '1456808400', '1459396800', NULL, NULL, NULL, '0.00', NULL, NULL);


#
# TABLE STRUCTURE FOR: lugar_descarga_producto
#

DROP TABLE IF EXISTS `lugar_descarga_producto`;

CREATE TABLE `lugar_descarga_producto` (
  `lugar_descarga_producto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_lugar_descarga_id` int(3) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  PRIMARY KEY (`lugar_descarga_producto_id`),
  KEY `id_producto` (`fk_producto_id`),
  KEY `id_lugar_descarga` (`fk_lugar_descarga_id`),
  CONSTRAINT `lugar_descarga_producto_ibfk_1` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lugar_descarga_producto_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('1', '1', '13');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('2', '1', '2');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('3', '1', '12');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('4', '1', '8');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('5', '1', '7');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('6', '1', '4');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('7', '1', '6');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('8', '1', '5');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('9', '1', '1');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('10', '1', '3');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('11', '1', '10');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('12', '1', '11');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('13', '1', '9');
INSERT INTO `lugar_descarga_producto` (`lugar_descarga_producto_id`, `fk_lugar_descarga_id`, `fk_producto_id`) VALUES ('15', '2', '13');


#
# TABLE STRUCTURE FOR: m_capacidad_carga
#

DROP TABLE IF EXISTS `m_capacidad_carga`;

CREATE TABLE `m_capacidad_carga` (
  `m_capacidad_carga_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `fk_equipo_id` int(3) unsigned NOT NULL,
  `fk_cuna_id` int(3) unsigned DEFAULT NULL,
  `viajes_promedio` int(2) unsigned DEFAULT NULL,
  `capacidad_carga` int(6) unsigned NOT NULL,
  `tipo_de_producto` enum('Blanco','GLP','Negro') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Blanco',
  `entregas_promedio` int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`m_capacidad_carga_id`),
  KEY `id_equipo` (`fk_equipo_id`),
  KEY `id_cuna` (`fk_cuna_id`),
  CONSTRAINT `m_capacidad_carga_ibfk_1` FOREIGN KEY (`fk_equipo_id`) REFERENCES `m_equipo` (`m_equipo_id`) ON UPDATE CASCADE,
  CONSTRAINT `m_capacidad_carga_ibfk_2` FOREIGN KEY (`fk_cuna_id`) REFERENCES `m_cuna` (`m_cuna_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_capacidad_carga` (`m_capacidad_carga_id`, `fk_equipo_id`, `fk_cuna_id`, `viajes_promedio`, `capacidad_carga`, `tipo_de_producto`, `entregas_promedio`) VALUES ('1', '1', '1', '2', '34', 'Blanco', '2');
INSERT INTO `m_capacidad_carga` (`m_capacidad_carga_id`, `fk_equipo_id`, `fk_cuna_id`, `viajes_promedio`, `capacidad_carga`, `tipo_de_producto`, `entregas_promedio`) VALUES ('2', '1', NULL, '2', '33', 'Negro', '3');


#
# TABLE STRUCTURE FOR: m_categoria_operario
#

DROP TABLE IF EXISTS `m_categoria_operario`;

CREATE TABLE `m_categoria_operario` (
  `m_categoria_operario_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nomenclador` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `min_capacidad_carga` int(6) unsigned NOT NULL COMMENT 'minimo de la capacidad de carga del equipo',
  `max_capacidad_carga` int(6) unsigned NOT NULL COMMENT 'maximo de la capacidad de carga del equipo',
  PRIMARY KEY (`m_categoria_operario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES ('1', 'Chofer de gran porte', 'AA', '50000', '100000');
INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES ('2', 'Chofer A', 'A', '17000', '49999');
INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES ('3', 'Chofer B', 'B', '10000', '16999');
INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES ('4', 'Chofer C', 'C', '2001', '9999');
INSERT INTO `m_categoria_operario` (`m_categoria_operario_id`, `categoria`, `nomenclador`, `min_capacidad_carga`, `max_capacidad_carga`) VALUES ('5', 'Auxiliar general de la industria', 'F', '0', '2000');


#
# TABLE STRUCTURE FOR: m_causa_ausencia
#

DROP TABLE IF EXISTS `m_causa_ausencia`;

CREATE TABLE `m_causa_ausencia` (
  `m_causa_ausencia_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `causa` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_causa_ausencia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('1', 'Vacaciones');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('2', 'Movilizaciones varias');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('3', 'Enfermedad 3 días');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('4', 'Licencia de maternidad');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('5', 'Accidentes de trabajo');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('6', 'Accidentes en el trayecto al trabajo');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('7', 'Enfermedad común');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('8', 'Ausencia autorizada');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('9', 'Licencia sin sueldo');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('10', 'Ausencia injustificada');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('11', 'Autorizado legislación vigente');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('12', 'Alta trabajador');
INSERT INTO `m_causa_ausencia` (`m_causa_ausencia_id`, `causa`) VALUES ('13', 'Día festivo y feriado');


#
# TABLE STRUCTURE FOR: m_claves_siscont
#

DROP TABLE IF EXISTS `m_claves_siscont`;

CREATE TABLE `m_claves_siscont` (
  `m_claves_siscont_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `unidad_medida` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`m_claves_siscont_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_claves_siscont` (`m_claves_siscont_id`, `clave`, `sigla`, `valor`, `unidad_medida`) VALUES ('3', 'Clave de entrada de vinculacion', 'CEV', '4', '');
INSERT INTO `m_claves_siscont` (`m_claves_siscont_id`, `clave`, `sigla`, `valor`, `unidad_medida`) VALUES ('4', 'Clave de entrada de nocturnidad corta', 'CENC', '2', '');


#
# TABLE STRUCTURE FOR: m_cuna
#

DROP TABLE IF EXISTS `m_cuna`;

CREATE TABLE `m_cuna` (
  `m_cuna_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `numero_operacional` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_cuna_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_cuna` (`m_cuna_id`, `numero_operacional`) VALUES ('1', '00011');


#
# TABLE STRUCTURE FOR: m_equipo
#

DROP TABLE IF EXISTS `m_equipo`;

CREATE TABLE `m_equipo` (
  `m_equipo_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `numero_operacional` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_equipo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_equipo` (`m_equipo_id`, `numero_operacional`) VALUES ('1', '0001');


#
# TABLE STRUCTURE FOR: m_lugar_carga
#

DROP TABLE IF EXISTS `m_lugar_carga`;

CREATE TABLE `m_lugar_carga` (
  `m_lugar_carga_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `lugar_carga` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_lugar_carga_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_lugar_carga` (`m_lugar_carga_id`, `lugar_carga`) VALUES ('1', 'uno');
INSERT INTO `m_lugar_carga` (`m_lugar_carga_id`, `lugar_carga`) VALUES ('2', 'dos');


#
# TABLE STRUCTURE FOR: m_lugar_descarga
#

DROP TABLE IF EXISTS `m_lugar_descarga`;

CREATE TABLE `m_lugar_descarga` (
  `m_lugar_descarga_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `lugar_descarga` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `capacidad_bombeo_turbina_cliente` decimal(6,2) unsigned DEFAULT '0.00',
  `velocidad_media_a_k` decimal(6,2) unsigned DEFAULT NULL COMMENT 'minorista alcohol y kerosina',
  `velocidad_media_d` decimal(6,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`m_lugar_descarga_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_lugar_descarga` (`m_lugar_descarga_id`, `lugar_descarga`, `capacidad_bombeo_turbina_cliente`, `velocidad_media_a_k`, `velocidad_media_d`) VALUES ('1', 'tres', '6.00', '6.00', '6.00');
INSERT INTO `m_lugar_descarga` (`m_lugar_descarga_id`, `lugar_descarga`, `capacidad_bombeo_turbina_cliente`, `velocidad_media_a_k`, `velocidad_media_d`) VALUES ('2', 'cuatro', '7.00', '8.00', '7.00');


#
# TABLE STRUCTURE FOR: m_modo_descarga
#

DROP TABLE IF EXISTS `m_modo_descarga`;

CREATE TABLE `m_modo_descarga` (
  `m_modo_descarga_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `modo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_modo_descarga_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES ('1', 'Turbina del cliente');
INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES ('2', 'Gravedad 2\"');
INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES ('3', 'Gravedad 3\"');
INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES ('4', 'Gravedad 4\"');
INSERT INTO `m_modo_descarga` (`m_modo_descarga_id`, `modo`) VALUES ('5', 'Turbina del equipo');


#
# TABLE STRUCTURE FOR: m_normativa
#

DROP TABLE IF EXISTS `m_normativa`;

CREATE TABLE `m_normativa` (
  `m_normativa_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `normativa` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(6,3) unsigned NOT NULL,
  `unidad_medida` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`m_normativa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('1', 'Normativa del tiempo preparativo conclusivo', 'TPC', '10.000', 'minutos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('2', 'Normativa del tiempo para la recogida y entrega de documento', 'TRED', '30.000', 'minutos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('3', 'Normativa de tiempo auxiliar', 'TA', '25.000', 'minutos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('4', 'Perímetro Urbano', 'PU', '25.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('5', 'Carretera', 'C', '45.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('6', 'Autopista', 'A', '55.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('7', 'Terraplén', 'T', '28.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('8', 'Camino de Tierra', 'CT', '18.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('9', 'Carretera de Montaña', 'CM', '25.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('10', 'Terraplén de Montaña', 'TM', '18.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('11', 'Camino Vecinal', 'CV', '10.000', 'km/h');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('12', 'Pago por entrega adicional (Mayorista)', 'PEA', '1.440', 'pesos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('13', 'Viajes promedios +1', 'VP+1', '2.000', 'pesos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('14', 'Viajes promedios +2', 'VP+2', '2.500', 'pesos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('15', 'Viajes promedios +3', 'VP+3', '2.800', 'pesos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('16', 'Cuantía horaria nocturnidad corta', 'CHNC', '0.092', 'centavos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('17', 'Cuantía horaria nocturnidad larga', 'CHNL', '0.184', 'centavos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('18', 'Normativa de tiempo de servicio', 'TS', '3.570', 'minutos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('19', 'Pago por entrega (Minorista)', 'PPE', '2.000', 'pesos');
INSERT INTO `m_normativa` (`m_normativa_id`, `normativa`, `sigla`, `valor`, `unidad_medida`) VALUES ('20', 'Pago incrementado por descarga con turbina del equipo', 'CDTMA', '2.000', 'pesos');


#
# TABLE STRUCTURE FOR: m_operario
#

DROP TABLE IF EXISTS `m_operario`;

CREATE TABLE `m_operario` (
  `m_operario_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `chapa` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fk_categoria_operario_id` int(2) unsigned NOT NULL,
  `ci` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`m_operario_id`),
  KEY `fk_categoria_operario_id` (`fk_categoria_operario_id`),
  CONSTRAINT `m_operario_ibfk_1` FOREIGN KEY (`fk_categoria_operario_id`) REFERENCES `m_categoria_operario` (`m_categoria_operario_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_operario` (`m_operario_id`, `chapa`, `nombre`, `apellidos`, `fk_categoria_operario_id`, `ci`) VALUES ('1', '45233', 'michel ', 'reyss', '2', '78779787877');
INSERT INTO `m_operario` (`m_operario_id`, `chapa`, `nombre`, `apellidos`, `fk_categoria_operario_id`, `ci`) VALUES ('2', '12345', 'Luis', 'lope', '3', '43498779798');


#
# TABLE STRUCTURE FOR: m_producto
#

DROP TABLE IF EXISTS `m_producto`;

CREATE TABLE `m_producto` (
  `m_producto_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `producto` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('Blanco','GLP','Negro') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Blanco',
  PRIMARY KEY (`m_producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('1', 'GLP', 'GLP');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('2', 'Alcohol', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('3', 'Kerosina', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('4', 'Fuell', 'Negro');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('5', 'Gasolina Regular', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('6', 'Gasolina Especial', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('7', 'Diesel', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('8', 'Crudo', 'Negro');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('9', 'Turbo', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('10', 'Lubricantes', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('11', 'Nafta', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('12', 'BioMix', 'Blanco');
INSERT INTO `m_producto` (`m_producto_id`, `producto`, `tipo`) VALUES ('13', 'Aceite Usado', 'Negro');


#
# TABLE STRUCTURE FOR: perfil
#

DROP TABLE IF EXISTS `perfil`;

CREATE TABLE `perfil` (
  `perfil_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `perfil` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `no_eliminar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: se puede eliminar, 1: no se puede eliminar',
  PRIMARY KEY (`perfil_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `perfil` (`perfil_id`, `perfil`, `descripcion`, `no_eliminar`) VALUES ('1', 'Administrador', 'Tiene control total sobre todos los modulos y acciones en el sitio.', '1');
INSERT INTO `perfil` (`perfil_id`, `perfil`, `descripcion`, `no_eliminar`) VALUES ('2', 'Técnico', 'Permite dar alta en todos los maestros.', '1');
INSERT INTO `perfil` (`perfil_id`, `perfil`, `descripcion`, `no_eliminar`) VALUES ('3', 'install', 'installer puede cargar una base de datos', '0');


#
# TABLE STRUCTURE FOR: perfil_permiso
#

DROP TABLE IF EXISTS `perfil_permiso`;

CREATE TABLE `perfil_permiso` (
  `fk_perfil_id` int(2) unsigned NOT NULL,
  `fk_permiso_id` int(2) unsigned NOT NULL,
  KEY `fk_permiso_id` (`fk_permiso_id`),
  KEY `fk_perfil_id` (`fk_perfil_id`),
  CONSTRAINT `perfil_permiso_ibfk_1` FOREIGN KEY (`fk_perfil_id`) REFERENCES `perfil` (`perfil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `perfil_permiso_ibfk_2` FOREIGN KEY (`fk_permiso_id`) REFERENCES `permiso` (`permiso_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '59');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '60');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '61');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '58');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '63');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '64');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '65');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '62');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '39');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '40');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '41');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '38');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '31');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '32');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '33');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '30');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '91');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '90');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '23');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '24');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '25');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '22');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '4');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '5');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '7');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '6');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '79');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '80');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '81');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '78');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '19');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '20');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '21');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '18');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '51');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '52');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '53');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '50');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '55');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '56');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '57');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '54');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '47');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '48');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '49');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '46');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '35');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '36');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '37');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '34');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '43');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '44');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '45');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '42');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '14');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '13');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '15');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '12');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '88');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '89');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '3');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '16');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '17');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '1');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '27');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '28');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '29');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '26');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '87');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '86');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '85');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '84');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '83');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '82');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '75');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '76');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '77');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '74');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '67');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '66');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '71');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '70');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '9');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '10');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '11');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '8');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('3', '90');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('3', '78');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('3', '9');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('3', '8');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '92');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '93');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '94');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '95');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '96');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '59');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '60');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '58');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '63');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '64');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '62');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '38');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '30');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '92');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '93');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '96');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '95');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '94');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '90');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '23');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '22');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '6');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '79');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '80');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '78');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '19');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '18');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '51');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '50');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '55');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '54');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '46');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '34');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '43');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '44');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '42');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '88');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '89');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '26');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '87');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '86');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '85');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '84');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '83');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '82');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '74');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '67');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '66');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '71');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '70');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '8');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '97');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '97');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '98');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '98');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '99');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '99');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '100');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '100');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('1', '101');
INSERT INTO `perfil_permiso` (`fk_perfil_id`, `fk_permiso_id`) VALUES ('2', '101');


#
# TABLE STRUCTURE FOR: periodo_pago
#

DROP TABLE IF EXISTS `periodo_pago`;

CREATE TABLE `periodo_pago` (
  `periodo_pago_id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `perioro_pago_abierto` tinyint(1) NOT NULL DEFAULT '0',
  `fondo_horario` decimal(6,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`periodo_pago_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `periodo_pago` (`periodo_pago_id`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`, `perioro_pago_abierto`, `fondo_horario`) VALUES ('1', '1456808400', '1459396800', '1', '25.00');


#
# TABLE STRUCTURE FOR: permiso
#

DROP TABLE IF EXISTS `permiso`;

CREATE TABLE `permiso` (
  `permiso_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'estructura: Modulo.Permiso',
  `descripcion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`permiso_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('1', 'Permiso.Ver', 'Permite ver el listado de permisos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('3', 'Permiso.Agregar', 'Permite agregar permisos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('4', 'Empresa.Agregar', 'Permite agregar empresas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('5', 'Empresa.Editar', 'Permite editar las empresas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('6', 'Empresa.Ver', 'Permite ver las empresas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('7', 'Empresa.Eliminar', 'Permite eliminar las empresas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('8', 'Usuario.Ver', 'Permite ver los usuarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('9', 'Usuario.Agregar', 'Permite agregar los usuarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('10', 'Usuario.Editar', 'Permite editar a los usuarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('11', 'Usuario.Eliminar', 'Permite eliminar a los usuarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('12', 'Perfil.Ver', 'Permite ver los perfiles.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('13', 'Perfil.Editar', 'Permite editar los perfiles.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('14', 'Perfil.Agregar', 'Permite agregar perfiles.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('15', 'Perfil.Eliminar', 'Permite eliminar perfiles.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('16', 'Permiso.Editar', 'Permite editar los permisos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('17', 'Permiso.Eliminar', 'Permite eliminar permisos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('18', 'Equipo.Ver', 'Permite ver los equipos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('19', 'Equipo.Agregar', 'Permite agregar equipos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('20', 'Equipo.Editar', 'Permite editar equipos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('21', 'Equipo.Eliminar', 'Permite eliminar equipos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('22', 'Cuña.Ver', 'Permite ver cuñas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('23', 'Cuña.Agregar', 'Permite agregar cu­ñas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('24', 'Cuña.Editar', 'Permite editar cuñas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('25', 'Cuña.Eliminar', 'Permite eliminar cuñas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('26', 'Producto.Ver', 'Permite ver productos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('27', 'Producto.Agregar', 'Permite agregar productos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('28', 'Producto.Editar', 'Permite editar productos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('29', 'Producto.Eliminar', 'Permite eliminar productos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('30', 'CausaAusencia.Ver', 'Permite ver causas de ausencia');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('31', 'CausaAusencia.Agregar', 'Permite agregar causas de ausencia.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('32', 'CausaAusencia.Editar', 'Permite editar causas de ausencia.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('33', 'CausaAusencia.Eliminar', 'Permite elimnar causas de ausencia.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('34', 'Normativa.Ver', 'Permite ver normativas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('35', 'Normativa.Agregar', 'Permite agregar normativas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('36', 'Normativa.Editar', 'Permite editar normativas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('37', 'Normativa.Eliminar', 'Permite eliminar normativas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('38', 'CategoriaOperario.Ver', 'Permite ver categorias del operario.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('39', 'CategoriaOperario.Agregar', 'Permite agregar categorias del operario.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('40', 'CategoriaOperario.Editar', 'Permite editar categorias del operario.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('41', 'CategoriaOperario.Eliminar', 'Permite eliminar categorias del operario.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('42', 'Operario.Ver', 'Permite ver operarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('43', 'Operario.Agregar', 'Permite agregar operarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('44', 'Operario.Editar', 'Permite editar operarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('45', 'Operario.Eliminar', 'Permite eliminar operarios.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('46', 'ModoDescarga.Ver', 'Permite ver modos de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('47', 'ModoDescarga.Agregar', 'Permite agregar modos de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('48', 'ModoDescarga.Editar', 'Permite editar modos de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('49', 'ModoDescarga.Eliminar', 'Permite eliminar modos de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('50', 'LugarCarga.Ver', 'Permite ver lugares de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('51', 'LugarCarga.Agregar', 'Permite agregar lugares de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('52', 'LugarCarga.Editar', 'Permite editar lugares de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('53', 'LugarCarga.Eliminar', 'Permite eliminar lugares de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('54', 'LugarDescarga.Ver', 'Permite ver lugares de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('55', 'LugarDescarga.Agregar', 'Permite agregar lugares de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('56', 'LugarDescarga.Editar', 'Permite editar lugares de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('57', 'LugarDescarga.Eliminar', 'Permite eliminar lugares de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('58', 'CapacidadCarga.Ver', 'Permite ver capacidad de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('59', 'CapacidadCarga.Agregar', 'Permite agregar capacidad de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('60', 'CapacidadCarga.Editar', 'Permite editar capacidad de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('61', 'CapacidadCarga.Eliminar', 'Permite eliminar capacidad de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('62', 'CargaDescarga.Ver', 'Permite ver carga y carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('63', 'CargaDescarga.Agregar', 'Permite agregar carga y descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('64', 'CargaDescarga.Editar', 'Permite editar carga y descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('65', 'CargaDescarga.Eliminar', 'Permite eliminar carga y descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('66', 'TiempoCarga.Ver', 'Permite ver tiempo de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('67', 'TiempoCarga.Calcular', 'Permite clacular el tiempo de carga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('70', 'TiempoDescarga.Ver', 'Permite ver tiempo de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('71', 'TiempoDescarga.Calcular', 'Permite calcular tiempo de descarga.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('74', 'TarifaPago.Ver', 'Permite ver tarifas de pago.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('75', 'TarifaPago.Agregar', 'Permite agregar tarifas de pago.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('76', 'TarifaPago.Editar', 'Permite editar tarifas de pago.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('77', 'TarifaPago.Eliminar', 'Permite eliminar tarifas de pago.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('78', 'Entrada.Ver', 'Permite ver entradas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('79', 'Entrada.Agregar', 'Permite agregar entradas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('80', 'Entrada.Editar', 'Permite editar entradas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('81', 'Entrada.Eliminar', 'Permite eliminar entradas.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('82', 'SalidaSalarioTrabajador.Ver', 'Permite ver la salida del salario por trabajador.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('83', 'SalidaSalarioTrabajador.Calcular', 'Permite calcular la salida del salario por trabajador.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('84', 'SalidaSalarioEquipo.Ver', 'Permite ver la salida del salario por equipo.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('85', 'SalidaSalarioEquipo.Calcular', 'Permite calcular la salida del salario por equipos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('86', 'SalidaCumplimientoNorma.Ver', 'Permite ver la salida del cumplimiento de la norma.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('87', 'SalidaCumplimientoNorma.Calcular', 'Permite calcular la salida del cumplimiento de la norma.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('88', 'PeriodoPago.Abrir', 'Permite abrir el periodo de pago.\r\nNecesita tener permisos para ver la configuración');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('89', 'PeriodoPago.Cerrar', 'Permite cerrar el periodo de pago.\r\nNecesita tener permisos para ver la configuración.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('90', 'Configurar.Ver', 'Permite tener acceso a configurar. Esto no quiere decir que tenga permisos en todos los detalles de la configuración.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('91', 'Configurar.Mantenimiento', 'Permite darle mantenimiento (Optimizar) a las tablas de la base de datos.');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('92', 'ClavesSiscont.Agregar', 'Permite agregar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('93', 'ClavesSiscont.Editar', 'Permite editar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('94', 'ClavesSiscont.Ver', 'Permite ver claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('95', 'ClavesSiscont.Eliminar', 'Permite eleiminar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('96', 'ClavesSiscont.EditarSigla', 'Permite Editar las siglas en el modulo Clave de Siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('97', 'ClavesSiscont.Agregar', 'Permite agregar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('98', 'ClavesSiscont.Editar', 'Permite editar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('99', 'ClavesSiscont.Ver', 'Permite ver claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('100', 'ClavesSiscont.Eliminar', 'Permite eleiminar claves del siscont');
INSERT INTO `permiso` (`permiso_id`, `nombre`, `descripcion`) VALUES ('101', 'ClavesSiscont.EditarSigla', 'Permite Editar las siglas en el modulo Clave de Siscont');


#
# TABLE STRUCTURE FOR: salida_cumplimiento_norma
#

DROP TABLE IF EXISTS `salida_cumplimiento_norma`;

CREATE TABLE `salida_cumplimiento_norma` (
  `salida_cumplimiento_norma_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `producto` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `cumplimiento_norma` decimal(6,2) unsigned DEFAULT NULL,
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cumplimiento_norma_minorista` decimal(6,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`salida_cumplimiento_norma_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `salida_cumplimiento_norma` (`salida_cumplimiento_norma_id`, `producto`, `cumplimiento_norma`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`, `cumplimiento_norma_minorista`) VALUES ('1', 'Alcohol', '1302.91', '1456808400', '1459396800', NULL);


#
# TABLE STRUCTURE FOR: salida_salario_equipo
#

DROP TABLE IF EXISTS `salida_salario_equipo`;

CREATE TABLE `salida_salario_equipo` (
  `salida_salario_equipo_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `numero_operacional_equipo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `numero_operacional_cuna` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_viaje` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `fecha_inicio_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_final_periodo_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`salida_salario_equipo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `salida_salario_equipo` (`salida_salario_equipo_id`, `numero_operacional_equipo`, `numero_operacional_cuna`, `importe_viaje`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`) VALUES ('2', '0001', '00011', '28.69', '1456808400', '1459396800');


#
# TABLE STRUCTURE FOR: salida_salario_trabajador
#

DROP TABLE IF EXISTS `salida_salario_trabajador`;

CREATE TABLE `salida_salario_trabajador` (
  `salida_salario_trabajador_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `ci` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`salida_salario_trabajador_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `salida_salario_trabajador` (`salida_salario_trabajador_id`, `chapa`, `nombre`, `apellidos`, `horas_viaje`, `importe_viaje`, `cumplimiento_norma`, `horas_interrupto`, `horas_no_vinculado`, `horas_nocturnidad_corta`, `cuantia_horaria_nocturnidad_corta`, `horas_nocturnidad_larga`, `cuantia_horaria_nocturnidad_larga`, `fecha_inicio_periodo_pago`, `fecha_final_periodo_pago`, `horas_viaje_m`, `importe_viaje_m`, `cumplimiento_norma_m`, `horas_interrupto_m`, `horas_no_vinculado_m`, `horas_nocturnidad_corta_m`, `cuantia_horaria_nocturnidad_corta_m`, `horas_nocturnidad_larga_m`, `cuantia_horaria_nocturnidad_larga_m`, `ci`) VALUES ('2', '12345', 'Luis', 'lope', '1.00', '28.69', '1302.91', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '1456808400', '1459396800', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '43498779798');


#
# TABLE STRUCTURE FOR: tarifa_pago
#

DROP TABLE IF EXISTS `tarifa_pago`;

CREATE TABLE `tarifa_pago` (
  `tarifa_pago_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fk_categoria_operario_id` int(2) unsigned NOT NULL,
  `tarifa_menor` decimal(6,5) NOT NULL COMMENT 'tarifa para viajes menores e igual a 90 Km',
  `tarifa_mayor` decimal(6,5) NOT NULL COMMENT 'tarifa para viajes mayores a 90 Km',
  `tarifa_completa` decimal(6,5) NOT NULL,
  `tarifa_interrupcion` decimal(6,5) DEFAULT '0.00000',
  `tarifa_horario_escala` decimal(6,5) unsigned DEFAULT NULL,
  PRIMARY KEY (`tarifa_pago_id`),
  KEY `fk_categoria_operario_id` (`fk_categoria_operario_id`),
  CONSTRAINT `tarifa_pago_ibfk_1` FOREIGN KEY (`fk_categoria_operario_id`) REFERENCES `m_categoria_operario` (`m_categoria_operario_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES ('1', '5', '3.99170', '4.17270', '1.99580', '1.91580', NULL);
INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES ('2', '4', '4.35370', '4.53470', '2.17680', '2.01680', NULL);
INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES ('3', '3', '4.40400', '4.58500', '2.20200', '2.02100', NULL);
INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES ('4', '2', '4.58500', '4.76600', '2.29250', '2.13260', NULL);
INSERT INTO `tarifa_pago` (`tarifa_pago_id`, `fk_categoria_operario_id`, `tarifa_menor`, `tarifa_mayor`, `tarifa_completa`, `tarifa_interrupcion`, `tarifa_horario_escala`) VALUES ('5', '1', '4.70600', '4.88700', '2.35300', '2.16680', NULL);


#
# TABLE STRUCTURE FOR: tiempo_carga
#

DROP TABLE IF EXISTS `tiempo_carga`;

CREATE TABLE `tiempo_carga` (
  `tiempo_carga_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  `fk_lugar_carga_id` int(3) unsigned NOT NULL,
  `tiempo_carga` decimal(6,2) NOT NULL,
  PRIMARY KEY (`tiempo_carga_id`),
  KEY `fk_lugar_carga_id` (`fk_lugar_carga_id`),
  KEY `fk_producto_id` (`fk_producto_id`),
  KEY `tiempo_carga_id` (`tiempo_carga_id`),
  KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`),
  CONSTRAINT `tiempo_carga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  CONSTRAINT `tiempo_carga_ibfk_3` FOREIGN KEY (`fk_lugar_carga_id`) REFERENCES `m_lugar_carga` (`m_lugar_carga_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('1', '1', '2', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('2', '1', '12', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('3', '1', '7', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('4', '1', '6', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('5', '1', '5', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('6', '1', '3', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('7', '1', '10', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('8', '1', '11', '1', '17.53');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('9', '1', '9', '1', '11.68');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('10', '1', '2', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('11', '1', '12', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('12', '1', '7', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('13', '1', '6', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('14', '1', '5', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('15', '1', '3', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('16', '1', '10', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('17', '1', '11', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('18', '1', '9', '2', '8.76');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('19', '2', '13', '1', '34.02');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('20', '2', '8', '1', '17.01');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('21', '2', '4', '1', '17.01');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('22', '2', '8', '2', '8.51');
INSERT INTO `tiempo_carga` (`tiempo_carga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_carga_id`, `tiempo_carga`) VALUES ('23', '2', '4', '2', '8.51');


#
# TABLE STRUCTURE FOR: tiempo_descarga
#

DROP TABLE IF EXISTS `tiempo_descarga`;

CREATE TABLE `tiempo_descarga` (
  `tiempo_descarga_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_capacidad_carga_id` int(6) unsigned NOT NULL,
  `fk_producto_id` int(2) unsigned NOT NULL,
  `fk_lugar_descarga_id` int(3) unsigned NOT NULL,
  `fk_modo_descarga_id` int(2) unsigned NOT NULL,
  `tiempo_descarga` decimal(6,2) NOT NULL,
  PRIMARY KEY (`tiempo_descarga_id`),
  KEY `fk_modo_descarga_id` (`fk_modo_descarga_id`),
  KEY `fk_lugar_descarga_id` (`fk_lugar_descarga_id`),
  KEY `fk_producto_id` (`fk_producto_id`),
  KEY `fk_capacidad_carga_id` (`fk_capacidad_carga_id`),
  CONSTRAINT `tiempo_descarga_ibfk_2` FOREIGN KEY (`fk_producto_id`) REFERENCES `m_producto` (`m_producto_id`) ON UPDATE CASCADE,
  CONSTRAINT `tiempo_descarga_ibfk_3` FOREIGN KEY (`fk_lugar_descarga_id`) REFERENCES `m_lugar_descarga` (`m_lugar_descarga_id`) ON UPDATE CASCADE,
  CONSTRAINT `tiempo_descarga_ibfk_4` FOREIGN KEY (`fk_modo_descarga_id`) REFERENCES `m_modo_descarga` (`m_modo_descarga_id`) ON UPDATE CASCADE,
  CONSTRAINT `tiempo_descarga_ibfk_5` FOREIGN KEY (`fk_capacidad_carga_id`) REFERENCES `m_capacidad_carga` (`m_capacidad_carga_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('1', '1', '2', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('2', '1', '2', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('3', '1', '12', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('4', '1', '12', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('5', '1', '7', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('6', '1', '7', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('7', '1', '6', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('8', '1', '6', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('9', '1', '5', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('10', '1', '5', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('11', '1', '3', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('12', '1', '3', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('13', '1', '10', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('14', '1', '10', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('15', '1', '11', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('16', '1', '11', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('17', '1', '9', '1', '2', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('18', '1', '9', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('19', '1', '2', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('20', '1', '2', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('21', '1', '12', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('22', '1', '12', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('23', '1', '7', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('24', '1', '7', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('25', '1', '6', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('26', '1', '6', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('27', '1', '5', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('28', '1', '5', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('29', '1', '3', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('30', '1', '3', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('31', '1', '10', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('32', '1', '10', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('33', '1', '11', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('34', '1', '11', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('35', '1', '9', '1', '3', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('36', '1', '9', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('37', '1', '2', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('38', '1', '2', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('39', '1', '12', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('40', '1', '12', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('41', '1', '7', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('42', '1', '7', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('43', '1', '6', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('44', '1', '6', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('45', '1', '5', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('46', '1', '5', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('47', '1', '3', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('48', '1', '3', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('49', '1', '10', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('50', '1', '10', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('51', '1', '11', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('52', '1', '11', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('53', '1', '9', '1', '4', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('54', '1', '9', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('55', '1', '2', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('56', '1', '2', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('57', '1', '12', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('58', '1', '12', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('59', '1', '7', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('60', '1', '7', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('61', '1', '6', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('62', '1', '6', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('63', '1', '5', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('64', '1', '5', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('65', '1', '3', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('66', '1', '3', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('67', '1', '10', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('68', '1', '10', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('69', '1', '11', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('70', '1', '11', '1', '1', '5.84');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('71', '1', '9', '1', '5', '17.53');
INSERT INTO `tiempo_descarga` (`tiempo_descarga_id`, `fk_capacidad_carga_id`, `fk_producto_id`, `fk_lugar_descarga_id`, `fk_modo_descarga_id`, `tiempo_descarga`) VALUES ('72', '1', '9', '1', '1', '5.84');


#
# TABLE STRUCTURE FOR: updates
#

DROP TABLE IF EXISTS `updates`;

CREATE TABLE `updates` (
  `update_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) DEFAULT NULL,
  `fichero` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'waiting',
  `date` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;

INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('119', 'application/controllers/update.php', '0001update.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('120', 'css/vendor/bootstrap.css', 'css2014.12.17.08.14.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('121', 'application/views/entrada/agregar_v.php', 'ctrl2014.12.17.01.23.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('122', 'application/views/entrada/editar_v.php', 'ctrl2014.12.17.01.37.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('123', 'application/views/operario/agregar_v.php', 'ctrl2014.12.17.01.47.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('124', 'application/views/operario/editar_v.php', 'ctrl2014.12.17.01.50.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('125', 'application/views/periodo_pago/apertura_v.php', 'ctrl2014.12.17.01.51.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('126', 'application/views/salida_salario_trabajador/agregar_v.php', 'ctrl2014.12.17.01.56.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('127', 'application/views/salida_salario_trabajador/imprimir_v.php', 'ctrl2014.12.17.01.57.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('128', 'application/views/salida_salario_trabajador/layout_v.php', 'ctrl2014.12.17.01.58.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('129', 'application/views/salida_salario_trabajador/tabla_v.php', 'ctrl2014.12.17.01.59.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('130', 'application/views/template/t_main_menu.php', 'ctrl2014.12.17.02.02.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('131', 'application/views/claves_siscont/agregar_v.php', 'ctrl2014.12.17.02.04.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('132', 'application/views/claves_siscont/editar_v.php', 'ctrl2014.12.17.02.05.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('133', 'application/views/claves_siscont/layout_v.php', 'ctrl2014.12.17.02.06.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('134', 'application/views/claves_siscont/search_v.php', 'ctrl2014.12.17.02.07.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('135', 'application/views/claves_siscont/tabla_v.php', 'ctrl2014.12.17.02.08.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('136', 'application/controllers/auth.php', 'ctrl2014.12.17.08.30.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('137', 'application/controllers/entrada.php', 'ctrl2014.12.17.08.31.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('138', 'application/controllers/operario.php', 'ctrl2014.12.17.08.32.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('139', 'application/controllers/periodo_pago.php', 'ctrl2014.12.17.08.34.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('140', 'application/controllers/claves_siscont.php', 'ctrl2014.12.17.08.35.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('141', 'js/plugins.js', 'js2014.12.17.08.00.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('142', 'js/main.js', 'js2014.12.17.8.08.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('143', 'application/models/entrada_m.php', 'mdl2014.12.17.08.43.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('144', 'application/models/claves_siscont_m.php', 'mdl2014.12.17.08.44.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('145', 'application/models/operario_m.php', 'mdl2014.12.17.08.45.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('146', 'application/models/periodo_pago_m.php', 'mdl2014.12.17.08.47.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('147', 'application/models/salida_salario_equipo_m.php', 'mdl2014.12.17.08.48.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('148', 'application/models/salida_salario_trabajador_m.php', 'mdl2014.12.17.08.49.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('149', 'application/models', 'sql2014.12.17.05.37.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('150', 'application/models', 'sql2014.12.17.07.44.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('151', 'application/models', 'sql2014.12.17.07.51.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('152', 'application/libraries/Math.php', 'math2014.12.19.11.49.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('153', 'application/models', 'sql2014.12.19.12.00.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('154', 'application/controllers/salida_salario_trabajador.php', 'crtl2014.12.19.11.35.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('155', 'application/controllers/salida_salario_trabajador.php', 'ctrl2015.01.19.11.53.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('156', 'js/plugins.js', 'js2015.01.19.12.07.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('157', 'application/models/salida_salario_trabajador_m.php', 'mdl2015.01.19.11.55.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('158', 'application/models/entrada_m.php', 'mdl2015.01.19.11.56.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('159', 'application/models', 'sql2015.01.17.07.17.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('160', 'application/models', 'sql2015.01.17.10.15.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('161', 'application/models', 'sql2015.01.17.10.42.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('162', 'application/views/operario/tabla_v.php', 'view2015.01.19.11.59.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('163', 'application/views/salida_salario_trabajador/tabla_v.php', 'view2015.01.19.12.02.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('164', 'application/views/entrada/agregar_minorista_v.php', 'view2015.01.19.12.04.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('165', 'application/models/tarifa_pago_m.php', 'mdl2015.01.21.02.43.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('166', 'application/views/tarifa_pago/agregar_v.php', 'view2015.01.21.02.46.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('167', 'application/views/tarifa_pago/tabla_v_v.php', 'view2015.01.21.02.48.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('168', 'application/views/tarifa_pago/editar_v.php', 'view2015.01.21.02.50.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('169', 'application/controllers/salida_salario_trabajador.php', 'ctrl2015.04.23.03.16.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('170', 'application/controllers/periodo_pago.php', 'ctrl2015.05.11.11.56.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('171', 'application/views/entrada/editar_minorista_v.php', 'view2015.05.11.11.53.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('172', 'application/views/entrada/editar_v.php', 'view2015.05.11.11.54.dtj', 'done', '2016.03.20');
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('173', NULL, 'view2015.05.11.11.55.dtj', 'waiting', NULL);
INSERT INTO `updates` (`update_id`, `path`, `fichero`, `status`, `date`) VALUES ('174', 'application/controllers/periodo_pago.php', 'ctrl2015.05.11.11.53.dtj', 'done', '2016.03.20');


#
# TABLE STRUCTURE FOR: usuario
#

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `usuario_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'sirve para loguearse y para recuperar el password perdido',
  `nombre_login` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password_login` varchar(255) CHARACTER SET utf8 NOT NULL,
  `fecha_alta` date NOT NULL,
  `fk_empresa_id` int(1) unsigned NOT NULL,
  `fk_perfil_id` int(2) unsigned NOT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `fk_perfil_id` (`fk_perfil_id`),
  KEY `fk_empresa_id` (`fk_empresa_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`fk_empresa_id`) REFERENCES `empresa` (`empresa_id`) ON UPDATE CASCADE,
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`fk_perfil_id`) REFERENCES `perfil` (`perfil_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `usuario` (`usuario_id`, `nombre`, `apellidos`, `email`, `nombre_login`, `password_login`, `fecha_alta`, `fk_empresa_id`, `fk_perfil_id`) VALUES ('2', 'Michel', 'Reyes', 'michel@trans.cupet.cu', 'michel', '71e935fa8e4530a6093cf2d5d63858e3057827077119c58fbf8e5792bf9e6319e05b4c7f9cec3e72847baf7a36beda3ce6cf44e91249530e831369e8d53919b9t4NeHAuXWhAIdbG22oieyRoJyC2I+Ry0Sa8KkPmbV9w=', '2013-06-07', '1', '1');
INSERT INTO `usuario` (`usuario_id`, `nombre`, `apellidos`, `email`, `nombre_login`, `password_login`, `fecha_alta`, `fk_empresa_id`, `fk_perfil_id`) VALUES ('3', 'Rafael', 'Collazo León', 'rafael@cmg.trans.cupet.cu', 'rafael', 'ce15a454a5b2d8cbce9c67e43a29a15760081694', '1997-07-28', '1', '1');


