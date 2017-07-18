<?php

    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectBD.php');

    require_once('commonfiles/funciones.php');

    // Insert the page header
    $page_title = 'Agregar Valija - Gestión Cuentas SINDO ';
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
    $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-EditarSolicitud' );
    if ( !$ResultadoConexion ) {
      // Hubo un error en la conexión a la base de datos;
      printf( " Connect failed: %s", mysqli_connect_error() );
      require_once('lib/footer.php');
      exit();
    }

    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    $query = "SELECT id_user 
              FROM dspa_permisos
              WHERE id_modulo = 13
              AND   id_user   = " . $_SESSION['id_user'];
    /*echo $query;*/
    $data = mysqli_query($dbc, $query);

    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para éste módulo
    }
    else {
      echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
      require_once('lib/footer.php');
      $log = fnGuardaBitacora( 5, 105, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
      exit(); 
    }

    if ( isset( $_POST['submit'] ) ) {

      /*$error_msg = fnConnect( $dbc );*/
      $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

      $id_valija =         $_POST['id_valija'];
      $num_oficio_ca      = mysqli_real_escape_string( $dbc, trim( $_POST['num_oficio_ca'] ) );
      $fecha_recepcion_ca = mysqli_real_escape_string( $dbc, trim( $_POST['fecha_recepcion_ca'] ) );
      $num_oficio_del     = mysqli_real_escape_string( $dbc, trim( $_POST['num_oficio_del'] ) );
      $fecha_valija_del   = mysqli_real_escape_string( $dbc, trim( $_POST['fecha_valija_del'] ) );
      
      $cmbDelegaciones    = mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
      $comentario         = mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
      
      $new_file           = mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );

      $Actualizar_new_file = true;

      if ( $new_file == '' ) {
        /*  echo 'Sin archivo nuevo, usemos el mismo';*/
        $Actualizar_new_file = false;
      }
      
      $new_file_type = $_FILES['new_file']['type'];
      $new_file_size = $_FILES['new_file']['size'];

      $output_form = 'no';
    }

  ?>
         
            <?php

            if ( isset( $_POST['submit'] ) ) {

              if ( empty( $id_valija ) ) {
                echo '<p class="error">No hay ID_VALIJA</p>';
                $output_form = 'yes';
              }

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
                  echo '<p class="error">Fecha de Área de Gestión inválida. ';
                  echo 'Año:'  . $anio;
                  echo ' Mes:'         . $mes;
                  echo ' Día:'  . $dia  . '</p>';
                  $output_form = 'yes';
                }
                else {
                  if ( $anio < 2017 ) {
                    echo '<p class="advertencia">El año en Fecha de Área de Gestión no es el actual. ';
                    echo ' Año:'  . $anio . '  ¿Es correcto?</p>';
                    /*$output_form = 'yes'; */
                  }
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

              /*if ( !preg_match( '/^\d{9}$/', $fecha_valija_del ) ) {*/
              if ( !preg_match( '/^[0-9]{9}$/', $fecha_valija_del ) ) {
                $anio = substr( $fecha_valija_del, 0, 4 );
                $mes  = substr( $fecha_valija_del, 5, 2 );
                $dia  = substr( $fecha_valija_del, 8, 2 );
                
                if ( !checkdate( $mes, $dia, $anio ) ) {
                  echo '<p class="error">Fecha de Oficio Delegación inválida.';
                  echo ' Año:'  . $anio;
                  echo ' Mes:'         . $mes;
                  echo ' Día:'  . $dia  . '</p>';
                  $output_form = 'yes';
                }
                else {
                  if ( $anio < 2017 ) {
                    echo '<p class="advertencia">El año en Fecha de Oficio Delegación no es el actual. ';
                    echo ' Año:'  . $anio . '  ¿Es correcto?</p>';
                    /*$output_form = 'yes'; */
                  }
                }
              }

              if ( empty( $cmbDelegaciones ) || 
                      ( $cmbDelegaciones == 0 ) || 
                      ( $cmbDelegaciones == -1 ) 
                    ) {
                echo '<p class="error">Olvidaste seleccionar una Delegación.</p>';
                $output_form = 'yes';
              }

              
              if ( $output_form == 'no' ) {

                $error = false;

                //Si el archivo no es nuevo, no tiene caso realizar las siguientes validaciones
                if ( $Actualizar_new_file ) {

                  // Validate and move the uploaded picture file, if necessary
                  if ( !empty( $new_file ) ) {

                    if ( ( ( $new_file_type == 'application/pdf' ) || ( $new_file_type == 'image/gif' ) || ( $new_file_type == 'image/jpeg' ) || ( $new_file_type == 'image/pjpeg' ) || ( $new_file_type == 'image/png' ) ) && ( ( $new_file_size > 0 ) && ( $new_file_size <= MM_MAXFILESIZE_VALIJA ) ) ) {
                      if ( $_FILES['new_file']['error'] == 0 ) {
                        $timetime = time();
                        //Move the file to the target upload folder
                        $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . basename( $new_file );

                          // The new file file move was successful, now make sure any old file is deleted
                        if ( move_uploaded_file( $_FILES['new_file']['tmp_name'], $target ) ) {

                          $error = false; //No hay error, podremos hacer UPDATE (línea 257) y mostrar los resultados
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

                  } // ELSE de "if ( !empty( $new_file ) )"
                  else {
                    $output_form = 'yes';
                  }

                } // END IF de "if ( $Actualizar_new_file )"

                if ( !$error ) { //Si no hay error, hacemos el UPDATE y mostramos el registro modificado:

                  // Conectarse a la BD
                  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

                  $query = "INSERT INTO ctas_hist_valijas 
                              (id_valija, num_oficio_ca, num_oficio_del, 
                              fecha_recepcion_ca, fecha_captura_ca,  fecha_valija_del, 
                              id_remitente, delegacion, 
                              comentario, archivo, id_user) 
                              SELECT 
                                id_valija, num_oficio_ca, num_oficio_del, 
                                fecha_recepcion_ca, fecha_captura_ca, fecha_valija_del, 
                                id_remitente, delegacion, 
                                comentario, archivo, id_user 
                              FROM ctas_valijas 
                              WHERE id_valija = " . $id_valija . " LIMIT 1";

                  /*echo $query;*/
                  mysqli_query( $dbc, $query );

                  $log = fnGuardaBitacora( 1, 115, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_valija:' . $id_valija . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

                  $query = "UPDATE ctas_valijas
                          SET num_oficio_ca       = '$num_oficio_ca',
                              num_oficio_del      = '$num_oficio_del',
                              fecha_recepcion_ca  = '$fecha_recepcion_ca',
                              fecha_captura_ca    = NOW(),
                              fecha_valija_del    = '$fecha_valija_del',
                              delegacion          = '$cmbDelegaciones',
                              comentario          = '$comentario',";

                  if ( $Actualizar_new_file ) { //...si hay nuevo archivo, sí hay que agregar campo "archivo"
                    $query = $query . "archivo = '$timetime $new_file',";
                  }

                  $query = $query . " id_user = " . $_SESSION['id_user'] . " WHERE 
                                    id_valija = '" . $id_valija . "' LIMIT 1";

                  /*echo $query;*/
                  mysqli_query( $dbc, $query );

                  $id_valija_bitacora = $id_valija;
                  $log = fnGuardaBitacora( 2, 105, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_valija:' . $id_valija_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

                  echo '<p class="mensaje"><strong>¡La valija ha sido actualizada!</strong></p>';

                  echo '<p class="mensaje">¿Deseas volver a <a href="editarvalija.php?id_valija=' . $id_valija . '">editar la valija</a>?</p>';

                  echo '</br><p class="mensaje">Puede agregar una <a href="agregarvalija.php">nueva valija</a></p>';

                  echo '<p class="mensaje">O puede regresar al <a href="index.php">inicio</a></p>';

                  $query = "SELECT ctas_valijas.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.num_oficio_del,
                                    ctas_valijas.fecha_recepcion_ca, ctas_valijas.fecha_captura_ca, ctas_valijas.fecha_valija_del,
                                    ctas_valijas.id_remitente, ctas_valijas.delegacion, ctas_valijas.comentario, ctas_valijas.archivo, 
                                    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
                                  FROM ctas_valijas, dspa_usuarios
                                  WHERE ctas_valijas.id_user = dspa_usuarios.id_user ";

                  $query = $query . "AND ctas_valijas.id_valija = '" . $id_valija . "'";

                  /*echo $query;*/

                  $data = mysqli_query( $dbc, $query );

                  if ( mysqli_num_rows( $data ) == 1 ) {
                    // The user row was found so display the user data
                    $rowB = mysqli_fetch_array($data);
                  }

                  ?>

                  <div class="contenedor">
                    <h2>Datos de la valija/oficio</h2>
                    <ul>
                      <li>
                        <label for="num_oficio_ca"># del Área de Gestión DSPA</label>
                        <input disabled class="textinputsmall" type="text" name="num_oficio_ca" id="num_oficio_ca" value="<?php if ( !empty( $rowB['num_oficio_ca'] ) ) echo $rowB['num_oficio_ca']; ?>"/>
                      </li>

                      <li>
                        <label for="fecha_recepcion_ca">Fecha recepción CA</label>
                        <input disabled type="date" id="fecha_recepcion_ca" name="fecha_recepcion_ca" value="<?php if (!empty($rowB['fecha_recepcion_ca'])) echo $rowB['fecha_recepcion_ca']; ?>" />
                      </li>

                      <li>
                        <label for="num_oficio_del"># del Oficio de la Delegación</label>
                        <input disabled class="textinputsmall" type="text" name="num_oficio_del" id="num_oficio_del" value="<?php if ( !empty( $rowB['num_oficio_del'] ) ) echo $rowB['num_oficio_del']; ?>"/>
                      </li>

                      <li>
                        <label for="fecha_valija_del">Fecha de Oficio de la Delegación</label>
                        <input disabled type="date" id="fecha_valija_del" name="fecha_valija_del" value="<?php if (!empty( $rowB['fecha_valija_del'] ) ) echo $rowB['fecha_valija_del']; ?>" />
                      </li>

                      <li>
                        <label for="cmbDelegaciones">Delegación IMSS</label>
                        <select disabled class="textinput" id="cmbDelegaciones" name="cmbDelegaciones">
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
                        <label for="comentario">Comentario</label>
                        <textarea disabled class="textarea" id="comentario" name="comentario"><?php if ( !empty( $rowB['comentario'] ) ) echo $rowB['comentario']; ?></textarea>
                      </li>

                      <li>
                        <label for="new_file">Archivo</label>
                        <!-- <input type="file" id="new_file" name="new_file"> -->
                        <?php 
                            if ( !empty( $rowB['archivo'] ) ) 
                              echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $rowB['archivo'] . '"  target="_new">' . $rowB['archivo'] . '</a>';
                            else echo '(Vacío)';
                          ?>
                      </li>

                      <li>
                        <label for="id_user">Capturada por:</label>
                        <input disabled class="textinputsmall" type="text" name="id_user" id="id_user" value="<?php if ( !empty( $rowB['creada_por'] ) ) echo $rowB['creada_por']; ?>"/>
                      </li>

                      <li>
                        <label for="fecha_captura_ca">Fecha de captura/modificación:</label>
                        <input disabled class="text" type="text" name="fecha_captura_ca" id="fecha_captura_ca" value="<?php if ( !empty( $rowB['fecha_captura_ca'] ) ) echo $rowB['fecha_captura_ca']; ?>"/>
                      </li>

                    </ul>
                  </div>

                  <?php
                  /*}
                  else {
                    echo '<p class="error"><strong>La nueva solicitud no ha podido generarse. Contactar al administrador.</strong></p>';
                  }*/

                  // Clear the score data to clear the form
                  
                  $_POST['num_oficio_ca'] = "";
                  $_POST['fecha_recepcion_ca'] = "";
                  $_POST['num_oficio_del'] = "";
                  $_POST['fecha_valija_del'] = "";
                  $_POST['fecha_captura_ca'] = "";
                  $_POST['cmbDelegaciones'] = 0;
                  $_POST['comentario'] = "";
                  $_POST['new_file'] = "";

                  mysqli_close( $dbc );

                } //ENDIF de "if ( !$error )"

              } // ELSE de "if ( $output_form == 'no' )"
              else {
                echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar la valija.</p>';
              }

            }
            else {
              echo '<p class="mensaje"><strong>Ahora puedes EDITAR los datos de esta valija.</strong></p>';
            }

            ?>
              
      <?php

        if ( $output_form == 'yes' ) {
          $query = "SELECT ctas_valijas.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.num_oficio_del,
                      ctas_valijas.fecha_recepcion_ca, ctas_valijas.fecha_captura_ca, ctas_valijas.fecha_valija_del,
                      ctas_valijas.id_remitente, ctas_valijas.delegacion, ctas_valijas.comentario, ctas_valijas.archivo, 
                      CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
                    FROM ctas_valijas, dspa_usuarios
                    WHERE ctas_valijas.id_user = dspa_usuarios.id_user ";

          if ( !isset( $_GET['id_valija'] ) ) {
            $query = $query . "AND ctas_valijas.id_valija = '" . $_SESSION['id_valija'] . "'";
            $id_valija_bitacora = $_SESSION['id_valija'];
            
          } else {
            $query = $query . "AND ctas_valijas.id_valija = '" . $_GET['id_valija'] . "'";
            $id_valija_bitacora = $_GET['id_valija'];
          }

          /*echo $query;*/
          /*echo $id_valija_bitacora;*/
          $data = mysqli_query( $dbc, $query );
          $rowF = mysqli_fetch_array( $data );

          if ( $rowF != NULL ) {

            $id_valija =            $rowF['id_valija'];

            $num_oficio_ca =        $rowF['num_oficio_ca'];
            $num_oficio_del =       $rowF['num_oficio_del'];

            $fecha_recepcion_ca =   $rowF['fecha_recepcion_ca'];
            $fecha_captura_ca =     $rowF['fecha_captura_ca'];
            $fecha_valija_del =     $rowF['fecha_valija_del'];
            
            $cmbDelegaciones =      $rowF['delegacion'];
            $comentario =           $rowF['comentario'];
            $archivo =              $rowF['archivo'];
            $creada_por =           $rowF['creada_por'];

            /*$new_file =             $_FILES['new_file']['name'];
            $new_file_type = $_FILES['new_file']['type'];
            $new_file_size = $_FILES['new_file']['size'];*/

          }
          else {
            ?>
            
            <?php
              echo '<p class="error">Hubo un problema leyendo la información de la valija.</p>';
            ?>
            
            <?php
              require_once('lib/footer.php');
              exit();
          }
      ?>
          <div class="contenedor">
            <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . '?id_valija=' . $id_valija; ?>">
              <h2>Datos de la valija localizada</h2>
              <ul>

                <li>
                  <input class="textinput" type="hidden" required name="id_valija" id="id_valija" value="<?php if ( !empty( $id_valija ) ) echo $id_valija; ?>"/>
                </li>

                <li>
                <label for="num_oficio_ca"># del Área de Gestión DSPA</label>
                <input class="textinputsmall" type="text" required name="num_oficio_ca" id="num_oficio_ca" maxlength="15" placeholder="# de Gestión" value="<?php if ( !empty( $num_oficio_ca ) ) echo $num_oficio_ca; ?>"/>
              </li>

              <li>
                <label for="fecha_recepcion_ca">Fecha recepción CA</label>
                <input type="date" id="fecha_recepcion_ca" name="fecha_recepcion_ca" value="<?php if (!empty($fecha_recepcion_ca)) echo $fecha_recepcion_ca; ?>" />
              </li>

              <li>
                <label for="num_oficio_del"># del Oficio de la Delegación</label>
                <input class="textinputsmall" type="text" required name="num_oficio_del" id="num_oficio_del" maxlength="15" placeholder="Núm oficio Deleg" value="<?php if ( !empty( $num_oficio_del ) ) echo $num_oficio_del; ?>"/>
              </li>

              <li>
                <label for="fecha_valija_del">Fecha de Oficio de la Delegación</label>
                <input type="date" id="fecha_valija_del" name="fecha_valija_del" value="<?php if (!empty($fecha_valija_del)) echo $fecha_valija_del; ?>" />
              </li>

              <li>
                <label for="cmbDelegaciones">Delegación IMSS</label>
                <select class="textinput" id="cmbDelegaciones" name="cmbDelegaciones">
                  <option value="0">Seleccione Delegación</option>
                  <?php
                    $query = "SELECT * 
                              FROM dspa_delegaciones 
                              WHERE activo = 1 
                              ORDER BY delegacion";
                    $result = mysqli_query( $dbc, $query );
                    while ( $row = mysqli_fetch_array( $result ) ) {
                      if ( $cmbDelegaciones == $row['delegacion'] )
                          $textselected = 'selected';
                        else
                          $textselected = '';  

                        echo '<option value="' . $row['delegacion'] . '" ' . $textselected . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
                      }
                  ?>
                </select>
              </li>

              <li>
                <label for="comentario">Comentario</label>
                <textarea class="textarea" id="comentario" name="comentario" maxlength="256" placeholder="Escriba comentarios (opcional)"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
              </li>

              <li>
                <label for="old_file">Archivo Actual</label>
                <?php 
                  if ( !empty( $archivo ) ) 
                    echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $archivo . '"  target="_new">' . $archivo . '</a>';
                  else echo '(Vacío)';
                ?>
              </li>

              <li>
                <label for="new_file">Archivo Nuevo</label>
                <input type="file" id="new_file" name="new_file">
              </li>

              <li>
                <label for="id_user">Modificada/Capturada por:</label>
                <input disabled class="textinput" type="text" name="id_user" id="id_user" value="<?php if ( !empty( $creada_por ) ) echo $creada_por; ?>"/>
              </li>

              <li>
                <label for="fecha_captura_ca">Fecha de modificación:</label>
                <input disabled class="text" type="text" name="fecha_captura_ca" id="fecha_captura_ca" value="<?php if ( !empty( $fecha_captura_ca ) ) echo $fecha_captura_ca; ?>"/>
              </li>

              <br/>

              <li class="buttons">
                <input type="submit" name="submit" value="Actualiza valija">
                <input type="reset" name="reset" value="Reset">
              </li>

              </ul>
          </div>

  <!--         }Fin de: if ( mysqli_num_rows( $data ) == 1 ) -->

      <?php
        }
      ?>
  <!--       else {

        } -->

    <?php
      //mysqli_close( $dbc );
      // Insert the page footer
      require_once('lib/footer.php');
    ?>



