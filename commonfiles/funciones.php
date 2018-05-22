<?php

  function fnTituloPag( $pvar_modulo )
  { //Change this to CASE
    if ($pvar_modulo == 1) 
      return "Registro: ";
    else
      return"";
  }

  function fnGuardaBitacora( $pid_audita_act, $pid_audita_accion, $pUserId, $pdir_ip, $pInformacion )
  {
    // Conectarse a la BD
    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    /* check connection */
    if ( mysqli_connect_errno() ) {
        printf( "Connect failed: %s\n", mysqli_connect_error() );
        exit();
    }
      
    /* change character set to utf8 */
    if ( !$dbc->set_charset( "utf8" ) ) {
        printf( "Error loading character set utf8: %s\n", $dbc->error );
        exit();
    }

    $timeNOW = time();
              
    $sql_query = "INSERT INTO dspa_pistas_aud 
                    ( id_audita_act, id_audita_accion, id_user, dir_ip, informacion, fecha_pista_aud ) 
              VALUES ( " . $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', NOW() ); ";
    $timeNOW = date('D d-M-Y H:i:s T', $timeNOW );
    /*echo $sql_query;*/
    mysqli_query( $dbc, $sql_query );

    $sql_query = "SELECT LAST_INSERT_ID()";
    $result = mysqli_query( $dbc, $sql_query );
    $data = mysqli_query( $dbc, $sql_query );

    if ( mysqli_num_rows( $data ) == 1 ) {
      return $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;
    }
    else {
      echo '<p class="nota"><strong>Error! Contacte al administrador y proporcione este dato: ' . $pid_audita_act . ', ' . $timeNOW . '</strong></p>';
      /*echo $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;*/
      return "Error:" . $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;
    }

  }

  //FUNCION DE CONEXIÓN:
  function fnConnectBD( $pid_user, $pip_address, $phost, $pInformacion )
  {
    $resultado_fnConnectBD = "Error";
    // Conectarse a la BD
    $pInformacion = $pInformacion . '|HOST:' . DB_HOST . '|USER:' . DB_USER . '|DB_NAME:' . DB_NAME;
    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
    /*$log = fnGuardaBitacora( 5, 6, $pid_user,  $pip_address, '|ConnectDB(Ok)|' . $phost . ' ' . $pInformacion );*/
    
    /* check connection */
    if ( mysqli_connect_errno () ) {
        printf("Connect failed: %s\n", mysqli_connect_error());

        $resultado_fnConnectBD = "Falló la conexión a base de datos. Contacte al administrador del sitio.";

        $log = fnGuardaBitacora( 5, 6, $pid_user, $pip_address, '|ConnectDB(Error)|EQUIPO:' . $phost . ' ' . $pInformacion );
    }
      
    /* change character set to utf8 */
    if ( !$dbc->set_charset( "utf8" ) ) {
        printf("Error loading character set utf8: %s\n", $dbc->error);
        $resultado_fnConnectBD = "Error al cargar set de caracteres utf8. Contacte al administrador del sitio.";
        $log = fnGuardaBitacora( 5, 6, $pid_user, $pip_address, '|ConnectDB(Error-utf8)|EQUIPO:' . $phost . ' ' . $pInformacion );
    } 
    else {
      //printf("Current character set: %s\n", $dbc->character_set_name () );
      $resultado_fnConnectBD = "";
    }
    return $dbc;
  }


?>
