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
            WHERE id_modulo = 23
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos para acceder a este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 301, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }

  if ( isset( $_POST['submit'] ) ) {

    $cmbDelegaciones =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    if ( isset ( $_POST['cmbSubdelegaciones'] ) )
      $cmbSubdelegaciones =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegaciones'] ) );
    else
      $cmbSubdelegaciones = -1; 
    if ( isset ( $_POST['cmbPuesto'] ) )
      $cmbPuesto =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbPuesto'] ) );
    else
      $cmbPuesto = -1; 
    if ( isset ( $_POST['chkMarcaEncargo'] ) )
      $chkMarcaEncargo =            mysqli_real_escape_string( $dbc, trim( $_POST['chkMarcaEncargo'] ) );
    else
      $chkMarcaEncargo ='0';
    $matricula =            mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['matricula'] ) ) );
    $curp =                 mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['curp'] ) ) );
    $nss =                  mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['nss'] ) ) );
    $primer_apellido =      mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['primer_apellido'] ) ) );
    $segundo_apellido =     mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['segundo_apellido'] ) ) );
    $nombre =               mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['nombre'] ) ) );

    $email =                mysqli_real_escape_string( $dbc, strtolower( trim( $_POST['email'] ) ) );

    $output_form = 'no';
  }

  if ( isset( $_POST['submit'] ) ) {

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

    if ( ( empty( $cmbPuesto ) || $cmbPuesto == -1 ) && $cmbPuesto <> 0 )  {
      echo '<p class="error">Olvidaste seleccionar un puesto</p>';
      $output_form = 'yes';
    }

    if ( empty( $matricula ) ) {
      echo '<p class="advertencia">Olvidaste capturar Matrícula. ¿Es correcto?</p>';
    }

    if ( empty( $curp ) ) {
      echo '<p class="advertencia">Olvidaste capturar CURP. ¿Es correcto?</p>';
    } 
    if ( strlen( trim($curp) ) >18 ) {
        echo '<p class="advertencia">CURP mayor a 18 caracteres. Revisar que así se proporcionó el dato</p>';
    } elseif ( strlen( trim($curp) ) <18 ) {
        echo '<p class="advertencia">CURP menor a 18 caracteres. Revisar que así se proporcionó el dato</p>';
    }

    if ( empty( $nss ) ) {
      echo '<p class="advertencia">Olvidaste capturar NSS.</p>';
    } 
    if ( strlen( trim($nss) ) >11 ) {
        echo '<p class="advertencia">NSS mayor a 11 caracteres. Revisar que así se proporcionó el dato</p>';
    } elseif ( strlen( trim($nss) ) <11 ) {
        echo '<p class="advertencia">NSS menor a 11 caracteres. Revisar que así se proporcionó el dato</p>';
    }

     if ( empty( $primer_apellido ) ) {
      echo '<p class="error">Olvidaste capturar el Primer Apellido.</p>';
      $output_form = 'yes';
    }

    if ( empty( $nombre ) ) {
      echo '<p class="error">Olvidaste capturar el Nombre.</p>';
      $output_form = 'yes';
    }

    if ( empty( $email ) ) {
      echo '<p class="error">Olvidaste capturar Correo Electrónico.</p>';
      $output_form = 'yes';
    }

    if ( empty( $segundo_apellido ) ) 
      echo '<p class="advertencia">Olvidaste capturar Segundo Apellido. ¿Es correcto?';

    if ( $output_form == 'no' ) {

      // Conectarse a la BD
      $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
      $query = "INSERT INTO usaf_personas (
                  delegacion, subdelegacion,
                  id_puesto, marca_encargo,
                  matricula, curp, nss,
                  nombre, primer_apellido, segundo_apellido, email,
                  id_user_creacion, id_user_modificacion,
                  id_estatus
                  )
                VALUES (
                  '$cmbDelegaciones', '$cmbSubdelegaciones',
                  '$cmbPuesto', '$chkMarcaEncargo',
                  '$matricula', '$curp', '$nss',
                  '$nombre', '$primer_apellido', '$segundo_apellido', '$email', "
                  . $_SESSION['id_user'] . ", " . $_SESSION['id_user'] . ", '1' )";
      mysqli_query( $dbc, $query );

      $query = "SELECT LAST_INSERT_ID()";
      $data = mysqli_query( $dbc, $query );
      if ( mysqli_num_rows( $data ) == 1 ) {
        // The user row was found so display the user data
        $row = mysqli_fetch_array($data);

        $id_persona_bitacora = $row['LAST_INSERT_ID()'];
        $log = fnGuardaBitacora( 1, 301, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_persona:' . $id_persona_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

        echo '<p class="mensaje"><strong>¡Se ha registrado correctamente a una nueva persona!</strong></p>';
        /*echo '<p class="mensaje">¿Hubo un error? Puede EDITAR el <a href="editarpersona.php?id_persona=' . $row['LAST_INSERT_ID()'] . '">registro de la persona</a></p>';*/
        echo '<p class="mensaje">Puede registrar a una <a href="reg_per_usaf.php">nueva persona</a></p>';
        echo '<p class="mensaje">O puede regresar al <a href="index.php">inicio</a></p>';

        $query = "SELECT 
                    U.id_persona,
                    U.delegacion, U.subdelegacion,
                    U.id_puesto, U.marca_encargo,
                    U.matricula, U.curp, U.nss,
                    U.nombre, U.primer_apellido, U.segundo_apellido, U.email,
                    U.id_user_creacion, 
                    U.fecha_creacion, DATE_FORMAT(U.fecha_creacion, '%d%M%y %H:%i') AS fCreacionFormato,
                    U.id_user_modificacion, 
                    U.fecha_modificacion, DATE_FORMAT(U.fecha_modificacion, '%d%M%y %H:%i') AS fModificacionFormato,
                    U.id_estatus,
                    CONCAT( D1.nombre, ' ', D1.primer_apellido) AS creada_por,
                    CONCAT( D2.nombre, ' ', D2.primer_apellido) AS modificada_por
                  FROM 
                    usaf_personas U, dspa_usuarios D1, dspa_usuarios D2
                  WHERE 
                    U.id_user_creacion = D1.id_user AND 
                    U.id_user_modificacion = D2.id_user AND ";

        $query = $query . "U.id_persona = '" . $row['LAST_INSERT_ID()'] . "'";
        $data = mysqli_query( $dbc, $query );

        if ( mysqli_num_rows( $data ) == 1 )
          // The user row was found so display the user data
          $rowB = mysqli_fetch_array($data);
        //Missing else...
        ?>
        <div class="contenedor">
          <form>
          <h2>Registro de Persona</h2>
          <ul>
            <li>
              <label for="cmbDelegaciones">Delegación IMSS</label>
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
              <label for="cmbPuesto">Puesto IMSS</label>
              <select disabled class="combo0" id="cmbPuesto" name="cmbPuesto">
                <?php
                  $query = "SELECT * 
                            FROM dspa_puestos 
                            WHERE id_puesto = " . $rowB['id_puesto'];
                  $result = mysqli_query( $dbc, $query );
                  while ( $row2 = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row2['id_puesto'] . '" selected>' . $row2['descripcion'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="chkMarcaEncargo">-</label>
              <input disabled type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value=
                <?php 
                  if (empty( $rowB['marca_encargo'] ))
                    echo '""'; 
                  elseif ( $rowB['marca_encargo'] == 1 )
                    echo '"1" checked';
                  else
                    echo '""'; 
                ?>
              />Es encargado temporal<br>
            </li>
            <li>
              <label for="matricula">Matrícula</label>
              <input disabled class="textinput" type="text" name="matricula" id="matricula" value='<?php if ( !empty( $rowB['matricula'] ) ) echo $rowB['matricula']; ?>'/>
            </li>
            <li>
              <label for="curp">CURP (Usuario)</label>
              <input disabled class="textinput" type="text" name="curp" id="curp" value="<?php if ( !empty( $rowB['curp'] ) ) echo $rowB['curp']; ?>" />
            </li>
            <li>
              <label for="matricula">NSS</label>
              <input disabled class="textinput" type="text" name="nss" id="nss" value='<?php if ( !empty( $rowB['nss'] ) ) echo $rowB['nss']; ?>'/>
            </li>
            <li>
              <label for="primer_apellido">Primer apellido</label>
              <input disabled class="textinput" type="text" name="primer_apellido" id="primer_apellido" value="<?php if ( !empty( $rowB['primer_apellido'] ) ) echo $rowB['primer_apellido']; ?>"/>
            </li>
            <li>
              <label for="segundo_apellido">Segundo apellido</label>
              <input disabled class="textinput" type="text" name="segundo_apellido" id="segundo_apellido" value="<?php if ( !empty( $rowB['segundo_apellido'] ) ) echo $rowB['segundo_apellido']; ?>"/>
            </li>
            <li>
              <label for="nombre">Nombre(s)</label>
              <input disabled class="textinput" type="text" name="nombre" id="nombre" value="<?php if ( !empty( $rowB['nombre'] ) ) echo $rowB['nombre']; ?>"/>
            </li>
            <li>
              <label for="email">Correo Electrónico</label>
              <input disabled class="textinput" type="email" name="email" id="email" value="<?php if ( !empty( $rowB['email'] ) ) echo $rowB['email']; ?>"/>
            </li>
            <li>
              <label for="creada_por">Creado por</label>
              <input disabled class="textinputsmall" type="text" name="creada_por" id="creada_por" value="<?php if ( !empty( $rowB['creada_por'] ) ) echo $rowB['creada_por']; ?>" />
            </li>
            <li>
              <label for="fCreacionFormato">Fecha de creación</label>
              <input disabled class="textinput" type="text" name="fCreacionFormato" id="fCreacionFormato" value="<?php if ( !empty( $rowB['fCreacionFormato'] ) ) echo $rowB['fCreacionFormato']; ?>"/>
            </li>
            <li>
              <label for="modificada_por">Modificada por última vez por</label>
              <input disabled class="textinputsmall" type="text" name="modificada_por" id="modificada_por" value="<?php if ( !empty( $rowB['modificada_por'] ) ) echo $rowB['modificada_por']; ?>" />
            </li>
            <li>
              <label for="fModificacionFormato">Fecha de modificación</label>
              <input disabled class="textinput" type="text" name="fModificacionFormato" id="fModificacionFormato" value="<?php if ( !empty( $rowB['fModificacionFormato'] ) ) echo $rowB['fModificacionFormato']; ?>"/>
            </li>
          </ul>
          </form>
        </div>

      <?php
      }
      else
        echo '<p class="error"><strong>El nuevo La nueva solicitud no ha podido generarse. Contactar al administrador.</strong></p>';

      // Clear the score data to clear the form
      $_POST['cmbDelegaciones'] = 0;
      $_POST['cmbSubdelegaciones'] = -1;
      $_POST['cmbPuesto'] = 0;
      $_POST['chkMarcaEncargo'] = "";
      $_POST['matricula'] = "";
      $_POST['curp'] = "";
      $_POST['nss'] = "";
      $_POST['primer_apellido'] = "";
      $_POST['segundo_apellido'] = "";
      $_POST['nombre'] = "";
      $_POST['email'] = "";
      
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
          <h2>Captura los datos de la Persona</h2>
          <ul>
            <li>
              <label for="cmbDelegaciones">Delegación IMSS</label>
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
              <label for="cmbPuesto">Puesto IMSS</label>
              <select class="combo0" id="cmbPuesto" name="cmbPuesto">
              <option value="-1">Seleccione Puesto</option>
                <?php
                  $query = "SELECT * 
                            FROM dspa_puestos
                            ORDER BY id_puesto";
                  $result = mysqli_query( $dbc, $query );
                  while ( $row = mysqli_fetch_array( $result ) )
                    echo '<option value="' . $row['id_puesto'] . '" ' . fntPuestoSelect( $row['id_puesto'] ) . '>' . $row['descripcion'] . '</option>';
                ?>
              </select>
            </li>
            <li>
              <label for="chkMarcaEncargo">-</label>
              <input type="checkbox" name="chkMarcaEncargo" id="chkMarcaEncargo" value='1'>Es encargado temporal<br>
            </li>
            <li>
              <label for="matricula">Matrícula</label>
              <input class="textinput" type="text" name="matricula" id="matricula" maxlength="15" placeholder="Escriba la matrícula" value='<?php if ( !empty( $matricula ) ) echo $matricula; ?>'/>
            </li>
            <li>
              <label for="curp">CURP (Usuario)</label>
              <input class="textinput" type="text" name="curp" id="curp" maxlength="20" placeholder="Escriba su CURP" value="<?php if ( !empty( $curp ) ) echo $curp; ?>" />
            </li>
            <li>
              <label for="matricula">NSS</label>
              <input class="textinput" type="text" name="nss" id="nss" maxlength="13" placeholder="Escriba su nss" value="<?php if ( !empty( $nss ) ) echo $nss; ?>" />
            </li>
            <li>
              <label for="primer_apellido">Primer apellido</label>
              <input class="textinput" type="text" required name="primer_apellido" id="primer_apellido" maxlength="32" placeholder="Escriba el primer apellido" value="<?php if ( !empty( $primer_apellido ) ) echo $primer_apellido; ?>"/>
            </li>
            <li>
              <label for="segundo_apellido">Segundo apellido</label>
              <input class="textinput" type="text" name="segundo_apellido" id="segundo_apellido" maxlength="32" placeholder="Escriba el segundo apellido" value="<?php if ( !empty( $segundo_apellido ) ) echo $segundo_apellido; ?>"/>
            </li>
            <li>
              <label for="nombre">Nombre(s)</label>
              <input class="textinput" type="text" required name="nombre" id="nombre" maxlength="32" placeholder="Escriba el nombre(s)" value="<?php if ( !empty( $nombre ) ) echo $nombre; ?>"/>
            </li>
            <li>
              <label for="email">Correo Electrónico</label>
              <input class="textinput" type="email" required name="email" id="email" maxlength="50" placeholder="Escriba el Correo Electrónico" value="<?php if ( !empty( $email ) ) echo $email; ?>"/>
            </li>
            <br/>
              <li class="buttons">
                <input type="submit" name="submit" value="Registrar Persona">
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
