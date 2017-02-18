<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Gestión Cuentas SINDO - Capturar Solicitud';
  require_once( 'header.php' );
  
  // Show the navigation menu
  require_once( 'navmenu.php' );
  require_once( 'funciones.php');

  $error_msg = "";
  $output_form = 'yes';

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    require_once('footer.php');
    exit();
  }

  // Connect to the database
  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( mysqli_connect_errno () ) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    return "Falló la conexión a base de datos";
    require_once('footer.php');
    exit(); 
  }

  if ( isset( $_POST['submit'] ) ) {

    $error_msg = fnConnect( $dbc );

    /*$cmbLotes =             mysqli_real_escape_string( $dbc, trim( $_POST['cmbLotes'] ) );*/
    $cmbLotes = 0;
    $cmbValijas =           mysqli_real_escape_string( $dbc, trim( $_POST['cmbValijas'] ) );
    $fecha_solicitud_del =  mysqli_real_escape_string( $dbc, trim( $_POST['fecha_solicitud_del'] ) );
    $cmbtipomovimiento =    mysqli_real_escape_string( $dbc, trim( $_POST['cmbtipomovimiento'] ) );
    $cmbDelegaciones =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    $cmbSubdelegaciones =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegaciones'] ) );
    $primer_apellido =      mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['primer_apellido'] ) ) );
    $segundo_apellido =     mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['segundo_apellido'] ) ) );
    $nombre =               mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['nombre'] ) ) );
    $matricula =            mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['matricula'] ) ) );
    $curp =                 mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['curp'] ) ) );
    $usuario =              mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['usuario'] ) ) );
    $cmbgponuevo =          mysqli_real_escape_string( $dbc, trim( $_POST['cmbgponuevo'] ) );
    $cmbgpoactual =         mysqli_real_escape_string( $dbc, trim( $_POST['cmbgpoactual'] ) );
    $cmbcausarechazo =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbcausarechazo'] ) );
    $comentario =           mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    $new_file =             mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size'];

    $output_form = 'no';
  }

?>

  <section id="main-container">
    <div class="row">

      <div class="col s2">
        <div class="signup-box">
          <div class="container">
        
          <?php

          if ( isset( $_POST['submit'] ) ) {

            if ( empty( $cmbValijas ) ) {
              echo '<p class="error">Olvidaste seleccionar una Valija.</p>';
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
                echo ' Día:'  . $dia  . '<br />';
                $output_form = 'yes';
              }
            }

            if ( empty( $cmbtipomovimiento ) ) {
              echo '<p class="error">Olvidaste seleccionar un Tipo de Movimiento.</p>';
              $output_form = 'yes';
            }

            if ( empty( $cmbDelegaciones ) || 
                    ( $cmbDelegaciones == 0 ) || 
                    ( $cmbDelegaciones == -1 ) 
                  ) {
              echo '<p class="error">Olvidaste seleccionar una Delegación.</p>';
              $output_form = 'yes';
            }

            if ( ( empty( $cmbSubdelegaciones ) || $cmbSubdelegaciones == -1 ) && $cmbSubdelegaciones <> 0 )  {
              echo '<p class="error">Olvidaste seleccionar una Subdelegación.</p>';
              $output_form = 'yes';
            }

            if ( empty( $primer_apellido ) ) {
              echo '<p class="error">Olvidaste capturar el Primer Apellido.</p>';
              $output_form = 'yes';
            }

            if ( empty( $nombre ) ) {
              echo '<p class="error">Olvidaste capturar el Nombre.</p>';
              $output_form = 'yes';
            }

            // BAJA
            if ( $cmbtipomovimiento == 2 ) { 
              if ( empty( $cmbgpoactual ) || ( $cmbgpoactual == 0 ) ) {
              echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de BAJA.</p>';
              $output_form = 'yes';
              }
            }

            //Si el tipo de movimiento es diferente a BAJA, no se permiten Matrícula y CURP nulas.
            if ( ( $cmbtipomovimiento <> 2 ) && ( empty( $matricula ) ) ) {
              echo '<p class="error">Olvidaste capturar la Matrícula.</p>';
              $output_form = 'yes';
            }

            if ( ( $cmbtipomovimiento <> 2 ) && ( empty( $curp ) ) ) {
              echo '<p class="error">Olvidaste capturar la CURP.</p>';
              $output_form = 'yes';
            }

            if ( empty( $usuario ) ) {
              echo '<p class="error">Olvidaste capturar Usuario.</p>';
              $output_form = 'yes';
            }

            // ALTA
            if ( $cmbtipomovimiento == 1 ) {
              if ( empty( $cmbgponuevo ) || ( $cmbgponuevo == 0 ) ) {
              echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de ALTA.</p>';
              $output_form = 'yes';
              }
            }

            // CAMBIO
            if ( $cmbtipomovimiento == 3 ) {
              if ( empty( $cmbgpoactual ) || ( $cmbgpoactual == 0 ) ) {
                echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de CAMBIO.</p>';
                $output_form = 'yes';
              }
            }

            if ( $cmbtipomovimiento == 3 ) {
              if ( empty( $cmbgponuevo ) || ( $cmbgponuevo == 0 ) ) {
                echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de CAMBIO.</p>';
                $output_form = 'yes';
              }
            }

            if ( ( empty( $cmbcausarechazo ) || $cmbcausarechazo  == -1 ) && $cmbcausarechazo <> 0 )  {
              echo '<p class="error">Olvidaste capturar Causa de Rechazo</p>';
              $output_form = 'yes';
            }

            if ( empty( $new_file ) ) {
              echo '<p class="error">Olvidaste adjuntar un Archivo.</p>';
              $output_form = 'yes';
            }

            if ( $output_form == 'no' ) {

              // Validate and move the uploaded picture file, if necessary
              if ( !empty( $new_file ) ) {

                if ( ( ( $new_file_type == 'application/pdf' ) || ( $new_file_type == 'image/gif' ) || ( $new_file_type == 'image/jpeg' ) || ( $new_file_type == 'image/pjpeg' ) || ( $new_file_type == 'image/png' ) ) && ( ( $new_file_size > 0 ) && ( $new_file_size <= MM_MAXFILESIZE_VALIJA ) ) ) {
                  if ( $_FILES['new_file']['error'] == 0 ) {
                    $timetime = time();
                    //Move the file to the target upload folder
                    $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . basename( $new_file );

                      // The new file file move was successful, now make sure any old file is deleted
                    if ( move_uploaded_file( $_FILES['new_file']['tmp_name'], $target ) ) {
                      // Conectarse a la BD
                      $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
                      $query = "INSERT INTO ctas_solicitudes 
                                  ( id_valija, id_lote, 
                                    fecha_solicitud_del, 
                                    delegacion, subdelegacion, 
                                    nombre, primer_apellido, segundo_apellido, matricula, curp, 
                                    usuario, id_movimiento, id_grupo_nuevo, id_grupo_actual,
                                    comentario, id_causarechazo, archivo, user_id )
                                VALUES 
                                ( '$cmbValijas', '$cmbLotes',
                                  '$fecha_solicitud_del',
                                  '$cmbDelegaciones', '$cmbSubdelegaciones', 
                                  '$nombre', '$primer_apellido', '$segundo_apellido', 
                                  '$matricula', '$curp',
                                  '$usuario', '$cmbtipomovimiento', '$cmbgponuevo', '$cmbgpoactual',
                                  '$comentario', $cmbcausarechazo, '$timetime $new_file', " . $_SESSION['user_id'] . " )";
                      /*echo $query;*/
                      mysqli_query( $dbc, $query );

                      $query = "SELECT LAST_INSERT_ID()";
                      $result = mysqli_query( $dbc, $query );
                      $data = mysqli_query( $dbc, $query );

                      if ( mysqli_num_rows( $data ) == 1 ) {
                        // The user row was found so display the user data
                        $row = mysqli_fetch_array($data);
                        echo '<p class="nota"><strong>¡La nueva solicitud ha sido creada correctamente!</strong></p>';
                        echo '<p class="titulo2">¿Hubo un error? Puede EDITAR la <a href="editarsolicitud.php?id_solicitud=' . $row['LAST_INSERT_ID()'] . '">solicitud</a></p>';
                        echo '<p class="titulo2">Puede agregar una <a href="agregarsolicitud.php">nueva solicitud</a></p>';
                        /*echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';*/
                        echo '<p>O puede regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';

                        $query = "SELECT ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, 
                              ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion, ctas_solicitudes.id_lote,
                              ctas_solicitudes.delegacion, ctas_solicitudes.subdelegacion, 
                              ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
                              ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
                              ctas_solicitudes.id_movimiento, ctas_solicitudes.id_grupo_actual, ctas_solicitudes.id_grupo_nuevo, 
                              ctas_solicitudes.comentario, ctas_solicitudes.id_causarechazo, ctas_solicitudes.archivo,
                              CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creada_por
                            FROM ctas_solicitudes, ctas_grupos grupos1, ctas_grupos grupos2, ctas_usuarios
                            WHERE ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
                            AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
                            AND   ctas_solicitudes.user_id = ctas_usuarios.user_id ";

                        $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $row['LAST_INSERT_ID()'] . "'";
                        $data = mysqli_query( $dbc, $query );

                        if ( mysqli_num_rows( $data ) == 1 ) {
                          // The user row was found so display the user data
                          $rowB = mysqli_fetch_array($data);
                        }

                        ?>
                            </div>
                          </div>
                        </div>

                        <div class="col s5">
                          <div class="signup-box">
                            <div class="container">

                              <div class="input-field">
                                <i class="material-icons prefix">description</i>
                                <select id="cmbValijas" name="cmbValijas" disabled>
                                  <?php
                                    $query = "SELECT ctas_valijas.id_valija AS id_valija2, 
                                                ctas_valijas.delegacion AS num_del, 
                                                ctas_delegaciones.descripcion AS delegacion_descripcion, 
                                                ctas_valijas.num_oficio_del,
                                                ctas_valijas.num_oficio_ca, 
                                                ctas_valijas.user_id
                                              FROM ctas_valijas, ctas_delegaciones 
                                              WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion 
                                              AND ctas_valijas.id_valija = " . $rowB['id_valija'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['id_valija2'] . '" selected>' . $row2['num_oficio_ca'] . ': ' . $row2['num_del'] . '-' . $row2['delegacion_descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label>Número de Valija/Oficio</label>
                              </div>

                              <label for="fecha_solicitud_del">Fecha solicitud:</label>
                              <div class="input-field">
                                <i class="material-icons prefix">today</i>
                                <input disabled type="text" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if ( !empty( $rowB['fecha_solicitud_del'] ) ) echo $rowB['fecha_solicitud_del']; ?>"/>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">view_list</i>
                                <select disabled id="cmbtipomovimiento" name="cmbtipomovimiento">
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_movimientos
                                              WHERE id_movimiento = " . $rowB['id_movimiento'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['id_movimiento'] . '" ' . fntipomovimientoSelect( $row2['id_movimiento'] ) . '>' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label for="cmbtipomovimiento">Tipo de Movimiento</label>
                              </div>

                              <div class="input-field">
                                <i class="large material-icons prefix">business</i>
                                <select disabled id="cmbDelegaciones" name="cmbDelegaciones" >
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_delegaciones 
                                              WHERE delegacion = " . $rowB['delegacion'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['delegacion'] . '" selected>' . $row2['delegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label>Delegación IMSS</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">store</i>
                                <select disabled class="active validate" id="cmbSubdelegaciones" name="cmbSubdelegaciones" >
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_subdelegaciones 
                                              WHERE delegacion = " . $rowB['delegacion'] . " AND subdelegacion = " . $rowB['subdelegacion'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['subdelegacion'] . '" selected>' . $row2['subdelegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">perm_identity</i>
                                <input disabled type="text" class="active validate" name="primer_apellido" id="primer_apellido" length="32" value="<?php if ( !empty( $rowB['primer_apellido'] ) ) echo $rowB['primer_apellido']; ?>"/>
                                <label data-error="Error" for="primer_apellido">Primer apellido</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">perm_identity</i>
                                <input disabled type="text" class="active validate" name="segundo_apellido" id="segundo_apellido" length="32" value="<?php if ( !empty( $rowB['segundo_apellido'] ) ) echo $rowB['segundo_apellido']; ?>"/>
                                <label data-error="Error" for="segundo_apellido">Segundo apellido</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">perm_identity</i>
                                <input disabled type="text" required class="active validate" name="nombre" id="nombre" length="32" value="<?php if ( !empty( $rowB['nombre'] ) ) echo $rowB['nombre']; ?>"/>
                                <label data-error="Error" for="nombre">Nombre(s)</label>
                              </div>


                            </div>
                          </div>
                        </div>

                        <div class="col s5">
                          <div class="signup-box">
                            <div class="container">

                              <div class="input-field">
                                <i class="material-icons prefix">assignment_ind</i>
                                <input disabled type="text" required class="active validate" name="matricula" id="matricula" length="32" value='<?php if ( !empty( $rowB['matricula'] ) ) echo $rowB['matricula']; ?>'/>
                                <label data-error="Error" for="matricula">Matrícula</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">account_circle</i>
                                <input disabled type="text" required class="active validate" name="curp" id="curp" length="18" value="<?php if ( !empty( $rowB['curp'] ) ) echo $rowB['curp']; ?>" />
                                <label data-error="Error" for="curp">CURP</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">assignment</i>
                                <input disabled type="text" required class="active validate" name="usuario" id="usuario" length="7" value="<?php if ( !empty( $rowB['usuario'] ) ) echo $rowB['usuario']; ?>" />
                                <label data-error="Error" for="usuario">Usuario</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">label_outline</i>
                                <select disabled id="cmbgpoactual" class="active validate" name="cmbgpoactual" >
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_grupos 
                                              WHERE id_grupo = " . $rowB['id_grupo_actual'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label>Grupo Actual</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">label</i>
                                <select disabled id="cmbgponuevo" name="cmbgponuevo" >
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_grupos 
                                              WHERE id_grupo = " . $rowB['id_grupo_nuevo'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label>Grupo Nuevo</label>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">report_problem</i>
                                <select disabled id="cmbcausarechazo" name="cmbcausarechazo" >
                                  <?php
                                    $query = "SELECT * 
                                              FROM ctas_causasrechazo
                                              WHERE id_causarechazo = " . $rowB['id_causarechazo'];
                                    $result = mysqli_query( $dbc, $query );
                                    while ( $row2 = mysqli_fetch_array( $result ) )
                                      echo '<option value="' . $row2['id_causarechazo'] . '" selected>' . $row2['id_causarechazo'] . ' - ' . $row2['descripcion'] . '</option>';
                                  ?>
                                </select>
                                <label>Causa de Rechazo</label>
                              </div>
                                  
                              <div class="input-field">
                                <i class="material-icons prefix">comment</i>
                                <textarea disabled class="materialize-textarea" class="validate" id="comentario" length="256" name="comentario"><?php if ( !empty( $rowB['comentario'] ) ) echo $rowB['comentario']; ?></textarea>
                                <label data-error="Error" for="comentario">Comentario</label>
                              </div>

                              <div>
                                <i class="material-icons prefix">description</i>
                                <label data-error="Error" for="usuario">Archivo</label>
                                <div class="section" align="right">
                                  <?php 
                                    if ( !empty( $rowB['archivo'] ) ) 
                                      echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $rowB['archivo'] . '"  target="_new">' . $rowB['archivo'] . '</a>';
                                    else echo '(Vacío)';
                                  ?>
                                </div>
                              </div>

                              <div class="input-field">
                                <i class="material-icons prefix">contact</i>
                                <input disabled type="text" required class="active validate" name="user_id" id="user_id" length="50" value="<?php if ( !empty( $rowB['creada_por'] ) ) echo $rowB['creada_por']; ?>" />
                                <label data-error="Error" for="user_id">Capturada por:</label>
                              </div>

                              <label for="fecha_modificacion">Fecha Modificación:</label>
                              <div class="input-field">
                                <i class="material-icons prefix">today</i>
                                <input disabled type="text" id="fecha_modificacion" name="fecha_modificacion" value="<?php if ( !empty( $rowB['fecha_modificacion'] ) ) echo $rowB['fecha_modificacion']; ?>"/>
                              </div>

                            </div>
                          </div>
                        </div>

                      <?php
                      }
                      else {
                        echo '<p class="error"><strong>La nueva solicitud no ha podido generarse. Contactar al administrador.</strong></p>';
                      }

                      // Clear the score data to clear the form
                      $_POST['cmbLote']    = 0;
                      $_POST['cmbValijas'] = 0;
                      $_POST['fecha_solicitud_del'] = "";
                      $_POST['cmbtipomovimiento'] = 0;
                      $_POST['cmbDelegaciones'] = 0;
                      $_POST['cmbSubdelegaciones'] = -1;
                      $_POST['nombre'] = "";
                      $_POST['primer_apellido'] = "";
                      $_POST['segundo_apellido'] = "";
                      $_POST['matricula'] = "";
                      $_POST['curp'] = "";
                      $_POST['cargo'] = "";
                      $_POST['usuario'] = "";
                      $_POST['cmbgpoactual'] = 0;
                      $_POST['cmbgponuevo'] = 0;
                      $_POST['cmbcausarechazo'] = -1;
                      $_POST['comentario'] = "";
                      $_POST['new_file'] = "";

                      mysqli_close( $dbc );
                    }
                    else {
                      // The new picture file move failed, so delete the temporary file and set the error flag
                      @unlink( $_FILES['new_file']['tmp_name'] );
                      $error = true;
                      echo '<p class="error">Lo sentimos, hubo un problema al cargar tu archivo.</p>';
                    } // if ( move_uploaded_file(...

                  } // if ( $_FILES['new_file']['error'] == 0 )...

                }
                else {
                // The new picture file is not valid, so delete the temporary file and set the error flag
                  @unlink( $_FILES['new_file']['tmp_name'] );
                  $error = true;
                  echo '<p class="error">El archivo debe ser PDF, GIF, JPEG o PNG no mayor de '. ( MM_MAXFILESIZE_VALIJA / 1024 ) . ' KB de tamaño.</p>';
                } // if ( ( ( $new_file_type == 'application/pdf' )...

              } //FIN de "if (isset($_POST['submit']))"
              else {
                $output_form = 'yes';
              }
            }
            else {
              echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar la solicitud.</p>';
            }

          }
          else {
            echo '<p class="nota"><strong>Captura todos los datos de la solicitud.</strong></p>';
          }

          ?>
            
          </div>
        </div>
      </div>


    <?php
      if ( $output_form == 'yes' ) {
    ?>
        <form class="signup-form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        
          <div class="col s5">
            <div class="signup-box">
              <div class="container">

        
                  <i class="material-icons prefix">description</i>
                  <select id="cmbValijas" name="cmbValijas">
                    <option value="0">Seleccione # de Valija/Oficio</option>
                    <?php
                      $query = "SELECT ctas_valijas.id_valija, 
                                  ctas_valijas.delegacion AS num_del, 
                                  ctas_delegaciones.descripcion AS delegacion_descripcion, 
                                  ctas_valijas.num_oficio_del,
                                  ctas_valijas.num_oficio_ca, 
                                  ctas_valijas.user_id
                                FROM ctas_valijas, ctas_delegaciones 
                                WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion
                                AND   ( YEAR(ctas_valijas.fecha_recepcion_ca) = 2017 OR YEAR(ctas_valijas.fecha_recepcion_ca) = 2016 ) 
                                ORDER BY ctas_valijas.fecha_recepcion_ca DESC, ctas_valijas.id_valija";
                                //AND   YEAR(ctas_valijas.fecha_recepcion_ca) = 2017 
                      $result = mysqli_query( $dbc, $query );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['id_valija'] . '" ' . fnvalijaSelect( $row['id_valija'] ) . '>' . $row['num_oficio_ca'] . ': ' . $row['num_del'] . '-' . $row['delegacion_descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Número de Valija/Oficio</label>
                <!-- </div> -->

                <label for="fecha_solicitud_del">Fecha solicitud:</label>
                <div class="input-field">
                  <i class="material-icons prefix">today</i>
                  <input type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if (!empty($fecha_solicitud_del)) echo $fecha_solicitud_del; ?>" /><br />
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">view_list</i>
                    <select id="cmbtipomovimiento" name="cmbtipomovimiento">
                      <option value="0">Seleccione Tipo de Movimiento</option>
                      <?php
                        $result = mysqli_query( $dbc, "SELECT * 
                                                        FROM ctas_movimientos 
                                                        ORDER BY 1 ASC" );
                        while ( $row = mysqli_fetch_array( $result ) )
                          echo '<option value="' . $row['id_movimiento'] . '" ' . fntipomovimientoSelect( $row['id_movimiento'] ) . '>' . $row['descripcion'] . '</option>';
                      ?>
                    </select>
                    <label for="cmbtipomovimiento">Tipo de Movimiento</label>
                </div>

                <div class="input-field">
                  <i class="large material-icons prefix">business</i>
                  <select id="cmbDelegaciones" name="cmbDelegaciones">
                    <option value="0">Seleccione Delegación</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_delegaciones 
                                                      WHERE activo = 1 
                                                      ORDER BY delegacion" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Delegación IMSS</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">store</i>
                  <select class="active validate" id="cmbSubdelegaciones" name="cmbSubdelegaciones">
                    <option value="-1" >Seleccione Subdelegación</option>
                    <?php
                      if ( !empty( $_POST['cmbSubdelegaciones'] ) || $_POST['cmbSubdelegaciones'] == "0" ) 
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_subdelegaciones 
                                                      WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label data-error="Error" for="cmbSubdelegaciones">Subdelegación IMSS</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" required class="active validate" name="primer_apellido" id="primer_apellido" length="32" value="<?php if ( !empty( $primer_apellido ) ) echo $primer_apellido; ?>"/>
                  <label data-error="Error" for="primer_apellido">Primer apellido</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" class="active validate" name="segundo_apellido" id="segundo_apellido" length="32" value="<?php if ( !empty( $segundo_apellido ) ) echo $segundo_apellido; ?>"/>
                  <label data-error="Error" for="segundo_apellido">Segundo apellido</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" required class="active validate" name="nombre" id="nombre" length="32" value="<?php if ( !empty( $nombre ) ) echo $nombre; ?>"/>
                  <label data-error="Error" for="nombre">Nombre(s)</label>
                </div>


              </div>
            </div>
          </div>

          <div class="col s5">
            <div class="signup-box">
              <div class="container">

                <div class="input-field">
                  <i class="material-icons prefix">assignment_ind</i>
                  <input type="text" required class="active validate" name="matricula" id="matricula" length="32" value='<?php if ( !empty( $matricula ) ) echo $matricula; ?>'/>
                  <label data-error="Error" for="matricula">Matrícula</label>
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input type="text" required class="active validate" name="curp" id="curp" length="18" value="<?php if ( !empty( $curp ) ) echo $curp; ?>" />
                    <label data-error="Error" for="curp">CURP</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">assignment</i>
                  <input type="text" required class="active validate" name="usuario" id="usuario" length="7" value="<?php if ( !empty( $usuario ) ) echo $usuario; ?>" />
                  <label data-error="Error" for="usuario">Usuario</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">label_outline</i>
                  <select id="cmbgpoactual" class="active validate" name="cmbgpoactual">
                    <option value="0">Seleccione Grupo Actual</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_grupos 
                                                      WHERE id_grupo <> 0
                                                      ORDER BY descripcion ASC" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgpoactualSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Grupo Actual</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">label</i>
                  <select id="cmbgponuevo" name="cmbgponuevo">
                    <option value="0">Seleccione Grupo Nuevo</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_grupos 
                                                      WHERE id_grupo <> 0
                                                      ORDER BY descripcion ASC" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgponuevoSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Grupo Nuevo</label>
                </div>


                <div class="input-field">
                  <i class="material-icons prefix">report_problem</i>
                  <select id="cmbcausarechazo" name="cmbcausarechazo">
                    <option value="-1">Seleccione Causa de Rechazo</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_causasrechazo
                                                      WHERE id_causarechazo <> -1
                                                      ORDER BY id_causarechazo ASC" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['id_causarechazo'] . '" ' . fntcmbcausarechazoSelect( $row['id_causarechazo'] ) .'>' . $row['id_causarechazo'] . ' - ' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Causa de Rechazo</label>

                <!-- <?php
                /*  echo $row['id_causarechazo'];
                  echo fntcmbcausarechazoSelect( $row['id_causarechazo'] );*/
                ?> -->
                </div>
                    
                <div class="input-field">
                  <i class="material-icons prefix">comment</i>
                  <textarea class="materialize-textarea" class="validate" id="comentario" length="256" name="comentario"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
                  <label data-error="Error" for="comentario">Comentario</label>
                </div>

                <div class="input-field">
                  <div class="file-field input-field">
                    <div class="btn">
                      <span>Archivo</span>
                      <input type="file" id="new_file" name="new_file">
                    </div>
                    <div class="file-path-wrapper">
                      <input class="file-path validate" type="text">
                    </div>
                  </div>
                </div>

                <div class="section" align="center">
                  <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Registra Solicitud<i class="material-icons right">send</i>
                  </button>
                </div>

              </div>
            </div>
          </div>

        </form>
    <?php
      }
    ?>
<!--       else {

      } -->

    </div>
  </section>

  <?php
    //mysqli_close( $dbc );
    // Insert the page footer
    require_once('footer.php');
  ?>
