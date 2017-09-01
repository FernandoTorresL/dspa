-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-07-2017 a las 17:46:21
-- Versión del servidor: 5.6.20
-- Versión de PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dspa_web`
CREATE DATABASE IF NOT EXISTS `dspa_web` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_archivos`
--

CREATE TABLE `ctas_archivos` (
  `id_archivo` int(11) NOT NULL,
  `nombre_archivo` varchar(64) NOT NULL,
  `fecha_recepcion` datetime DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_causasrechazo`
--

CREATE TABLE `ctas_causasrechazo` (
  `id_causarechazo` int(11) NOT NULL,
  `descripcion` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_doc_recibidos`
--

CREATE TABLE `ctas_doc_recibidos` (
  `ANIO` char(4) DEFAULT NULL,
  `DOCUMENTO` varchar(32) NOT NULL DEFAULT '',
  `TIPO_DOCTO_D` char(15) DEFAULT NULL,
  `CLASIFICACION` char(7) DEFAULT NULL,
  `REF_DOCUMENTO` varchar(32) DEFAULT NULL,
  `FEC_DOCTO` date DEFAULT NULL,
  `FEC_RECEPCION` date DEFAULT NULL,
  `RMTE_NOMBRE` varchar(100) DEFAULT NULL,
  `RMTE_PUESTO` varchar(100) DEFAULT NULL,
  `RMTE_ORGANISMO` varchar(100) DEFAULT NULL,
  `DEST_NOMBRE` varchar(100) DEFAULT NULL,
  `AREA_D` char(3) DEFAULT NULL,
  `DEST_AREA` varchar(100) DEFAULT NULL,
  `ANEXOS` char(3) DEFAULT NULL,
  `ASUNTO` varchar(500) DEFAULT NULL,
  `INFORMACION` char(6) DEFAULT NULL,
  `DESC_AREA_S` varchar(100) DEFAULT NULL,
  `DESC_DEST_S` varchar(100) DEFAULT NULL,
  `ESTADO_S` char(13) DEFAULT NULL,
  `TIPO_DOCTO_S` char(15) DEFAULT NULL,
  `PRIORIDAD` char(7) DEFAULT NULL,
  `FEC_ATENCION` date DEFAULT NULL,
  `INSTRUCCION` varchar(50) DEFAULT NULL,
  `INSTRUC_DIRSS` varchar(50) DEFAULT NULL,
  `REFERENCIA_S` varchar(50) DEFAULT NULL,
  `FECHA_S` date DEFAULT NULL,
  `ESTADO_A` char(8) DEFAULT NULL,
  `NUM_OFICIO` varchar(20) DEFAULT NULL,
  `FEC_OFICIO` date DEFAULT NULL,
  `REFERENCIA_A` varchar(100) DEFAULT NULL,
  `ATRASO` char(3) DEFAULT NULL,
  `TEMA_DESC` varchar(32) DEFAULT NULL,
  `SUBTEMA_DESC` varchar(32) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_grupos`
--

CREATE TABLE `ctas_grupos` (
  `id_grupo` int(11) NOT NULL DEFAULT '0',
  `descripcion` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_hist_solicitudes`
--

CREATE TABLE `ctas_hist_solicitudes` (
  `id_hist_solicitud` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_valija` int(11) DEFAULT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_solicitud_del` date DEFAULT NULL,
  `fecha_modificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_lote` int(11) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `nombre` varchar(32) DEFAULT NULL,
  `primer_apellido` varchar(32) DEFAULT NULL,
  `segundo_apellido` varchar(32) DEFAULT NULL,
  `matricula` char(10) DEFAULT NULL,
  `curp` varchar(20) DEFAULT NULL,
  `curp_correcta` varchar(18) DEFAULT NULL,
  `cargo` varchar(256) DEFAULT NULL,
  `usuario` char(8) DEFAULT NULL,
  `id_movimiento` int(11) DEFAULT NULL,
  `id_grupo_nuevo` int(11) DEFAULT NULL,
  `id_grupo_actual` int(11) DEFAULT NULL,
  `comentario` varchar(256) DEFAULT NULL,
  `id_causarechazo` int(11) NOT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_hist_valijas`
--

CREATE TABLE `ctas_hist_valijas` (
  `id_hist_valija` int(11) NOT NULL,
  `id_valija` int(11) NOT NULL,
  `num_oficio_ca` varchar(32) DEFAULT NULL,
  `num_oficio_del` varchar(32) DEFAULT NULL,
  `fecha_recepcion_ca` date DEFAULT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_valija_del` date DEFAULT NULL,
  `id_remitente` int(11) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `comentario` varchar(500) DEFAULT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_lotes`
--

CREATE TABLE `ctas_lotes` (
  `id_lote` int(11) NOT NULL,
  `lote_anio` char(9) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `num_oficio_ca` varchar(32) NOT NULL,
  `fecha_oficio_ca` date DEFAULT NULL,
  `num_ticket_mesa` varchar(15) DEFAULT NULL,
  `fecha_atendido` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_movimientos`
--

CREATE TABLE `ctas_movimientos` (
  `id_movimiento` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_rechazosmainframe`
--

CREATE TABLE `ctas_rechazosmainframe` (
  `id_rechazomainframe` int(11) NOT NULL,
  `descripcion` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_resultadolotes`
--

CREATE TABLE `ctas_resultadolotes` (
  `id_resultadolote` int(11) NOT NULL,
  `id_lote` int(11) DEFAULT NULL,
  `fecha_correo` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_resultadosolicitudes`
--

CREATE TABLE `ctas_resultadosolicitudes` (
  `id_resultadosolicitud` int(11) NOT NULL,
  `id_resultadolote` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_mainframe` char(8) DEFAULT NULL,
  `nombre_mainframe` varchar(20) DEFAULT NULL,
  `id_grupo_mainframe` int(11) DEFAULT NULL,
  `instalation_data` char(10) DEFAULT NULL,
  `comentario` varchar(256) DEFAULT NULL,
  `id_rechazomainframe` int(11) DEFAULT '0',
  `marca_reintento` tinyint(1) NOT NULL DEFAULT '0',
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_solicitudes`
--

CREATE TABLE `ctas_solicitudes` (
  `id_solicitud` int(11) NOT NULL,
  `id_valija` int(11) DEFAULT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_solicitud_del` date DEFAULT NULL,
  `fecha_modificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_lote` int(11) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `nombre` varchar(32) DEFAULT NULL,
  `primer_apellido` varchar(32) DEFAULT NULL,
  `segundo_apellido` varchar(32) DEFAULT NULL,
  `matricula` char(10) DEFAULT NULL,
  `curp` varchar(20) DEFAULT NULL,
  `curp_correcta` varchar(18) DEFAULT NULL,
  `cargo` varchar(256) DEFAULT NULL,
  `usuario` char(8) DEFAULT NULL,
  `id_movimiento` int(11) DEFAULT NULL,
  `id_grupo_nuevo` int(11) DEFAULT NULL,
  `id_grupo_actual` int(11) DEFAULT NULL,
  `comentario` varchar(256) DEFAULT NULL,
  `id_causarechazo` int(11) NOT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ctas_valijas`
--

CREATE TABLE `ctas_valijas` (
  `id_valija` int(11) NOT NULL,
  `num_oficio_ca` varchar(32) DEFAULT NULL,
  `num_oficio_del` varchar(32) DEFAULT NULL,
  `fecha_recepcion_ca` date DEFAULT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_valija_del` date DEFAULT NULL,
  `id_remitente` int(11) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `comentario` varchar(500) DEFAULT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_aplicativos`
--

CREATE TABLE `dspa_aplicativos` (
  `id_aplicativo` tinyint(3) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_audita_accion`
--

CREATE TABLE `dspa_audita_accion` (
  `id_audita_accion` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_audita_act`
--

CREATE TABLE `dspa_audita_act` (
  `id_audita_act` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_delegaciones`
--

CREATE TABLE `dspa_delegaciones` (
  `delegacion` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `entidad_imss` tinyint(3) UNSIGNED DEFAULT NULL,
  `ciz` tinyint(3) UNSIGNED DEFAULT NULL,
  `descripcion` varchar(128) DEFAULT NULL,
  `descripcion_SINDO` varchar(128) DEFAULT NULL,
  `activo` char(1) DEFAULT NULL,
  `tipo_delegacion` char(1) DEFAULT NULL,
  `depto_laboral` char(1) DEFAULT NULL,
  `anio_ini_oper` year(4) DEFAULT NULL,
  `fecha_ini_oper` date DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `comentario` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_hist_puestos`
--

CREATE TABLE `dspa_hist_puestos` (
  `id_hist_puesto` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `fecha_ini` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_modulos`
--

CREATE TABLE `dspa_modulos` (
  `id_modulo` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `id_aplicativo` tinyint(3) UNSIGNED DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_permisos`
--

CREATE TABLE `dspa_permisos` (
  `id_permiso` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_modulo` tinyint(3) UNSIGNED DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_pistas_aud`
--

CREATE TABLE `dspa_pistas_aud` (
  `id_pista_aud` int(11) NOT NULL,
  `id_audita_act` tinyint(3) UNSIGNED DEFAULT '0',
  `id_audita_accion` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `dir_ip` varchar(32) DEFAULT NULL,
  `informacion` varchar(256) DEFAULT NULL,
  `fecha_pista_aud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_puestos`
--

CREATE TABLE `dspa_puestos` (
  `id_puesto` int(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_subdelegaciones`
--

CREATE TABLE `dspa_subdelegaciones` (
  `delegacion` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `subdelegacion` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `ciz` tinyint(3) UNSIGNED DEFAULT NULL,
  `cdsss_subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `descripcion` varchar(128) DEFAULT NULL,
  `descripcion_SINDO` varchar(128) DEFAULT NULL,
  `activo` char(1) DEFAULT NULL,
  `tipo_subdelegacion` char(1) DEFAULT NULL,
  `depto_audpatrones` char(1) DEFAULT NULL,
  `anio_ini_oper` year(4) DEFAULT NULL,
  `fecha_ini_oper` date DEFAULT NULL,
  `fecha_fin_oper` date DEFAULT NULL,
  `comentarios` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dspa_usuarios`
--

CREATE TABLE `dspa_usuarios` (
  `id_user` int(11) NOT NULL,
  `username` varchar(18) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_puesto` int(11) NOT NULL,
  `fecha_ini` date DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) DEFAULT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `picture` varchar(64) DEFAULT NULL,
  `id_estatus` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ctas_archivos`
--
ALTER TABLE `ctas_archivos`
  ADD PRIMARY KEY (`id_archivo`),
  ADD KEY `ctas_archivos_ibfk_1` (`id_user`);

--
-- Indices de la tabla `ctas_causasrechazo`
--
ALTER TABLE `ctas_causasrechazo`
  ADD PRIMARY KEY (`id_causarechazo`);

--
-- Indices de la tabla `ctas_doc_recibidos`
--
ALTER TABLE `ctas_doc_recibidos`
  ADD PRIMARY KEY (`DOCUMENTO`),
  ADD KEY `ctas_delegacion` (`delegacion`);

--
-- Indices de la tabla `ctas_grupos`
--
ALTER TABLE `ctas_grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `ctas_hist_solicitudes`
--
ALTER TABLE `ctas_hist_solicitudes`
  ADD PRIMARY KEY (`id_hist_solicitud`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_lote` (`id_lote`),
  ADD KEY `delegacion` (`delegacion`,`subdelegacion`),
  ADD KEY `id_valija` (`id_valija`),
  ADD KEY `id_movimiento` (`id_movimiento`),
  ADD KEY `id_grupo_nuevo` (`id_grupo_nuevo`),
  ADD KEY `id_grupo_actual` (`id_grupo_actual`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `ctas_hist_valijas`
--
ALTER TABLE `ctas_hist_valijas`
  ADD PRIMARY KEY (`id_hist_valija`),
  ADD KEY `id_valija` (`id_valija`),
  ADD KEY `delegacion` (`delegacion`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `ctas_lotes`
--
ALTER TABLE `ctas_lotes`
  ADD PRIMARY KEY (`id_lote`),
  ADD UNIQUE KEY `lote_anio` (`lote_anio`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `ctas_movimientos`
--
ALTER TABLE `ctas_movimientos`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `ctas_rechazosmainframe`
--
ALTER TABLE `ctas_rechazosmainframe`
  ADD PRIMARY KEY (`id_rechazomainframe`);

--
-- Indices de la tabla `ctas_resultadolotes`
--
ALTER TABLE `ctas_resultadolotes`
  ADD PRIMARY KEY (`id_resultadolote`),
  ADD KEY `ctas_resultadolotes_ibfk_1` (`id_lote`),
  ADD KEY `ctas_resultadolotes_ibfk_2` (`id_user`);

--
-- Indices de la tabla `ctas_resultadosolicitudes`
--
ALTER TABLE `ctas_resultadosolicitudes`
  ADD PRIMARY KEY (`id_resultadosolicitud`),
  ADD KEY `ctas_resultadosolicitudes_ibfk_1` (`id_resultadolote`),
  ADD KEY `ctas_resultadosolicitudes_ibfk_2` (`id_solicitud`),
  ADD KEY `ctas_resultadosolicitudes_ibfk_3` (`id_grupo_mainframe`),
  ADD KEY `ctas_resultadosolicitudes_ibfk_4` (`id_rechazomainframe`),
  ADD KEY `ctas_resultadosolicitudes_ibfk_5` (`id_user`);

--
-- Indices de la tabla `ctas_solicitudes`
--
ALTER TABLE `ctas_solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_lote` (`id_lote`),
  ADD KEY `delegacion` (`delegacion`,`subdelegacion`),
  ADD KEY `id_valija` (`id_valija`),
  ADD KEY `id_movimiento` (`id_movimiento`),
  ADD KEY `id_grupo_nuevo` (`id_grupo_nuevo`),
  ADD KEY `id_grupo_actual` (`id_grupo_actual`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `ctas_solicitudes_ibfk_9` (`id_causarechazo`);

--
-- Indices de la tabla `ctas_valijas`
--
ALTER TABLE `ctas_valijas`
  ADD PRIMARY KEY (`id_valija`),
  ADD KEY `delegacion` (`delegacion`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `dspa_aplicativos`
--
ALTER TABLE `dspa_aplicativos`
  ADD PRIMARY KEY (`id_aplicativo`);

--
-- Indices de la tabla `dspa_audita_accion`
--
ALTER TABLE `dspa_audita_accion`
  ADD PRIMARY KEY (`id_audita_accion`);

--
-- Indices de la tabla `dspa_audita_act`
--
ALTER TABLE `dspa_audita_act`
  ADD PRIMARY KEY (`id_audita_act`);

--
-- Indices de la tabla `dspa_delegaciones`
--
ALTER TABLE `dspa_delegaciones`
  ADD PRIMARY KEY (`delegacion`);

--
-- Indices de la tabla `dspa_hist_puestos`
--
ALTER TABLE `dspa_hist_puestos`
  ADD PRIMARY KEY (`id_hist_puesto`),
  ADD KEY `id_puesto` (`id_puesto`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `dspa_modulos`
--
ALTER TABLE `dspa_modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `id_aplicativo` (`id_aplicativo`);

--
-- Indices de la tabla `dspa_permisos`
--
ALTER TABLE `dspa_permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `dspa_pistas_aud`
--
ALTER TABLE `dspa_pistas_aud`
  ADD PRIMARY KEY (`id_pista_aud`),
  ADD KEY `id_audita_act` (`id_audita_act`),
  ADD KEY `id_audita_accion` (`id_audita_accion`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `dspa_puestos`
--
ALTER TABLE `dspa_puestos`
  ADD PRIMARY KEY (`id_puesto`);

--
-- Indices de la tabla `dspa_subdelegaciones`
--
ALTER TABLE `dspa_subdelegaciones`
  ADD PRIMARY KEY (`delegacion`,`subdelegacion`);

--
-- Indices de la tabla `dspa_usuarios`
--
ALTER TABLE `dspa_usuarios`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_puesto` (`id_puesto`),
  ADD KEY `delegacion` (`delegacion`,`subdelegacion`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ctas_archivos`
--
ALTER TABLE `ctas_archivos`
  ADD CONSTRAINT `ctas_archivos_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `ctas_doc_recibidos`
--
ALTER TABLE `ctas_doc_recibidos`
  ADD CONSTRAINT `ctas_doc_recibidos_ibfk_1` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`);

--
-- Filtros para la tabla `ctas_lotes`
--
ALTER TABLE `ctas_lotes`
  ADD CONSTRAINT `ctas_lotes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `ctas_resultadolotes`
--
ALTER TABLE `ctas_resultadolotes`
  ADD CONSTRAINT `ctas_resultadolotes_ibfk_1` FOREIGN KEY (`id_lote`) REFERENCES `ctas_lotes` (`id_lote`),
  ADD CONSTRAINT `ctas_resultadolotes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `ctas_resultadosolicitudes`
--
ALTER TABLE `ctas_resultadosolicitudes`
  ADD CONSTRAINT `ctas_resultadosolicitudes_ibfk_1` FOREIGN KEY (`id_resultadolote`) REFERENCES `ctas_resultadolotes` (`id_resultadolote`),
  ADD CONSTRAINT `ctas_resultadosolicitudes_ibfk_2` FOREIGN KEY (`id_solicitud`) REFERENCES `ctas_solicitudes` (`id_solicitud`),
  ADD CONSTRAINT `ctas_resultadosolicitudes_ibfk_3` FOREIGN KEY (`id_grupo_mainframe`) REFERENCES `ctas_grupos` (`id_grupo`),
  ADD CONSTRAINT `ctas_resultadosolicitudes_ibfk_4` FOREIGN KEY (`id_rechazomainframe`) REFERENCES `ctas_rechazosmainframe` (`id_rechazomainframe`),
  ADD CONSTRAINT `ctas_resultadosolicitudes_ibfk_5` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `ctas_solicitudes`
--
ALTER TABLE `ctas_solicitudes`
  ADD CONSTRAINT `ctas_solicitudes_ibfk_1` FOREIGN KEY (`id_lote`) REFERENCES `ctas_lotes` (`id_lote`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_2` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_3` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_4` FOREIGN KEY (`id_valija`) REFERENCES `ctas_valijas` (`id_valija`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_5` FOREIGN KEY (`id_movimiento`) REFERENCES `ctas_movimientos` (`id_movimiento`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_6` FOREIGN KEY (`id_grupo_nuevo`) REFERENCES `ctas_grupos` (`id_grupo`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_7` FOREIGN KEY (`id_grupo_actual`) REFERENCES `ctas_grupos` (`id_grupo`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_8` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_9` FOREIGN KEY (`id_causarechazo`) REFERENCES `ctas_causasrechazo` (`id_causarechazo`);

--
-- Filtros para la tabla `ctas_valijas`
--
ALTER TABLE `ctas_valijas`
  ADD CONSTRAINT `ctas_valijas_ibfk_1` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `ctas_valijas_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `dspa_hist_puestos`
--
ALTER TABLE `dspa_hist_puestos`
  ADD CONSTRAINT `dspa_hist_puestos_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `dspa_puestos` (`id_puesto`),
  ADD CONSTRAINT `dspa_hist_puestos_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `dspa_modulos`
--
ALTER TABLE `dspa_modulos`
  ADD CONSTRAINT `dspa_modulos_ibfk_1` FOREIGN KEY (`id_aplicativo`) REFERENCES `dspa_aplicativos` (`id_aplicativo`);

--
-- Filtros para la tabla `dspa_pistas_aud`
--
ALTER TABLE `dspa_pistas_aud`
  ADD CONSTRAINT `dspa_pistas_aud_ibfk_1` FOREIGN KEY (`id_audita_act`) REFERENCES `dspa_audita_act` (`id_audita_act`),
  ADD CONSTRAINT `dspa_pistas_aud_ibfk_2` FOREIGN KEY (`id_audita_accion`) REFERENCES `dspa_audita_accion` (`id_audita_accion`),
  ADD CONSTRAINT `dspa_pistas_aud_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `dspa_usuarios` (`id_user`);

--
-- Filtros para la tabla `dspa_subdelegaciones`
--
ALTER TABLE `dspa_subdelegaciones`
  ADD CONSTRAINT `dspa_subdelegaciones_ibfk_1` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`);

--
-- Filtros para la tabla `dspa_usuarios`
--
ALTER TABLE `dspa_usuarios`
  ADD CONSTRAINT `dspa_usuarios_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `dspa_puestos` (`id_puesto`),
  ADD CONSTRAINT `dspa_usuarios_ibfk_2` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `dspa_usuarios_ibfk_3` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
