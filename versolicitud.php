<?php

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Ver Solicitud - Gestión Cuentas SINDO';
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-VerSolicitud' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user 
            FROM  dspa_permisos
            WHERE id_modulo = 16
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');

    if ( !isset( $_GET['id_solicitud'] ) ) {
      $id_solicitud_bitacora = $_SESSION['id_solicitud'];
    } else {
      $id_solicitud_bitacora = $_GET['id_solicitud'];
    }

    $log = fnGuardaBitacora( 5, 108, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_solicitud:' . $id_solicitud_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

    exit(); 
  }

  $query = "SELECT 
    ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, 
    ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion, ctas_solicitudes.id_lote,
    ctas_solicitudes.delegacion, ctas_solicitudes.subdelegacion, 
    ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
    ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
    ctas_solicitudes.id_movimiento, ctas_solicitudes.id_grupo_actual, ctas_solicitudes.id_grupo_nuevo, 
    ctas_solicitudes.comentario, ctas_solicitudes.id_causarechazo, ctas_solicitudes.archivo,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
    FROM ctas_solicitudes, ctas_grupos grupos1, ctas_grupos grupos2, dspa_usuarios
    WHERE ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   ctas_solicitudes.id_user = dspa_usuarios.id_user ";

  if ( !isset( $_GET['id_solicitud'] ) ) {
    $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_SESSION['id_solicitud'] . "'";
    $id_solicitud_bitacora = $_SESSION['id_solicitud'];
    
  } else {
    $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_GET['id_solicitud'] . "'";
    $id_solicitud_bitacora = $_GET['id_solicitud'];
  }

  $data = mysqli_query( $dbc, $query );

  if ( mysqli_num_rows( $data ) == 1 ) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);

    $log = fnGuardaBitacora( 3, 108, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_solicitud:' . $id_solicitud_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

    echo '</br><p class="mensaje">¿Deseas <a href="editarsolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">editar esta solicitud</a>?</p>';
  ?>

  <div class="contenedor">
    <form>
      <h2>Datos de la solicitud</h2>
      <ul>
        <li>
          <label for="cmbValijas">Número de Valija/Oficio</label>
          <select disabled class="combo0" class="textinput" id="cmbValijas" name="cmbValijas">
            <?php
              $query = "SELECT ctas_valijas.id_valija AS id_valija2, 
                          ctas_valijas.delegacion AS num_del, 
                          dspa_delegaciones.descripcion AS delegacion_descripcion, 
                          ctas_valijas.num_oficio_del,
                          ctas_valijas.num_oficio_ca, 
                          ctas_valijas.id_user
                        FROM ctas_valijas, dspa_delegaciones 
                        WHERE ctas_valijas.delegacion = dspa_delegaciones.delegacion
                        AND ctas_valijas.id_valija = " . $row['id_valija'];
              $result = mysqli_query( $dbc, $query );
              while ( $row2 = mysqli_fetch_array( $result ) )
                echo '<option value="' . $row2['id_valija2'] . '" selected>' . $row2['num_oficio_ca'] . ': ' . $row2['num_del'] . '-' . $row2['delegacion_descripcion'] . '</option>';
            ?>
          </select>
        </li>

        <li>
          <label for="fecha_solicitud_del">Fecha solicitud</label>
          <input disabled type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if (!empty( $row['fecha_solicitud_del'] ) ) echo $row['fecha_solicitud_del']; ?>" />
        </li>

        <li>
          <label for="cmbtipomovimiento">Tipo de Movimiento</label>
          <select disabled class="combo0" id="cmbtipomovimiento" name="cmbtipomovimiento">
            <?php
              $query = "SELECT * 
                        FROM ctas_movimientos 
                        WHERE id_movimiento = " . $row['id_movimiento'];
              $result = mysqli_query( $dbc, $query );
              while ( $row2 = mysqli_fetch_array( $result ) )
                echo '<option value="' . $row2['id_movimiento'] . '" selected>' . $row2['descripcion'] . '</option>';
            ?>
          </select>
        </li>

        <li>
          <label for="cmbDelegaciones">Delegación IMSS</label>
          <select disabled class="combo0" class="textinput" id="cmbDelegaciones" name="cmbDelegaciones">
            <?php
              $query = "SELECT * 
                        FROM dspa_delegaciones 
                        WHERE delegacion = " . $row['delegacion'];
              $result = mysqli_query( $dbc, $query );
              while ( $row2 = mysqli_fetch_array( $result ) )
                echo '<option value="' . $row2['delegacion'] . '" selected>' . $row2['delegacion'] . ' - ' . $row2['descripcion'] . '</option>';
            ?>
          </select>
        </li>

        <li>
          <label for="cmbSubdelegaciones">Subdelegación IMSS</label>
          <select disabled class="combo0" id="cmbSubdelegaciones" name="cmbSubdelegaciones">
            <?php
              $query = "SELECT * 
                        FROM dspa_subdelegaciones 
                        WHERE delegacion = " . $row['delegacion'] . " AND subdelegacion = " . $row['subdelegacion'];
              $result = mysqli_query( $dbc, $query );
              while ( $row2 = mysqli_fetch_array( $result ) )
                echo '<option value="' . $row2['subdelegacion'] . '" selected>' . $row2['subdelegacion'] . ' - ' . $row2['descripcion'] . '</option>';
              ?>
            </select>
        </li>

        <li>
          <label for="primer_apellido">Primer apellido</label>
          <input disabled class="textinput" type="text" name="primer_apellido" id="primer_apellido" value="<?php if ( !empty( $row['primer_apellido'] ) ) echo $row['primer_apellido']; ?>"/>
        </li>

        <li>
          <label for="segundo_apellido">Segundo apellido</label>
          <input disabled class="textinput" type="text" name="segundo_apellido" id="segundo_apellido" value="<?php if ( !empty( $row['segundo_apellido'] ) ) echo $row['segundo_apellido']; ?>"/>
        </li>

        <li>
          <label for="nombre">Nombre(s)</label>
          <input disabled class="textinput" type="text" name="nombre" id="nombre" value="<?php if ( !empty( $row['nombre'] ) ) echo $row['nombre']; ?>"/>
        </li>

        <li>
          <label for="matricula">Matrícula</label>
          <input disabled class="textinput" type="text" name="matricula" id="matricula" value='<?php if ( !empty( $row['matricula'] ) ) echo $row['matricula']; ?>'/>
        </li>

        <li>
          <label for="curp">CURP (Usuario)</label>
          <input disabled class="textinput" type="text" name="curp" id="curp" value="<?php if ( !empty( $row['curp'] ) ) echo $row['curp']; ?>" />
        </li>

        <li>
          <label for="usuario">Usuario</label>
          <input disabled class="textinput" type="text" name="usuario" id="usuario" value="<?php if ( !empty( $row['usuario'] ) ) echo $row['usuario']; ?>" />
        </li>

        <li>
          <label for="cmbgpoactual">Grupo Actual</label>
          <select disabled class="combo0" id="cmbgpoactual" name="cmbgpoactual">
              <?php
                $query = "SELECT * 
                          FROM ctas_grupos 
                          WHERE id_grupo = " . $row['id_grupo_actual'];
                $result = mysqli_query( $dbc, $query );
                while ( $row2 = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
              ?>
            </select>
        </li>

        <li>
          <label for="cmbgponuevo">Grupo Nuevo</label>
          <select disabled class="combo0" id="cmbgponuevo" name="cmbgponuevo">
              <?php
                $query = "SELECT * 
                          FROM ctas_grupos 
                          WHERE id_grupo = " . $row['id_grupo_nuevo'];
                $result = mysqli_query( $dbc, $query );
                while ( $row2 = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
              ?>
            </select>
        </li>

        <li>
          <label for="cmbcausarechazo">Causa de Rechazo</label>
          <select disabled class="combo0" id="cmbcausarechazo" name="cmbcausarechazo">
              <?php
                $query = "SELECT * 
                          FROM ctas_causasrechazo
                          WHERE id_causarechazo = " . $row['id_causarechazo'];
                $result = mysqli_query( $dbc, $query );
                while ( $row2 = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row2['id_causarechazo'] . '" selected>' . $row2['id_causarechazo'] . ' - ' . $row2['descripcion'] . '</option>';
              ?>
          </select>
        </li>

        <li>
          <label for="comentario">Comentario</label>
          <textarea disabled class="textarea" id="comentario" name="comentario"><?php if ( !empty( $row['comentario'] ) ) echo $row['comentario']; ?></textarea>
        </li>

        <li>
          <label for="new_file">Archivo</label>
          <?php 
            if ( !empty( $row['archivo'] ) ) 
              echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">' . $row['archivo'] . '</a>';
            else echo '(Vacío)';
          ?>
        </li>

        <li>
          <label for="id_user">Capturada por:</label>
          <input disabled class="textinput" type="text" name="id_user" id="id_user" value="<?php if ( !empty( $row['creada_por'] ) ) echo $row['creada_por']; ?>"/>
        </li>

        <li>
          <label for="fecha_modificacion">Fecha de modificación</label>
          <input disabled class="text" type="text" name="fecha_modificacion" id="fecha_modificacion" value="<?php if ( !empty( $row['fecha_modificacion'] ) ) echo $row['fecha_modificacion']; ?>"/>
        </li>
     
        <li>
          <?php
            echo '</br><p class="mensaje">¿Deseas <a href="editarsolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">editar esta solicitud</a>?</p>';
          ?>
        </li>
      </ul>
    </form>
    </div>
    
  <?php
  }
  else {
    echo '</br><p class="error">No se localizó la solicitud con ID:' . $id_solicitud_bitacora . '. Verifica por favor con el Administrador del sitio.</p>';
  }

    // Insert the page footer
    require_once('lib/footer.php');
  ?>
