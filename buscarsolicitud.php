<?php

    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectvars.php');

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
      $query = "SELECT 
                  ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, 
                  ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion,
                  ctas_lotes.lote_anio AS num_lote_anio, 
                  ctas_solicitudes.delegacion AS num_del, dspa_delegaciones.descripcion AS delegacion_descripcion, 
                  ctas_valijas.delegacion AS num_del_val, 
                  ctas_solicitudes.subdelegacion AS num_subdel, dspa_subdelegaciones.descripcion AS subdelegacion_descripcion, 
                  ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
                  ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
                  ctas_movimientos.descripcion AS movimiento_descripcion, 
                  grupos1.descripcion AS grupo_nuevo, grupos2.descripcion AS grupo_actual, 
                  ctas_solicitudes.comentario, ctas_causasrechazo.id_causarechazo, ctas_causasrechazo.descripcion AS causa_rechazo, ctas_solicitudes.archivo,
                  CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por,
                  ctas_valijas.archivo AS archivovalija
                FROM ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2, dspa_usuarios, ctas_causasrechazo
                WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
                AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
                AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
                AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
                AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
                AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
                AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
                AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
                AND   ctas_solicitudes.id_user = dspa_usuarios.id_user
                AND   ctas_solicitudes.id_causarechazo = ctas_causasrechazo.id_causarechazo
                AND   ctas_solicitudes.usuario LIKE '%" . $usuario . "%' 
                ORDER BY ctas_solicitudes.fecha_captura_ca DESC, ctas_solicitudes.id_solicitud ASC, ctas_solicitudes.fecha_modificacion DESC";
          
        /*echo( $query );*/
        $data = mysqli_query($dbc, $query);

        echo '<p class="mensaje">Solicitudes localizadas</p>';
        echo '<table class="striped" border="1">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Valija</th>';
        echo '<th>Lote</th>';
        echo '<th># Área de Gestión</th>';
        echo '<th>Fecha Captura</th>';
        echo '<th>Creada/Modificada por</th>';
        echo '<th>Delegación - Subdelegación</th>';
        echo '<th>Nombre completo</th>';
        /*echo '<th>Matrícula</th>';*/
        echo '<th>Usuario(Mov)</th>';
        echo '<th>Grupo Actual->Nuevo</th>';
        echo '<th>Comentario</th>';
        echo '<th>Causa Rechazo</th>';
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
          if ( !empty( $row['archivovalija'] ) ) {
            echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivovalija'] . '"  target="_new">PDF Valija</a></td>';
          }
          else {
            echo '<td>(Sin PDF aún)</a></td>';
          }
          /*echo '<td><a href="vervalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';*/
          echo '<td>' . $row['num_lote_anio'] . '</td>';
          echo '<td>' . $row['num_oficio_ca'] . '</td>';
          echo '<td>' . $row['fecha_captura_ca'] . '</td>';
          echo '<td>' . $row['creada_por'] . '</td>';
          echo '<td class="mensaje">' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
          echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
          /*echo '<td>' . $row['matricula'] . '</td>'; */
          echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
          echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
          echo '<td>' . $row['comentario'] . '</td>';
          if ( !empty( $row['id_causarechazo'] ) && $row['id_causarechazo'] <> 0 )
            echo '<td class="error">' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';
          else echo '<td>' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';
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
