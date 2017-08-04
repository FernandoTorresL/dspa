<?php

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = MM_APPNAME;
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-RegistrarSolicitud USAF' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user 
            FROM dspa_permisos
            WHERE id_modulo = 22
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos para acceder a este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 303, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }

  if ( isset( $_POST['submit'] ) ) {

    if ( isset ( $_POST['cmbPersonaUSAF'] ) )
      $cmbPersonaUSAF =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbPersonaUSAF'] ) );
    else
      $cmbPersonaUSAF = -1;

    $fecha_solicitud_del =  mysqli_real_escape_string( $dbc, trim( $_POST['fecha_solicitud_del'] ) );
    $cmbDelegaciones =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    
    if ( isset ( $_POST['cmbSubdelegaciones'] ) )
      $cmbSubdelegaciones =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegaciones'] ) );
    else
      $cmbSubdelegaciones = -1; 
    
    if ( isset ( $_POST['cmbPersonaSolicitante'] ) )
      $cmbPersonaSolicitante =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbPersonaSolicitante'] ) );
    else
      $cmbPersonaSolicitante = -1; 
    
    $usuario =              mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['usuario'] ) ) );
    
    if ( isset ( $_POST['cmbPersonaTitular'] ) )
      $cmbPersonaTitular =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbPersonaTitular'] ) );
    else
      $cmbPersonaTitular = -1;

    if ( isset ( $_POST['cmbOpcion'] ) )
      $cmbOpcion =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbOpcion'] ) );
    else
      $cmbOpcion = -1; 

    if ( isset ( $_POST['chkRegion1'] ) )
      $chkRegion1 =            mysqli_real_escape_string( $dbc, trim( $_POST['chkRegion1'] ) );
    else
      $chkRegion1 ='0';

    if ( isset ( $_POST['chkRegion2'] ) )
      $chkRegion2 =            mysqli_real_escape_string( $dbc, trim( $_POST['chkRegion2'] ) );
    else
      $chkRegion2 ='0';

    if ( isset ( $_POST['chkRegion3'] ) )
      $chkRegion3 =            mysqli_real_escape_string( $dbc, trim( $_POST['chkRegion3'] ) );
    else
      $chkRegion3 ='0';

    if ( isset ( $_POST['chkRegion4'] ) )
      $chkRegion4 =            mysqli_real_escape_string( $dbc, trim( $_POST['chkRegion4'] ) );
    else
      $chkRegion4 ='0';

    $cmbcausarechazo =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbcausarechazo'] ) );
    $comentario =           mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    /*$new_file =             mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size'];*/

    $output_form = 'no';
  }

  if ( isset( $_POST['submit'] ) ) {

    if ( empty( $cmbPersonaUSAF ) || 
            ( $cmbPersonaUSAF == 0 ) || 
            ( $cmbPersonaUSAF == -1 ) 
          ) {
      echo '<p class="error">Olvidaste seleccionar quién atendió la solicitud.</p>';
      $output_form = 'yes';
    }

    if ( !preg_match( '/^[0-9]{9}$/', $fecha_solicitud_del ) ) {
      $anio = substr( $fecha_solicitud_del, 0, 4 );
      $mes  = substr( $fecha_solicitud_del, 5, 2 );
      $dia  = substr( $fecha_solicitud_del, 8, 2 );
      
      if ( !checkdate( $mes, $dia, $anio ) ) {
        echo '<p class="error">Fecha de la solicitud inválida. ';
        echo 'Año:'  . $anio;
        echo ' Mes:'         . $mes;
        echo ' Día:'  . $dia  . '</p>';
        $output_form = 'yes';
      }
    }

    if ( empty( $cmbDelegaciones ) || 
            ( $cmbDelegaciones == 0 ) || 
            ( $cmbDelegaciones == -1 ) 
          ) {
      echo '<p class="error">Olvidaste seleccionar una Delegación.</p>';
      $output_form = 'yes';
    }

    //Para que puedan trabajar en IE las capturas:
    /*if ( ( empty( $cmbSubdelegaciones ) ) )  {*/
    if ( ( empty( $cmbSubdelegaciones ) || $cmbSubdelegaciones == -1 ) && $cmbSubdelegaciones <> 0 )  {
      echo '<p class="error">Olvidaste seleccionar una Subdelegación.</p>';
      $output_form = 'yes';
    }

    if ( ( empty( $cmbPersonaSolicitante ) || $cmbPersonaSolicitante == -1 ) && $cmbPersonaSolicitante <> 0 )  {
      echo '<p class="error">Olvidaste seleccionar al Solicitante</p>';
      $output_form = 'yes';
    }

    if ( empty( $usuario ) ) {
      echo '<p class="advertencia">Olvidaste capturar Usuario(USER-ID). ¿Es correcto?</p>';
    }

    if ( ( empty( $cmbPersonaTitular ) || $cmbPersonaTitular == -1 ) && $cmbPersonaTitular <> 0 )  {
      echo '<p class="error">Olvidaste seleccionar al Titular de la Cuenta</p>';
      $output_form = 'yes';
    }

    if ( empty( $cmbOpcion ) ) {
      echo '<p class="error">Olvidaste seleccionar Opción solicitada.</p>';
      $output_form = 'yes';
    }

    if ( empty( $chkRegion1 ) && empty( $chkRegion2 ) && empty( $chkRegion3 ) && empty( $chkRegion4 )) {
      echo '<p class="error">Olvidaste seleccionar una región ¿es correcto?</p>';
    }

    if ( ( empty( $cmbcausarechazo ) || $cmbcausarechazo  == -1 ) && $cmbcausarechazo <> 0 )  {
      echo '<p class="error">Olvidaste capturar Causa de Rechazo</p>';
      $output_form = 'yes';
    }

    if ( $output_form == 'no' ) {
      // Conectarse a la BD
      $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
      $query = "INSERT INTO usaf_solicitudes (
                  id_persona_usaf,
                  fecha_solicitud_del,
                  delegacion, subdelegacion,
                  id_persona_solicitante,
                  usuario, id_persona_titular,
                  id_opcion, region1, region2, region3, region4,
                  id_causa_rechazo, comentario,
                  id_user_creacion, id_user_modificacion
                  )
                VALUES (
                  $cmbPersonaUSAF,
                  '$fecha_solicitud_del',
                  $cmbDelegaciones, $cmbSubdelegaciones, 
                  $cmbPersonaSolicitante,
                  '$usuario', $cmbPersonaTitular,
                  $cmbOpcion, $chkRegion1, $chkRegion2, $chkRegion3, $chkRegion4,
                  $cmbcausarechazo, '$comentario', 
                  " . $_SESSION['id_user'] . " , " . $_SESSION['id_user'] . " )";
      /*echo $query;*/
      mysqli_query( $dbc, $query );

      $query = "SELECT LAST_INSERT_ID()";
      $data = mysqli_query( $dbc, $query );
      if ( mysqli_num_rows( $data ) == 1 ) {
        // The user row was found so display the user data
        $row = mysqli_fetch_array($data);

        $id_sol_usaf_bitacora = $row['LAST_INSERT_ID()'];
        $log = fnGuardaBitacora( 1, 303, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_sol_usaf:' . $id_sol_usaf_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

        echo '<p class="mensaje"><strong>¡Se ha registrado correctamente una nueva solicitud!</strong></p>';
        /*echo '<p class="mensaje">¿Hubo un error? Puede EDITAR el <a href="editarpersona.php?id_persona=' . $row['LAST_INSERT_ID()'] . '">registro de la persona</a></p>';*/
        echo '<p class="mensaje">Puede registrar una <a href="reg_sol_usaf.php">nueva solicitud</a></p>';
/*        echo '<p class="mensaje">O puede regresar al <a href="index.php">inicio</a></p>';*/

        $query = "SELECT 
                    US.id_sol_usaf,
                    US.id_persona_usaf, CONCAT( P1.nombre, ' ', P1.primer_apellido) AS personaUSAF,
                    US.fecha_solicitud_del, DATE_FORMAT(US.fecha_solicitud_del, '%d%M%y') AS fSolDelFormato,
                    US.delegacion, US.subdelegacion,
                    US.id_persona_solicitante, CONCAT( P2.nombre, ' ', P2.primer_apellido) AS personaSolicitante,
                    US.usuario,
                    US.id_persona_titular, CONCAT( P3.nombre, ' ', P3.primer_apellido) AS personaTitular,
                    US.id_opcion, US.region1, US.region2, US.region3, US.region4,
                    US.id_causa_rechazo,
                    US.comentario,
                    US.id_user_creacion, CONCAT(DU1.nombre, ' ', DU1.primer_apellido) AS creada_por,
                    US.fecha_creacion, DATE_FORMAT(US.fecha_creacion, '%d%M%y %H:%i') AS fCreacionFormato,
                    US.id_user_modificacion, CONCAT(DU2.nombre, ' ', DU2.primer_apellido) AS modificada_por,
                    US.fecha_modificacion, DATE_FORMAT(US.fecha_modificacion, '%d%M%y %H:%i') AS fModificacionFormato
                  FROM 
                    ( ( ( ( (
                    usaf_solicitudes US JOIN usaf_personas P1 
                      ON US.id_persona_usaf = P1.id_persona )
                        JOIN usaf_personas P2 
                          ON US.id_persona_solicitante = P2.id_persona )
                            JOIN usaf_personas P3 
                              ON US.id_persona_titular = P3.id_persona )
                                JOIN dspa_usuarios DU1 
                                  ON US.id_user_creacion = DU1.id_user )
                                    JOIN dspa_usuarios DU2 
                                      ON US.id_user_modificacion = DU2.id_user )";

        $query = $query . "WHERE US.id_sol_usaf = '" . $row['LAST_INSERT_ID()'] . "'";
        /*echo $query;*/
        $data = mysqli_query( $dbc, $query );

        if ( mysqli_num_rows( $data ) == 1 )
          // The user row was found so display the user data
          $rowB = mysqli_fetch_array($data);
        //Missing else...
        ?>
        <div class="contenedor">
          <form>
          <h2>Registro de Solicitud USAF</h2>
          <ul>
            <li>
              <label for="cmbPersonaUSAF">Persona que atendió</label>
              <select disabled class="combo0" id="cmbPersonaUSAF" name="cmbPersonaUSAF">
                <?php
                  //Mostrar usuarios normativos
                  $query = "SELECT * 
                            FROM usaf_personas
                            WHERE   id_persona = " . $rowB['id_persona_usaf'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['id_persona'] . '" selected>' . $row2['primer_apellido'] . ' ' . $row2['segundo_apellido'] . ' ' . $row2['nombre'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="fecha_solicitud_del">Fecha solicitud:</label>
              <input disabled class="textinput" type="text" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if (!empty($rowB['fSolDelFormato'])) echo $rowB['fSolDelFormato']; ?>" />
            </li>
            <li>
              <label for="cmbDelegaciones">Delegación IMSS Solicitante</label>
              <select disabled class="combo0" id="cmbDelegaciones" name="cmbDelegaciones">
                <?php
                  $query = "SELECT * 
                            FROM dspa_delegaciones 
                            WHERE delegacion = " . $rowB['delegacion'];
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
                            WHERE delegacion = " . $rowB['delegacion'] . " AND subdelegacion = " . $rowB['subdelegacion'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['subdelegacion'] . '" selected>' . $row2['subdelegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
            </li>
            <li>
              <label for="cmbPersonaSolicitante">Persona que solicita</label>
              <select disabled class="combo0" id="cmbPersonaSolicitante" name="cmbPersonaSolicitante">
                <?php
                  $query = "SELECT * 
                            FROM usaf_personas
                            WHERE   id_persona = " . $rowB['id_persona_solicitante'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['id_persona'] . '" selected>' . $row2['primer_apellido'] . ' ' . $row2['segundo_apellido'] . ' ' . $row2['nombre'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="usuario">Usuario</label>
              <input disabled class="textinput" type="text" name="usuario" id="usuario" value="<?php if ( !empty( $rowB['usuario'] ) ) echo $rowB['usuario']; ?>" />
            </li>
            <li>
              <label for="cmbPersonaTitular">Titular de la cuenta</label>
              <select disabled class="combo0" id="cmbPersonaTitular" name="cmbPersonaTitular">
                <?php
                  $query = "SELECT * 
                            FROM    usaf_personas
                            WHERE   id_persona = " . $rowB['id_persona_titular'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['id_persona'] . '" selected>' . $row2['primer_apellido'] . ' ' . $row2['segundo_apellido'] . ' ' . $row2['nombre'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="cmbOpcion">Opción solicitada</label>
              <select disabled class="combo0" id="cmbOpcion" name="cmbOpcion">
                <?php
                  $query = "SELECT  * 
                            FROM    usaf_opciones
                            WHERE   id_opcion = " . $rowB['id_opcion'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['id_opcion'] . '" selected>'  . $row2['id_opcion'] . ' - ' . $row2['descripcion'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="chkMarcaEncargo">Región</label>
              <input disabled type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value=
                <?php 
                  if (empty( $rowB['region1'] ))
                    echo '""'; 
                  elseif ( $rowB['region1'] == 1 )
                    echo '"1" checked';
                  else
                    echo '""'; 
                ?>
              />CIZ1

              <input disabled type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value=
                <?php 
                  if (empty( $rowB['region2'] ))
                    echo '""'; 
                  elseif ( $rowB['region2'] == 1 )
                    echo '"1" checked';
                  else
                    echo '""'; 
                ?>
              />CIZ2

              <input disabled type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value=
                <?php 
                  if (empty( $rowB['region3'] ))
                    echo '""'; 
                  elseif ( $rowB['region3'] == 1 )
                    echo '"1" checked';
                  else
                    echo '""'; 
                ?>
              />CIZ3

              <input disabled type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value=
                <?php 
                  if (empty( $rowB['region4'] ))
                    echo '""'; 
                  elseif ( $rowB['region4'] == 1 )
                    echo '"1" checked';
                  else
                    echo '""'; 
                ?>
              />CIZ4
              <br>
            </li>
            <li>
              <label for="cmbcausarechazo">Causa de Rechazo</label>
              <select disabled class="combo0" id="cmbcausarechazo" name="cmbcausarechazo">
                  <?php
                    $query = "SELECT  * 
                              FROM    usaf_causasrechazo
                              WHERE   id_causa_rechazo = " . $rowB['id_causa_rechazo'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_causa_rechazo'] . '" selected>' . $row2['id_causa_rechazo'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
              </select>
            </li>
            <li>
              <label for="comentario">Comentario</label>
              <textarea disabled class="textarea" id="comentario" name="comentario"><?php if ( !empty( $rowB['comentario'] ) ) echo $rowB['comentario']; ?></textarea>
            </li>
            <li>
              <label for="id_user">Capturada por:</label>
              <input disabled class="textinputsmall" type="text" name="id_user" id="id_user" value="<?php if ( !empty( $rowB['creada_por'] ) ) echo $rowB['creada_por']; ?>"/>
            </li>
            <li>
              <label for="fecha_creacion">Fecha de captura</label>
              <input disabled class="textinput" type="text" name="fecha_creacion" id="fecha_creacion" value="<?php if ( !empty( $rowB['fCreacionFormato'] ) ) echo $rowB['fCreacionFormato']; ?>"/>
            </li>
            <li>
              <label for="id_user_modificacion">Modificada por:</label>
              <input disabled class="textinputsmall" type="text" name="id_user_modificacion" id="id_user_modificacion" value="<?php if ( !empty( $rowB['modificada_por'] ) ) echo $rowB['modificada_por']; ?>"/>
            </li>
            <li>
              <label for="fecha_modificacion">Fecha de modificación</label>
              <input disabled class="textinput" type="text" name="fecha_modificacion" id="fecha_modificacion" value="<?php if ( !empty( $rowB['fModificacionFormato'] ) ) echo $rowB['fModificacionFormato']; ?>"/>
            </li>
            <br/>
          </ul>
          </form>
        </div>

      <?php
      }
      else
        echo '<p class="error"><strong>La nueva solicitud no ha podido registrarse. Contactar al administrador.</strong></p>';

      // Clear the score data to clear the form
      $_POST['cmbPersonaUSAF'] = 0;
      $_POST['fecha_solicitud_del'] = "";
      $_POST['cmbDelegaciones'] = 0;
      $_POST['cmbSubdelegaciones'] = -1;
      $_POST['cmbPersonaSolicitante'] = 0;
      $_POST['usuario'] = "";
      $_POST['cmbPersonaTitular'] = 0;
      $_POST['cmbOpcion'] = 0;
      $_POST['chkRegion1'] = "";
      $_POST['chkRegion2'] = "";
      $_POST['chkRegion3'] = "";
      $_POST['chkRegion4'] = "";
      $_POST['cmbcausarechazo'] = -1;
      $_POST['comentario'] = "";
      
      mysqli_close( $dbc );
    } //if ( $output_form == 'no' ) {
    else
      echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar a la persona.</p>';
  } //if ( isset( $_POST['submit'] ) ) {
  else
    echo '<p class="nota"><strong>Captura todos los datos obligatorios</strong></p>';

  if ( $output_form == 'yes' ) {
    ?>

      <div class="contenedor">
        <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h2>Captura los datos de la solicitud USAF</h2>
          <ul>
            <li>
              <label for="cmbPersonaUSAF">Persona que atendió</label>
              <select class="combo0" id="cmbPersonaUSAF" name="cmbPersonaUSAF">
              <option value="-1">Seleccione Persona</option>
                <?php
                  //Mostrar usuarios normativos
                  $query = "SELECT * 
                            FROM usaf_personas
                            WHERE   id_puesto = 2 
                            AND     delegacion = 9
                            AND     id_estatus = 1
                            ORDER BY primer_apellido, segundo_apellido, nombre";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['id_persona'] . '" ' . fntPersonaUSAFSelect( $row['id_persona'] ) . '>' . $row['primer_apellido'] . ' ' . $row['segundo_apellido'] . ' ' . $row['nombre'] . '-'. $row['curp'] . '-'. $row['matricula'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="fecha_solicitud_del">Fecha solicitud:</label>
              <input type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if (!empty($fecha_solicitud_del)) echo $fecha_solicitud_del; ?>" />
            </li>
            <li>
              <label for="cmbDelegaciones">Delegación IMSS Solicitante</label>
              <select class="combo0" id="cmbDelegaciones" name="cmbDelegaciones">
                <option value="0">Seleccione Delegación</option>
                <?php
                  $query = "SELECT * 
                            FROM dspa_delegaciones 
                            WHERE activo = 1
                            ORDER BY delegacion";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="cmbSubdelegaciones">Subdelegación IMSS</label>
              <select class="combo0" id="cmbSubdelegaciones" name="cmbSubdelegaciones">
                <option value="-1">Seleccione Subdelegación</option>
                <?php
                    if ( !empty( $_POST['cmbSubdelegaciones'] ) ) {
                      if ( $_POST['cmbSubdelegaciones'] == "0" ) {
                      $query = "SELECT * 
                                FROM dspa_subdelegaciones 
                                WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion";
                      $result = mysqli_query( $dbc, $query );
                      }
                    }
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
                  ?>
                </select>
            </li>
            <li>
              <label for="cmbPersonaSolicitante">Persona que solicita</label>
              <select class="combo0" id="cmbPersonaSolicitante" name="cmbPersonaSolicitante">
              <option value="-1">Seleccione Persona</option>
                <?php
                  //Mostrar personas
                  $query = "SELECT * 
                            FROM    usaf_personas
                            WHERE   id_estatus = 1
                            ORDER BY primer_apellido, segundo_apellido, nombre";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['id_persona'] . '" ' . fntPersonaSolicitanteSelect( $row['id_persona'] ) . '>' . $row['primer_apellido'] . ' ' . $row['segundo_apellido'] . ' ' . $row['nombre'] . '-'. $row['curp'] . '-'. $row['matricula'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="usuario">Usuario</label>
              <input class="textinput" type="text" required name="usuario" id="usuario" maxlength="8" placeholder="Escriba el usuario" value="<?php if ( !empty( $usuario ) ) echo $usuario; ?>" />
            </li>
            <li>
              <label for="cmbPersonaTitular">Titular de la cuenta</label>
              <select class="combo0" id="cmbPersonaTitular" name="cmbPersonaTitular">
              <option value="-1">Seleccione Persona</option>
                <?php
                  //Mostrar personas
                  $query = "SELECT * 
                            FROM    usaf_personas
                            WHERE   id_estatus = 1
                            ORDER BY primer_apellido, segundo_apellido, nombre";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['id_persona'] . '" ' . fntPersonaTitularSelect( $row['id_persona'] ) . '>' . $row['primer_apellido'] . ' ' . $row['segundo_apellido'] . ' ' . $row['nombre'] . '-'. $row['curp'] . '-'. $row['matricula'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="cmbOpcion">Opción solicitada</label>
              <select class="combo0" id="cmbOpcion" name="cmbOpcion">
                <option value="0">Seleccione Opción solicitada</option>
                <?php
                  $query = "SELECT * 
                            FROM usaf_opciones
                            WHERE id_estatus = 1
                            ORDER BY id_opcion ASC";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['id_opcion'] . '" ' . fnOpcionUSAFSelect( $row['id_opcion'] ) . '>'  . $row['id_opcion'] . ' - ' . $row['descripcion'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="chkMarcaEncargo">Región CIZ</label>
              <input type="checkbox" name="chkRegion1" id="chkRegion1" value='1'>CIZ 1
              <input type="checkbox" name="chkRegion2" id="chkRegion2" value='1'>CIZ 2
              <input type="checkbox" name="chkRegion3" id="chkRegion3" value='1'>CIZ 3
              <input type="checkbox" name="chkRegion4" id="chkRegion4" value='1'>CIZ 4
            </li>
            <li>
              <label for="cmbcausarechazo">Causa de Rechazo</label>
              <select class="combo0" id="cmbcausarechazo" name="cmbcausarechazo">
                  <option value="-1">Seleccione Causa de Rechazo</option>
                  <?php
                    $query = "SELECT * 
                              FROM usaf_causasrechazo
                              WHERE id_causa_rechazo <> -1
                              AND   id_estatus = 1
                              ORDER BY id_causa_rechazo ASC";
                    $result = mysqli_query( $dbc, $query );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['id_causa_rechazo'] . '" ' . fntcmbcausarechazoSelect( $row['id_causa_rechazo'] ) .'>' . $row['id_causa_rechazo'] . ' - ' . $row['descripcion'] . '</option>';
                  ?>
              </select>
            </li>
            <li>
              <label for="comentario">Comentario</label>
              <textarea class="textinput" id="comentario" name="comentario" maxlength="256" placeholder="Escriba comentarios (opcional)"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
            </li>
            <br/>
              <li class="buttons">
                <input type="submit" name="submit" value="Registrar Solicitud">
                <input type="reset" name="reset" value="Limpiar Forma">
              </li>
          </ul>
        </form>
      </div>
    <?php
      }
    // Insert the page footer
    require_once('lib/footer.php');
  ?>
