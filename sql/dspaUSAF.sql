--Tabla principal del proyecto USAF
CREATE TABLE `usaf_solicitudes` (
  `id_solicitud`            int(11) NOT NULL,
  `id_persona_usaf`         int(11) DEFAULT NULL,
  `fecha_solicitud_del`     date DEFAULT NULL,
  `delegacion`              tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion`           tinyint(3) UNSIGNED DEFAULT NULL,
  `id_persona_solicitante`  int(11) DEFAULT NULL,
  `usuario`                 char(8) DEFAULT NULL,
  `id_persona_titular`      int(11) DEFAULT NULL,
  `id_opcion`               int(11) DEFAULT NULL,
  `region1`                 char(1) DEFAULT NULL,
  `region2`                 char(1) DEFAULT NULL,
  `region3`                 char(1) DEFAULT NULL,
  `region4`                 char(1) DEFAULT NULL,
  `id_causa_rechazo`        int(11) NOT NULL,
  `comentario`              varchar(256) DEFAULT NULL,
  `archivo`                 varchar(64) DEFAULT NULL,
  `id_user_creacion`        int(11) DEFAULT NULL,
  `fecha_creacion`          datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user_modificacion`    int(11) DEFAULT NULL,
  `fecha_modificacion`      datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usaf_personas` (
  `id_persona`      int(11) NOT NULL,
  `delegacion`      tinyint(3) UNSIGNED DEFAULT NULL,
  `subdelegacion`   tinyint(3) UNSIGNED DEFAULT NULL,
  `id_puesto`       int(11) NOT NULL,
  `marca_encargo`   char(1) DEFAULT NULL,

  `matricula`       char(10) DEFAULT NULL,
  `curp`            varchar(18) DEFAULT NULL,
  `nss`             char(11) DEFAULT NULL,
  `nombre`          varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) DEFAULT NULL,
  `segundo_apellido`  varchar(50) DEFAULT NULL,

  `email`                 varchar(70) DEFAULT NULL,
  `id_user_creacion`      int(11) DEFAULT NULL,
  `fecha_creacion`        datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user_modificacion`  int(11) DEFAULT NULL,
  `fecha_modificacion`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `id_estatus`            char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usaf_opciones` (
  `id_opcion`   int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(32) DEFAULT NULL,
  `id_estatus`  char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usaf_causasrechazo` (
  `id_causa_rechazo` int(11) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  `id_estatus` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
ALTER TABLE `usaf_solicitudes`
  ADD PRIMARY KEY (`id_solicitud`);

ALTER TABLE `usaf_personas`
  ADD PRIMARY KEY (`id_persona`);

ALTER TABLE `usaf_opciones`
  ADD PRIMARY KEY (`id_opcion`);

ALTER TABLE `usaf_causasrechazo`
  ADD PRIMARY KEY (`id_causa_rechazo`);

--
ALTER TABLE `usaf_solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `usaf_personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
ALTER TABLE `usaf_solicitudes`
  ADD CONSTRAINT `usaf_sol_ibfk_1` FOREIGN KEY (`id_persona_solicitante`) REFERENCES `usaf_personas` (`id_persona`),
  ADD CONSTRAINT `usaf_sol_ibfk_2` FOREIGN KEY (`id_persona_titular`) REFERENCES `usaf_personas` (`id_persona`),
  ADD CONSTRAINT `usaf_sol_ibfk_3` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `usaf_sol_ibfk_4` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`),
  ADD CONSTRAINT `usaf_sol_ibfk_5` FOREIGN KEY (`id_opcion`) REFERENCES `usaf_opciones` (`id_opcion`),
  ADD CONSTRAINT `usaf_sol_ibfk_6` FOREIGN KEY (`id_causa_rechazo`) REFERENCES `usaf_causasrechazo` (`id_causa_rechazo`),
  ADD CONSTRAINT `usaf_sol_ibfk_7` FOREIGN KEY (`id_persona_usaf`) REFERENCES `usaf_personas` (`id_persona`),
  ADD CONSTRAINT `usaf_sol_ibfk_8` FOREIGN KEY (`id_user_creacion`) REFERENCES `dspa_usuarios` (`id_user`),
  ADD CONSTRAINT `usaf_sol_ibfk_9` FOREIGN KEY (`id_user_modificacion`) REFERENCES `dspa_usuarios` (`id_user`);

ALTER TABLE `usaf_personas`
  ADD CONSTRAINT `usaf_personas_ibfk_1` FOREIGN KEY (`id_puesto`) REFERENCES `dspa_puestos` (`id_puesto`),
  ADD CONSTRAINT `usaf_personas_ibfk_2` FOREIGN KEY (`delegacion`) REFERENCES `dspa_delegaciones` (`delegacion`),
  ADD CONSTRAINT `usaf_personas_ibfk_3` FOREIGN KEY (`delegacion`,`subdelegacion`) REFERENCES `dspa_subdelegaciones` (`delegacion`, `subdelegacion`),
  ADD CONSTRAINT `usaf_personas_ibfk_4` FOREIGN KEY (`id_user_creacion`) REFERENCES `dspa_usuarios` (`id_user`),
  ADD CONSTRAINT `usaf_personas_ibfk_5` FOREIGN KEY (`id_user_modificacion`) REFERENCES `dspa_usuarios` (`id_user`);
--
