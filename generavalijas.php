<?php

  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Genera Valijas - Gestión Cuentas SINDO ';
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-GeneraValijas' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user 
            FROM  dspa_permisos
            WHERE id_modulo = 18
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 111, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }

  $query = "SELECT id_archivo, nombre_archivo
            FROM  ctas_archivos ";

  if ( !isset( $_GET['id_archivo'] ) ) {
    $query = $query . "WHERE id_archivo = " . $_SESSION['id_archivo'];
    $id_archivo = $_SESSION['id_archivo'];
    
  } 
  else {
    $query = $query . "WHERE id_archivo = " . $_GET['id_archivo'];
    $id_archivo = $_GET['id_archivo'];
  }

  /*echo $query;*/
  $data = mysqli_query($dbc, $query);
  $rowF = mysqli_fetch_array( $data );

  if ( $rowF != NULL ) {

/*"dspa_web\\tmp_archivos\\Load 05May2017.txt"*/
    /*$target = '"..\\\\..\\\\htdocs\\\\dspa\\\\files\\\\' . $rowF['nombre_archivo'] . '"';*/
    /*echo $target;*/
    /*echo "|";
    echo dirname(__FILE__) . $target;
    echo "|";*/
    /*echo $lines;*/
/*    foreach ( $lines as $line ) {
      $row = explode( "\t", $line );
      echo "INSERT INTO tablename SET VAL1 = '" . trim( $row[0] ) . "', val2 = '" . trim( $row[1] ) . "'";
      echo "</br>";
    }*/

    $target = '\files\\' . $rowF['nombre_archivo'];
    $content = file_get_contents( dirname(__FILE__) . $target );
    $lines = explode( "\n", $content );

    echo '<p class="mensaje">Datos de valijas que se crearon</p>';

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

        $query = "INSERT INTO dspa_web.ctas_valijas 
                    ( num_oficio_ca, num_oficio_del, fecha_recepcion_ca, fecha_captura_ca, 
                      fecha_valija_del, id_remitente, delegacion, comentario, archivo, id_user)
                  VALUES ( '" . $row['1'] . "', '" . $row['4'] ."', STR_TO_DATE( '" . $row['6'] . "' , '%d/%m/%Y'), NOW(), STR_TO_DATE( '"
                             . $row['5'] . "' , '%d/%m/%Y'), 0, " . $row['33'] . ", TRIM( '" . $row['14'] . "' ), '', " . $_SESSION['id_user'] . " );";
        /*echo $query;*/
        mysqli_query( $dbc, $query );

        $query = "SELECT LAST_INSERT_ID()";

        /*$result = mysqli_query( $dbc, $query );*/
        $data = mysqli_query( $dbc, $query );

        if ( mysqli_num_rows( $data ) == 1 ) {
          // The user row was found so display the user data
          $row = mysqli_fetch_array($data);

          $id_valija_bitacora = $row['LAST_INSERT_ID()'];
          $log = fnGuardaBitacora( 1, 111, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_valija:' . $id_valija_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
        }
    }
    echo '</table>';

    $query = "SELECT ctas_valijas.id_valija, ctas_valijas.delegacion AS num_del, dspa_delegaciones.descripcion AS delegacion_descripcion, 
                ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, ctas_valijas.num_oficio_del, 
                ctas_valijas.fecha_valija_del, ctas_valijas.comentario, ctas_valijas.archivo,
                (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_valija = ctas_valijas.id_valija) AS num_solicitudes,
                CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
              FROM ctas_valijas, dspa_delegaciones, dspa_usuarios
              WHERE ctas_valijas.delegacion = dspa_delegaciones.delegacion 
              AND   ctas_valijas.id_user = dspa_usuarios.id_user
              ORDER BY ctas_valijas.id_valija DESC LIMIT 200";
              //ORDER BY ctas_valijas.fecha_captura_ca DESC LIMIT 300";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">últimas valijas capturadas</p>';
  echo '<p class="titulo2">Agregar <a href="./agregarvalija.php">nueva valija</a></p>';
  
  echo '<table class="striped" border="1">';
  echo '<tr class="dato">';
  echo '<tr class="dato"><th># Valija</th>';
  echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Área de Gestión</th>';
  
  echo '<th># Oficio Delegación</th>';
  echo '<th>Fecha Oficio Delegación</th>';

  echo '<th>Delegación que envía</th>';
  echo '<th>Comentario</th>';
  /*echo '<th>Archivo</th>';*/
  echo '<th>Cantidad de solicitudes</th>';
  /*echo '<th>Creada por</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
    require_once('lib/footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {
    //$id_valija = $row['id_valija'];
    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
    echo '<td class="lista">' . $row['fecha_recepcion_ca'] . '</td>';

    echo '<td class="lista">' . $row['num_oficio_del'] . '</td>';
    echo '<td class="lista">' . $row['fecha_valija_del'] . '</td>';

    echo '<td class="lista">' . '(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . '</td>';
    
    echo '<td class="lista">' . $row['comentario'] . '</td>';    
    //echo '<td class="lista">' . $row['archivo'] . '</td>';
    /*if (!empty($row['archivo'])) {
      echo '<td class="lista"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_blank">Ver</a></td>';
    }
    else {
      echo '<td class="lista">(Vac&iacute;o)</a></td>';
    }*/
    echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
    /*echo '<td class="lista">' . $row['creada_por'] . '</td>';*/
    echo '</tr>';
  }

  echo '</table></br></br>';
  
  
      echo '</div>';
    echo '</div>';
  echo '</div>';

  mysqli_close($dbc);
    
  // Insert the page footer
  require_once('lib/footer.php');

  }
  else 
  { //if( $rowF != NULL )
    echo '</table></br><p class="error">No se localizó el archivo o es incorrecto. Favor de revisar y reintentar</p></br>';
  }


    require_once('lib/footer.php');
?>




