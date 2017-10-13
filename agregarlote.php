<?php
    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectBD.php');

    require_once('commonfiles/funciones.php');

    // Insert the page header
    $page_title = 'Agregar Lote - Gestión Cuentas SINDO ';
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
    $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-AgregarLote' );
    if ( !$ResultadoConexion ) {
      // Hubo un error en la conexión a la base de datos;
      printf( " Connect failed: %s", mysqli_connect_error() );
      require_once('lib/footer.php');
      exit();
    }

    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    $query = "SELECT id_user 
              FROM dspa_permisos
              WHERE id_modulo = 9
              AND   id_user   = " . $_SESSION['id_user'];
    
    $data = mysqli_query($dbc, $query);

    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para éste módulo
    }
    else {
      echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
      require_once('lib/footer.php');
      $log = fnGuardaBitacora( 5, 101, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
      exit(); 
    }

    if ( isset( $_POST['submit'] ) ) {

      $new_lote   = mysqli_real_escape_string( $dbc, trim( $_POST['new_lote'] ) );
      $comentario = mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );

      $output_form = 'no';
    }

    if ( isset( $_POST['submit'] ) ) {
      
      if ( empty( $new_lote ) ) {
        echo '<p class="error">Olvidaste capturar un Número de Lote</p>';
        $output_form = 'yes';
      }
      else {
        if ( !preg_match( '/^[0-9][0-9][0-9]/', $new_lote ) ) {
          echo '<p class="error">Número de lote inválido. Usar solo formato 3 dígitos "0XX" (ejemplo: 045) c</p>';
          $output_form = 'yes';
        }
      }

      if ( $output_form == 'no' ) {

        $query = "INSERT INTO ctas_lotes 
                    ( lote_anio, fecha_creacion, fecha_modificacion, comentario, id_user, num_oficio_ca, fecha_oficio_ca, num_ticket_mesa, fecha_atendido )
                    VALUES 
                      ( CONCAT('D','$new_lote', '/2017' ), NOW(), NOW(), '$comentario', " . $_SESSION['id_user'] . ", 'PENDIENTE', NULL, 'PENDIENTE', NULL )";
        
        mysqli_query($dbc, $query);
        $query = "SELECT LAST_INSERT_ID()";
        $data = mysqli_query( $dbc, $query );

        if ( mysqli_num_rows( $data ) == 1 ) {
          // The user row was found so display the user data
          $row = mysqli_fetch_array($data);
          $id_lote_bitacora = $row['LAST_INSERT_ID()'];
          $log = fnGuardaBitacora( 1, 101, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_lote:' . $id_lote_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
          echo '<p class="mensaje"><strong>¡El nuevo lote ha sido creado correctamente!</strong></p></br>';
          echo '<p class="mensaje">Puedes agregar una <a href="agregarvalija.php">nueva valija</a></p>';
          echo '<p class="mensaje">Puedes agregar una <a href="agregarsolicitud.php">nueva solicitud</a></p></br>';
          echo '<p class="mensaje">O puede regresar al <a href="index.php">inicio</a></p>';

          $query = "SELECT  ctas_lotes.lote_anio, ctas_lotes.fecha_creacion, ctas_lotes.fecha_modificacion, ctas_lotes.comentario, 
                            ctas_lotes.id_user, CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creado_por
                    FROM ctas_lotes, dspa_usuarios
                    WHERE ctas_lotes.id_user = dspa_usuarios.id_user ";
          $query = $query . "AND ctas_lotes.id_lote = '" . $row['LAST_INSERT_ID()'] . "'";
          $data = mysqli_query( $dbc, $query );

          if ( mysqli_num_rows( $data ) == 1 ) {
            // The user row was found so display the user data
            $rowB = mysqli_fetch_array($data);
          ?>
          <div class="contenedor">
            <form>
              <h2>Datos del Lote</h2>
              <ul>
                <li>
                  <label for="new_lote">Nuevo Lote</label>
                  <input disabled class="textinputsmall" type="text" required name="new_lote" id="new_lote" maxlength="9" value="<?php if ( !empty( $rowB['lote_anio'] ) ) echo $rowB['lote_anio'] ?>"/>
                </li>

                <li>
                  <label for="comentario">Comentario</label>
                  <textarea disabled class="textarea" id="comentario" name="comentario" maxlength="256"><?php if ( !empty( $rowB['comentario'] ) ) echo $rowB['comentario']; ?></textarea>
                </li>

                <li>
                  <label for="id_user">Capturado por:</label>
                  <input disabled class="textinputsmall" type="text" name="id_user" id="id_user" value="<?php if ( !empty( $rowB['creado_por'] ) ) echo $rowB['creado_por']; ?>"/>
                </li>

              </ul>
            </form>
          </div>
          <?php
          }
          else {
            echo '<p class="error"><strong>El nuevo lote no ha podido generarse. Contactar al administrador.</strong></p>';
          }
        }
        else {
          echo '<p class="error"><strong>El nuevo lote no ha podido generarse. Contactar al administrador.</strong></p>';
        }
        $_POST['new_lote']    = "";
        $_POST['comentario'] = "";
        mysqli_close( $dbc );
      }

    }

    if ( $output_form == 'yes' ) {
    ?>

      <div class="contenedor">
        <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h2>Datos del Lote</h2>
          <ul>
            <li>
              <label for="new_lote">Nuevo Lote:</label>
              <span class="etiquetaCourier">D
              <input class="textinputmini" type="text" required name="new_lote" id="new_lote" maxlength="4" placeholder="000" value="<?php if ( !empty( $new_lote ) ) echo $new_lote; ?>"/>
              /2017</span>
            </li>

            <li>
              <label for="comentario">Comentario</label>
              <textarea class="textarea" id="comentario" name="comentario" maxlength="256" placeholder="Escriba comentarios (opcional)"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
            </li>

            <li class="buttons">
              <input type="submit" name="submit" value="Agregar lote">
              <input type="reset" name="reset" value="Reset">
            </li>

          </ul>
        </form>
      </div>

      <?php

    }
    ?>

    <?php
      require_once('lib/footer.php');
    ?>




