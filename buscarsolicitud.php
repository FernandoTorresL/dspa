<?php

    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectBD.php');

    require_once('commonfiles/funciones.php');

    // Insert the page header
    $page_title = 'Buscar Solicitud - Gestión Cuentas SINDO ';
    require_once('lib/header.php');

    // Show the navigation menu
    require_once('lib/navmenu.php');

    $error_msg = "";
    $output_form = 'yes';

    // Make sure the user is logged in before going any further.
    if ( !isset( $_SESSION['id_user'] ) ) {
      echo '<p class="error">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
      require_once('lib/footer.php');
      exit();
    }

    // Connect to the database
    $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-BuscarSolicitud' );
    if ( !$ResultadoConexion ) {
      // Hubo un error en la conexión a la base de datos;
      printf( " Connect failed: %s", mysqli_connect_error() );
      require_once('lib/footer.php');
      exit();
    }


    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    $query = "SELECT id_user 
              FROM dspa_permisos
              WHERE id_modulo = 21
              AND   id_user   = " . $_SESSION['id_user'];
    /*echo $query;*/
    $data = mysqli_query($dbc, $query);

    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para éste módulo
    }
    else {
      echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
      require_once('lib/footer.php');
      $log = fnGuardaBitacora( 5, 114, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
      exit(); 
    }

    ?>
    <div class="contenedor">
      <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Búsqueda de solicitud</h2>
        <ul>
          <li>
            <label for="usuario">Usuario</label>
            <input class="textinputsmall" type="text" required name="usuario" id="usuario" maxlength="7" placeholder="Usuario a buscar" value="<?php if ( !empty( $usuario ) ) echo $usuario; ?>" />
          </li>
          <br/>
          <li class="buttons">
            <input type="submit" name="submit" value="Buscar Usuario">
            <input type="reset" name="reset" value="Reset">
          </li>
        </ul>
      </form>
    </div>
    
    <?php
      
    if ( isset( $_POST['submit'] ) ) {

      $usuario = mysqli_real_escape_string( $dbc, trim( $_POST['usuario'] ) );

      $output_form = 'no';

      if ( empty( $usuario ) ) {
        echo '<p class="error">Olvidaste capturar el dato a buscar</p>';
        $output_form = 'yes';
      }

      // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
      $query = 'SELECT  
                  S.id_solicitud, S.id_valija, V.num_oficio_ca, V.fecha_recepcion_ca, 
                  S.fecha_captura_ca, S.fecha_solicitud_del, S.fecha_modificacion,
                  L.lote_anio AS num_lote_anio, 
                  S.delegacion AS num_del, D.descripcion AS delegacion_descripcion, 
                  V.delegacion AS num_del_val, 
                  S.subdelegacion AS num_subdel, SD.descripcion AS subdelegacion_descripcion, 
                  S.nombre, S.primer_apellido, S.segundo_apellido, 
                  S.matricula, S.curp, S.curp_correcta, S.cargo, S.usuario, 
                  M.descripcion AS movimiento_descripcion, 
                  G2.descripcion AS grupo_actual, G1.descripcion AS grupo_nuevo, 
                  S.comentario, 
                  CR.id_causarechazo AS causa_rechazo,
                  CR.descripcion AS descripcion_causa_rechazo,
                  RM.id_rechazomainframe as causa_rechazo_MAINFRAME,
                  RM.descripcion as descripcion_causa_rechazo_MAINFRAME,
                  L.fecha_atendido as fecha_atendido,
                  RL.fecha_correo as timestamp_correo_MAINFRAME,
                  RL.archivo as correo_MAINFRAME,
                  RS.usuario_mainframe as usuario_MAINFRAME,

CASE 
WHEN ( S.id_causarechazo <> 0 ) THEN S.comentario
WHEN ( S.id_causarechazo = 0 AND RS.id_rechazomainframe <> 0 ) THEN CONCAT( IF( S.comentario IS NULL, "", CONCAT( S.comentario, " " ) ), "/", RM.descripcion, IF( RS.comentario IS NULL, "", CONCAT( " (", RS.comentario, ")" ) ) ) 
WHEN ( S.id_causarechazo = 0 AND RS.id_rechazomainframe = 0 ) THEN CONCAT ( IF( S.comentario IS NULL, "", CONCAT( S.comentario, " " ) ), IF( RS.comentario IS NULL, "", CONCAT( "(", RS.comentario, ")" ) ) )
ELSE "OTRO"
END AS "Observaciones",
                  S.archivo, 
                  CONCAT(DU.nombre, " ", DU.primer_apellido) AS creada_por,
                  V.archivo AS archivovalija
                FROM 
                ( ( ( ( ( ( ( ( ( (
                  ( ctas_solicitudes S LEFT JOIN ( ctas_resultadosolicitudes RS, ctas_rechazosmainframe RM, ctas_resultadolotes RL )
                    ON ( ( S.id_solicitud = RS.id_solicitud AND RM.id_rechazomainframe = RS.id_rechazomainframe ) AND RS.id_resultadolote = RL.id_resultadolote ) )
                    JOIN dspa_usuarios DU
                        ON S.id_user = DU.id_user ) 
                      JOIN `ctas_valijas` V
                        ON S.id_valija = V.id_valija )
                          JOIN `ctas_lotes` L
                            ON S.id_lote = L.id_lote )
                              JOIN ctas_movimientos M
                                ON S.id_movimiento = M.id_movimiento )
                                  JOIN ctas_grupos G1
                                    ON S.id_grupo_nuevo = G1.id_grupo )
                                      JOIN ctas_grupos G2
                                        ON S.id_grupo_actual = G2.id_grupo )
                                          JOIN dspa_delegaciones D
                                            ON S.delegacion = D.delegacion ) 
                                              JOIN dspa_subdelegaciones SD
                                                ON ( S.subdelegacion = SD.subdelegacion AND D.delegacion = SD.delegacion ) )
                                                  JOIN dspa_delegaciones D2
                                                    ON V.delegacion = D2.delegacion )
                                                      JOIN ctas_causasrechazo CR
                                                        ON S.id_causarechazo = CR.id_causarechazo )
                WHERE   S.usuario LIKE "%' . $usuario . '%" ';

        $query = $query . " ORDER BY S.fecha_captura_ca DESC, S.id_solicitud ASC, S.fecha_modificacion DESC;";
          
        /*echo( $query );*/
        $data = mysqli_query($dbc, $query);

        echo '<p class="mensaje">Solicitudes localizadas</p>';
        echo '<table class="striped" border="1">';
        echo '<tr>';
        echo '<th>#</th>';
        /*echo '<th># Valija</th>';*/
        echo '<th>Lote</th>';
        echo '<th># Área de Gestión</th>';
        echo '<th>PDF Valija</th>';
        echo '<th>Fecha Captura Solicitud</th>';
        echo '<th>Creada/Modificada por</th>';
        echo '<th>Delegación - Subdelegación</th>';
        echo '<th>Nombre completo</th>';
        /*echo '<th>Matrícula</th>';*/
        echo '<th>Usuario(Mov)</th>';
        echo '<th>Grupo Actual->Nuevo</th>';
        echo '<th>Causa Rechazo DSPA</th>';
        echo '<th>Causa Rechazo Mainframe</th>';
        echo '<th>Comentario</th>';
        echo '<th>Fecha Atención Mainframe</th>';
        echo '<th>PDF</th>';
        echo '</tr>';

        if (mysqli_num_rows($data) == 0) {
          echo '</table></br><p class="error">No se localizaron solicitudes.</p></br>';
        }

        $i = 1;
        while ( $row = mysqli_fetch_array($data) ) {

          echo '<tr class="dato condensed">';
          /*echo '<td class="lista">' . $row['id_valija'] . '</td>';*/
          echo '<td>' . $i. '</td>';
          echo '<td>' . $row['num_lote_anio'] . '</td>';
          echo '<td class="mensaje"><a target="_blank" href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['num_oficio_ca'] . '
            </a></td>';
          if ( !empty( $row['archivovalija'] ) ) {
            echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivovalija'] . '"  target="_new">PDF Valija</a></td>';
          }
          else {
            echo '<td>(Sin PDF aún)</a></td>';
          }

          echo '<td>' . $row['fecha_captura_ca'] . '</td>';
          echo '<td>' . $row['creada_por'] . '</td>';
          echo '<td class="mensaje">' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
          echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
          /*echo '<td>' . $row['matricula'] . '</td>'; */
          echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
          echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
          
          switch ( $row['causa_rechazo'] ) {
            case 0:
              echo '<td>' . $row['causa_rechazo'] .'-' . $row['descripcion_causa_rechazo'] . '</td>';
              if ( is_null( $row['fecha_atendido'] ) && is_null( $row['causa_rechazo_MAINFRAME'] ) )
                echo '<td>EN ESPERA RESPUESTA MAINFRAME</td>';
              elseif ( !is_null( $row['fecha_atendido'] ) && is_null( $row['causa_rechazo_MAINFRAME'] ) )
                echo '<td>SIN DOCUMENTAR AÚN RESPUESTA MAINFRAME</td>';
              else
                switch ( $row['causa_rechazo_MAINFRAME'] ) {
                  case 0:
                    echo '<td class="mensaje" align=center>ATENDIDA (' . $row['usuario_MAINFRAME'] . ')</td>';
                    break;
                  default:
                    # code...
                    echo '<td class="error">' . $row['causa_rechazo_MAINFRAME'] .'-' . $row['descripcion_causa_rechazo_MAINFRAME'] . '</td>';
                    break;
                }
              break;

            default:
              # code...
              echo '<td class="error">' . $row['causa_rechazo'] .'-' . $row['descripcion_causa_rechazo'] . '</td>';
              echo '<td class="error">SIN TRAMITAR A MAINFRAME-SIN DOCUMENTAR</td>';
              break;
          }

          echo '<td>' . $row['comentario'] . '</td>';

          /*echo '<td>' . $row['timestamp_correo_MAINFRAME'] . '</td>';

          correo_MAINFRAME*/
          if (!empty($row['correo_MAINFRAME'])) {
            echo '<td><a href="' . MM_UPLOADPATH_MSG . '\\' . $row['correo_MAINFRAME'] . '"  target="_new">' . $row['timestamp_correo_MAINFRAME'] . '</a></td>';
          }
          else {
            echo '<td>(PDF aún no disponible)</a></td>';
            /*echo '<td>' . $row['timestamp_correo_MAINFRAME'] . '</td>';*/
          }

          if (!empty($row['archivo'])) {
            echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">Ver PDF</a></td>';
          }
          else {
            echo '<td>(Vacío)</a></td>';
          } 
          echo '</tr>';
          $i = $i + 1;
        }    
        echo '</table></br></br>';

              /*$id_valija_bitacora = $row['LAST_INSERT_ID()'];*/
        $log = fnGuardaBitacora( 3, 114, $_SESSION['id_user'],  $_SESSION['ip_address'], 'Búsqueda:' . $usuario . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

        $output_form = 'yes';
    }
    require_once('lib/footer.php');
  ?>
