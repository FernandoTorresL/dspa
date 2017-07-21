--Tabla principal del proyecto USAF
CREATE TABLE `usaf_solicitudes` (
  `id_solicitud` int(11) NOT NULL,
  `id_solicitante` int(11) DEFAULT NULL,
  `fecha_solicitud_del` date DEFAULT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `usuario_titular` char(8) DEFAULT NULL,
  `nombre_titular` varchar(32) DEFAULT NULL,
  `primer_apellido_titular` varchar(32) DEFAULT NULL,
  `segundo_apellido_titular` varchar(32) DEFAULT NULL,
  `curp_titular` varchar(20) DEFAULT NULL,
  `matricula_titular` char(10) DEFAULT NULL,
  `nss_titular` char(11) DEFAULT NULL,
  `id_opcion` int(11) DEFAULT NULL,
  `region1` char(1) DEFAULT NULL,
  `region2` char(1) DEFAULT NULL,
  `region3` char(1) DEFAULT NULL,
  `id_causa_rechazo` int(11) NOT NULL,
  `comentario` varchar(256) DEFAULT NULL,
  `archivo` varchar(64) DEFAULT NULL,
  `id_user_creacion` int(11) DEFAULT NULL,
  `fecha_captura_ca` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `usaf_solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_solicitante_ibfk` (`id_solicitante`),
  ADD KEY `delegacion_ibfk` (`delegacion`,`subdelegacion`),
  ADD KEY `id_opcion_ibfk` (`id_opcion`),
  ADD KEY `id_causarechazo_ibfk` (`id_causarechazo`),
  ADD KEY `id_user_creacion_ibfk` (`id_user_creacion`),
  ADD KEY `id_user_modificacion_ibfk` (`id_user_modificacion`);

ALTER TABLE `ctas_solicitudes`
  ADD CONSTRAINT `ctas_solicitudes_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `usaf_solicitantes` (`id_solicitante`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_2` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_3` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_4` FOREIGN KEY (`id_opcion`) REFERENCES `usaf_opciones` (`id_opcion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_5` FOREIGN KEY (`id_causarechazo`) REFERENCES `usaf_causasrechazo` (`id_causarechazo`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_6` FOREIGN KEY (`id_user_creacion`) REFERENCES `dspa_usuarios` (`id_user_creacion`),
  ADD CONSTRAINT `ctas_solicitudes_ibfk_7` FOREIGN KEY (`id_user_modificacion`) REFERENCES `dspa_usuarios` (`id_user_modificacion`);

CREATE TABLE `usaf_solicitantes` (
  `id_solicitante` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `delegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion` tinyint(3) UNSIGNED DEFAULT NULL,
  `fecha_ini` date DEFAULT NULL,
  `nombre_solicitante` varchar(50) DEFAULT NULL,
  `primer_apellido_solicitante` varchar(50) DEFAULT NULL,
  `segundo_apellido_solicitante` varchar(50) DEFAULT NULL,
  `curp_solicitante` varchar(18) DEFAULT NULL,
  `matricula_solicitante` char(10) DEFAULT NULL,
  `nss_solicitante` char(11) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `id_estatus` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `usaf_solicitantes`
  ADD PRIMARY KEY (`id_solicitante`),
  ADD KEY `id_puesto_ibfk` (`id_puesto`),
  ADD KEY `delegacion_ibfk` (`delegacion`,`subdelegacion`);

ALTER TABLE `usaf_solicitantes`
  ADD CONSTRAINT `usaf_solicitantes_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `dspa_puestos` (`id_puesto`),
  ADD CONSTRAINT `usaf_solicitantes_ibfk_2` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `usaf_solicitantes_ibfk_3` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`);


CREATE TABLE `usaf_opciones` (
  `id_opcion` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `usaf_opciones`
  ADD PRIMARY KEY (`id_opcion`);








