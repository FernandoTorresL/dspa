<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Agregar Valija - Gestión Cuentas SINDO ';
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

    $num_oficio_ca      = mysqli_real_escape_string( $dbc, trim( $_POST['num_oficio_ca'] ) );
    $num_oficio_del     = mysqli_real_escape_string( $dbc, trim( $_POST['num_oficio_del'] ) );
    $fecha_recepcion_ca = mysqli_real_escape_string( $dbc, trim( $_POST['fecha_recepcion_ca'] ) );
    $fecha_solicitud_del= mysqli_real_escape_string( $dbc, trim( $_POST['fecha_solicitud_del'] ) );
    $cmbDelegaciones    = mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    $comentario         = mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    /*$timetime           = time();*/
    $new_file           = mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );
    $new_file_type      = $_FILES['new_file']['type'];
    $new_file_size      = $_FILES['new_file']['size']; 

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

            if ( empty( $num_oficio_ca ) ) {
              echo '<p class="error">Olvidaste capturar un Número de Área de Gestión</p>';
              $output_form = 'yes';
            }
            else {
              if ( !preg_match( '/^[1-9][0-9]*$/', $num_oficio_ca ) ) {
                echo '<p class="error">Número de Área de Gestión inválido.</p>';
                $output_form = 'yes';
              }
            }

            /*if ( !preg_match( '/^\d{9}$/', $fecha_recepcion_ca ) ) {*/
            if ( !preg_match( '/^[0-9]{9}$/', $fecha_recepcion_ca ) ) {
              $anio = substr( $fecha_recepcion_ca , 0, 4 );
              $mes  = substr( $fecha_recepcion_ca , 5, 2 );
              $dia  = substr( $fecha_recepcion_ca , 8, 2 );
              
              if ( !checkdate( $mes, $dia, $anio ) ) {
                echo '<p class="error">Fecha de Área de Gestión inválida.';
                echo 'Año:'  . $anio;
                echo ' Mes:'         . $mes;
                echo ' Día:'  . $dia  . '<br />';
                $output_form = 'yes';
              }

              if ( $anio < 2016 ) {
                echo '<p class="error">El año en Fecha de Área de Gestión es inválido.';
                echo ' Año:'  . $anio;
                $output_form = 'yes'; 
              }
            }

            if ( empty( $num_oficio_del ) ) {
              echo '<p class="error">Olvidaste capturar un Número de Oficio Delegación.</p>';
              $output_form = 'yes';
            }
            else {
              if ( !preg_match( '/^[a-z A-Z0-9\/\._\-]*$/', $num_oficio_del ) ) {
                echo '<p class="error">Caracteres inválidos en Número de Oficio Delegación.</p>';
                $output_form = 'yes';
              }
            }

            /*if ( !preg_match( '/^\d{9}$/', $fecha_solicitud_del ) ) {*/
            if ( !preg_match( '/^[0-9]{9}$/', $fecha_solicitud_del ) ) {
              $anio = substr( $fecha_solicitud_del, 0, 4 );
              $mes  = substr( $fecha_solicitud_del, 5, 2 );
              $dia  = substr( $fecha_solicitud_del, 8, 2 );
              
              if ( !checkdate( $mes, $dia, $anio ) ) {
                echo '<p class="error">Fecha de Oficio Delegación inválida.';
                echo ' Año:'  . $anio;
                echo ' Mes:'         . $mes;
                echo ' Día:'  . $dia  . '<br />';
                $output_form = 'yes';
              }

              if ( $anio < 2016 ) {
                echo '<p class="error">El año en Fecha de Oficio Delegación es inválido.';
                echo ' Año:'  . $anio;
                $output_form = 'yes'; 
              }
            }

            if ( empty( $cmbDelegaciones ) || 
                  ( $cmbDelegaciones == 0) || 
                  ( $cmbDelegaciones == -1 ) 
                ) {
              echo '<p class="error">Olvidaste seleccionar una Delegación.</p>';
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

                      //The number is unique, so insert the data
                      $query = "INSERT INTO ctas_valijas 
                        ( num_oficio_ca, num_oficio_del, 
                        fecha_recepcion_ca, fecha_captura_ca, fecha_solicitud_del, 
                        id_remitente, delegacion, comentario, archivo, user_id)
                        VALUES 
                        ( '$num_oficio_ca', '$num_oficio_del', 
                          '$fecha_recepcion_ca', NOW(), '$fecha_solicitud_del', 
                          0, '$cmbDelegaciones', '$comentario', '$timetime $new_file', " . $_SESSION['user_id'] . " )";
                      /*echo $query;*/
                      mysqli_query( $dbc, $query );

                      $query = "SELECT LAST_INSERT_ID()";
                      $result = mysqli_query( $dbc, $query );
                      $data = mysqli_query( $dbc, $query );

                      if ( mysqli_num_rows( $data ) == 1 ) {
                        // The user row was found so display the user data
                        $row = mysqli_fetch_array($data);
                        echo '<p class="nota"><strong>¡La nueva valija ha sido creada correctamente!</strong></p>';
                        echo '<p class="titulo2">¿Hubo un error? Puedes EDITAR la <a href="editarvalija.php?valija_id=' . $row['LAST_INSERT_ID()'] . '">valija</a></p>';
                        echo '<p class="titulo2">Puede agregar una <a href="agregarvalija.php">nueva valija</a></p>';
                        /*echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';*/
                        echo '<p>O puede regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';

                        $query = "SELECT ctas_valijas.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.num_oficio_del,
                                    ctas_valijas.fecha_recepcion_ca, ctas_valijas.fecha_captura_ca, ctas_valijas.fecha_solicitud_del,
                                    ctas_valijas.id_remitente, ctas_valijas.delegacion, ctas_valijas.comentario, ctas_valijas.archivos, 
                                    CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creada_por
                                  FROM ctas_valijas, ctas_usuarios
                                  WHERE ctas_valijas.user_id = ctas_usuarios.user_id ";

                        $query = $query . "AND ctas_valijas.id_valija = '" . $row['LAST_INSERT_ID()'] . "'";
                        $data = mysqli_query( $dbc, $query );

                        if ( mysqli_num_rows( $data ) == 1 ) {
                          // The user row was found so display the user data
                          $rowB = mysqli_fetch_array($data);
                         }

                        ?>





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
                        $_POST['num_oficio_ca']    = "";
                        $_POST['fecha_recepcion_ca'] = "";
                        $_POST['num_oficio_del'] = "";
                        $_POST['fecha_solicitud_del'] = "";
                        $_POST['cmbDelegaciones'] = 0;
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
                echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar la valija.</p>';
              }

            }
            else {
              echo '<p class="nota"><strong>Captura todos los datos de la valija.</strong></p>';
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

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" class="active validate" name="num_oficio_ca" id="num_oficio_ca" length="32" value="<?php if ( !empty( $num_oficio_ca ) ) echo $num_oficio_ca; ?>"/>
                  <label data-error="Error" for="num_oficio_ca"># del Área de Gestión</label>
                </div>

                <label for="fecha_recepcion_ca">Fecha recepcion CA:</label>
                <div class="input-field">
                  <i class="material-icons prefix">today</i>
                  <input type="date" id="fecha_recepcion_ca" name="fecha_recepcion_ca" value="<?php if ( !empty( $fecha_recepcion_ca ) ) echo $fecha_recepcion_ca; ?>"/>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input  type="text" class="active validate" name="num_oficio_del" id="num_oficio_del" length="32" value="<?php if ( !empty( $num_oficio_del ) ) echo $num_oficio_del; ?>"/>
                  <label data-error="Error" for="num_oficio_del"># del Oficio de la Delegación</label>
                </div>

                <label for="fecha_solicitud_del">Fecha del Oficio de la Delegación:</label>
                <div class="input-field">
                  <i class="material-icons prefix">today</i>
                  <input type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if ( !empty( $fecha_solicitud_del ) ) echo $fecha_solicitud_del; ?>"/>
                </div>

                <div class="input-field">
                  <i class="large material-icons prefix">business</i>
                  <select id="cmbDelegaciones" name="cmbDelegaciones" >
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

              </div>
            </div>
          </div>

          <div class="col s5">
            <div class="signup-box">
              <div class="container">
                    
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
                  <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Registra Valija<i class="material-icons right">send</i>
                  </button>
                </div>

              </div>
            </div>
          </div>

        </form>
    <?php
      }
    ?>

    </div>
  </section>

  <?php
    //mysqli_close( $dbc );
    // Insert the page footer
    require_once('footer.php');
  ?>
