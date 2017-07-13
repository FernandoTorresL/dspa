<?php

  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectvars.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Cerrar Lote - Gestión Cuentas SINDO ';
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-CerrarLote' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user 
            FROM  dspa_permisos
            WHERE id_modulo = 20
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 113, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }

  $query = "SELECT id_lote, lote_anio
            FROM  ctas_lotes ";

  if ( !isset( $_GET['id_lote'] ) ) {
    $query = $query . "WHERE id_lote = " . $_SESSION['id_lote'];
    $id_lote = $_SESSION['id_lote'];
    
  } 
  else {
    $query = $query . "WHERE id_lote = " . $_GET['id_lote'];
    $id_lote = $_GET['id_lote'];
  }

  /*echo $query;
  echo "|" . $id_lote . "|";*/
  $data = mysqli_query($dbc, $query);
  $rowF = mysqli_fetch_array( $data );

  if ( $rowF != NULL || $id_lote > 144) {
    $lote_anio = $rowF['lote_anio'];
    $query = "UPDATE ctas_solicitudes SET id_lote = " . $id_lote . " WHERE id_lote = 0";
    /*echo $query;*/
    mysqli_query( $dbc, $query );

      /*$id_valija_bitacora = $row['LAST_INSERT_ID()'];*/
      $log = fnGuardaBitacora( 1, 113, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_lote:' . $id_lote . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

      echo '<p class="mensaje"><strong>¡El lote D000 se ha convertido en el lote ' . $lote_anio . '! Se ha cerrado el lote ' . $lote_anio . '</strong></p>';
      echo '<p class="mensaje">Todas las capturas a partir de este momento formarán un nuevo lote D000</p></br>';
      echo '<p class="mensaje">Puede regresar al <a href="verDetalleCuentasSINDO.php">RESUMEN</a></p></br>';
      echo '<p class="mensaje">Puede regresar al <a href="index.php">inicio</a></p>';
  }
  else 
  { //if( $rowF != NULL )
    echo '<p class="error">No se pudo cerrar el lote. Favor de contactar al Adminsitrador del sitio</p></br>';
  }
  
  // Insert the page footer
  require_once('lib/footer.php');
?>




