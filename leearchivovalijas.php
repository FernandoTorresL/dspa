<?php

  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectvars.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Lee Archivo Valijas - Gestión Cuentas SINDO ';
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-LeeArchivoValijas' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user 
            FROM dspa_permisos
            WHERE id_modulo = 17
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 110, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }

  if ( isset( $_POST['submit'] ) ) {


    $comentario = mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    $old_layout = mysqli_real_escape_string($dbc, trim($_POST['old_layout']));
    $new_layout = mysqli_real_escape_string($dbc, trim($_FILES['new_layout']['name']));
    /*echo $new_layout;*/
    $new_layout_type = $_FILES['new_layout']['type'];
    $new_layout_size = $_FILES['new_layout']['size']; 

    /*$output_form = 'no';*/
    $error = false;

    // Validate and move the uploaded layout file, if necessary
    if ( !empty( $new_layout ) ) {
      //list($new_layout_width, $new_layout_height) = getimagesize($_FILES['new_picture']['tmp_name']);
      if ( ( $new_layout_type == 'text/plain' ) && ( $new_layout_size > 0 ) && ( $new_layout_size <= MM_MAXFILESIZE_VAL ) ) {
        if ( $_FILES['new_layout']['error'] == 0 ) {
          // Move the file to the target upload folder for layouts
          $timetime = time();
          $archivo_final = $timetime . " " . basename($new_layout);
          $target = MM_UPLOADPATH_VAL . $archivo_final;
          /*$target = MM_UPLOADPATH_VAL . basename($new_layout);*/
          
          if ( move_uploaded_file( $_FILES['new_layout']['tmp_name'], $target ) ) {
            // The new layout file move was successful, now make sure any old layout is deleted
            //The number is unique, so insert the data
            $query = "INSERT INTO ctas_archivos 
                        ( nombre_archivo, fecha_recepcion, comentario, id_user )
                      VALUES 
                        ( '$archivo_final', NOW(), '$comentario', " . $_SESSION['id_user'] . " )";
            /*echo $query;*/
            mysqli_query( $dbc, $query );

            $query = "SELECT LAST_INSERT_ID()";

            /*$result = mysqli_query( $dbc, $query );*/
            $data = mysqli_query( $dbc, $query );

            if ( mysqli_num_rows( $data ) == 1 ) {
              // The user row was found so display the user data
              $row = mysqli_fetch_array($data);

              $id_archivo_bitacora = $row['LAST_INSERT_ID()'];
              $log = fnGuardaBitacora( 1, 110, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_archivo:' . $id_archivo_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

              echo "<p class='mensaje'>¡Lectura exitosa del archivo! Se ha renombrado al nombre único '" . $timetime . " " . basename($new_layout) . "'</p>";
              
              echo '<p class="mensaje">Puede regresar al <a href="index.php">inicio</a></p>';  

              $target = '\files\\' . $archivo_final;
              $content = file_get_contents( dirname(__FILE__) . $target );
              $lines = explode( "\n", $content );

              echo '<p class="mensaje">Datos de valijas que se cargarán</p>';

              echo '<table class="striped" border="1">';
              echo '<tr class="dato">';
              echo '<th># del Área de Gestión DSPA</th>';
              echo '<th># del Oficio de la Delegación</th>';
              echo '<th>Fecha recepción CA</th>';
              echo '<th>Fecha de Oficio de la Delegación</th>';
              echo '<th>Delegación IMSS</th>';
              echo '<th>Comentario</th>';
              echo '</tr>';
              foreach ( $lines as $line ) {
                  $row = explode( "\t", $line );
                  echo '<tr>';
                  echo '<td>' . $row['1'] . '</td>';
                  echo '<td>' . $row['4'] . '</td>';
                  echo '<td>' . $row['6'] . '</td>';
                  echo '<td>' . $row['5'] . '</td>';
                  echo '<td>' . $row['33'] . '</td>';
                  echo '<td>' . $row['14'] . '</td>';
                  echo '</tr>';
              }
              echo '<tr>';
              echo '<td><strong><a href="generavalijas.php?id_archivo=' . $id_archivo_bitacora . '">Genera valijas con estos registros</a></strong></td>';
              echo '</tr>';
              echo '</table>';

            }
            else{
              @unlink( $_FILES['new_layout']['tmp_name'] );
              $error = true;
              echo '<p class="error">Lo sentimos, hubo un problema al tratar de cargar el archivo.</p>';  
            }  

            if ( !empty( $old_layout ) && ( $old_layout != $new_layout ) ) {
              @unlink( MM_UPLOADPATH_VAL . $old_layout );
            }
          }
          else {
            // The new layout file move failed, so delete the temporary file and set the error flag
            @unlink( $_FILES['new_layout']['tmp_name'] );
            $error = true;
            echo '<p class="error">Lo sentimos, hubo un problema al tratar de cargar el archivo.</p>';
          }
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink( $_FILES['new_layout']['tmp_name'] );
        $error = true;
        echo '<p class="error">El archivo debe ser un archivo .txt y no mayor de ' . (MM_MAXFILESIZE_VAL / 1024) . ' KB.</p>';
        $output_form = 'yes';
      }
    }
  
    if ( !$error ) {
      if ( !empty($new_layout) ) {
        //Validate...
        //echo "Aquí inicia validación...";
      }
      else {
        echo '<p class="error">Debes seleccionar un archivo para lectura</p>';
      }
    }

  } // End of check for form submission
  else{
    $output_form = 'no';
  }

  if ( $output_form == 'yes' ) {
  
    //Aquí se mostraba la lista de archivos de dspa_archivos
    $query = "SELECT id_archivo, nombre_archivo, fecha_recepcion, comentario, 
                CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS registrado_por
              FROM ctas_archivos, dspa_usuarios
              WHERE ctas_archivos.id_user = dspa_usuarios.id_user
              ORDER BY 1 DESC";
    
    $data = mysqli_query($dbc, $query);
    // Loop through the array of user data, formatting it as HTML
    echo '<h4>Últimos archivos ingresados:</h4>';

    echo '<table class="striped" border="1">';
    echo '<tr class="dato">';
    echo '<th># Archivo</th>';
    echo '<th>Nombre Archivo</th>';
    echo '<th>Fecha recepción</th>';
    echo '<th>Comentario</th>';
    echo '<th>Registrado por</th>';
    /*echo '<th>CARGA REGISTROS</th>';*/
    echo '</tr>';

    if (mysqli_num_rows($data) == 0) {
      echo '</table></br><p class="error">No hay archivos recientes</p></br>';
    }
    else {
      while ( $row = mysqli_fetch_array( $data ) ) {
        echo '<tr>';
        echo '<td>' . $row['id_archivo'] . '</td>';
        echo '<td>' . $row['nombre_archivo'] . '</td>';
        echo '<td>' . $row['fecha_recepcion'] . '</td>';
        echo '<td>' . $row['comentario'] . '</td>';
        echo '<td>' . $row['registrado_por'] . '</td>';
        /*echo '<td><strong><a href="generavalijas.php?id_archivo=' . $row['id_archivo'] . '">Genera valijas</a></strong></td></tr>';*/
        echo '</tr>';
      }
      echo '</table>';
    }
  }
  ?>

  <div class="contenedor">
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h2>Cargar archivo .txt con datos de valijas</h2>
      <ul>
        
        <li>
          <input type="hidden" name="old_layout" value="<?php if ( !empty( $old_layout ) ) echo $old_layout; ?>" />
          <label for="new_layout">Archivo:</label>
          <input type="file" id="new_layout" name="new_layout" />
          
          <?php if ( !empty( $old_layout ) ) {
            //echo '<img class="profile" src="' . MM_UPLOADPATH_PROFILE . $old_picture . '" alt="Imagen de Perfil" />';
            echo '<p class="error">No se encontró archivo</p>';
          }
          ?> 
        </li>

        <li>
          <label for="comentario">Comentario</label>
          <textarea class="textarea" id="comentario" name="comentario" maxlength="256" placeholder="Escriba comentarios (opcional)"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
        </li>

        <br/>

        <li class="buttons">
          <input type="submit" name="submit" value="Registra archivo!">
          <input type="reset" name="reset" value="Reset">
        </li>
        
      </ul>
    </form>
  </div>    

  <?php
  // Insert the page footer
  require_once('lib/footer.php');
?>

