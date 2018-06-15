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

  //FUNCIONES PARA COMBOBOX
  function fnvalijaSelect( $var_valija )
  {
    if ( empty( $_POST['cmbValijas'] ) ) 
      return "";
    else if ( $_POST['cmbValijas'] == $var_valija ) 
      return "selected";
  }

  function fnloteSelect( $var_lote )
  {
    if ( empty( $_POST['cmbLotes'] ) )
      return "";
    else if ( $_POST['cmbLotes'] == $var_lote )
      return "selected";
  }

  function fntipomovimientoSelect( $var_tipomovimiento )
  {
    if ( empty( $_POST['cmbtipomovimiento'] ) ) 
      return 0;    
    else if ( $_POST['cmbtipomovimiento'] == $var_tipomovimiento )
      return "selected";
  }

  function fntdelegacionSelect($var_delegacion)
  {
    if (empty($_POST['cmbDelegaciones']))
      return "";
    else if ($_POST['cmbDelegaciones'] == $var_delegacion)
      return "selected";
  }

  function fntsubdelegacionSelect($var_subdelegacion)
  {
    if ( empty( $_POST['cmbSubdelegaciones'] ) && $_POST['cmbSubdelegaciones'] <> 0 ) {
      return "";
      /*echo "nad";*/
    }
    else if ( $_POST['cmbSubdelegaciones'] == $var_subdelegacion ) {
      return "selected";
      /*echo "els";*/
    }
  }

  function fntPuestoSelect($var_id_puesto)
  {
    if (empty($_POST['cmbPuesto']))
      return "";
    else if ($_POST['cmbPuesto'] == $var_id_puesto)
      return "selected";
  }

  function fntcmbgponuevoSelect($var_gponuevo)
  {
    if ( empty( $_POST['cmbgponuevo'] ) )
      return "";
    else if ( $_POST['cmbgponuevo'] == $var_gponuevo )
      return "selected";
  }

  function fntcmbgpoactualSelect($var_gpoactual)
  {
    if ( empty( $_POST['cmbgpoactual'] ) )
      return "";
    else if ( $_POST['cmbgpoactual'] == $var_gpoactual )
      return "selected";
  }

  function fntcmbcausarechazoSelect($var_causarechazo)
  {
    if ( empty( $_POST['cmbcausarechazo'] ) && $_POST['cmbcausarechazo'] <> 0 )
      return "";
    else if ( $_POST['cmbcausarechazo'] == $var_causarechazo )
      return "selected";
  }

  function fntPersonaUSAFSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaUSAF'] ) && $_POST['cmbPersonaUSAF'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaUSAF'] == $var_persona )
      return "selected";
  }

  function fntPersonaSolicitanteSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaSolicitante'] ) && $_POST['cmbPersonaSolicitante'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaSolicitante'] == $var_persona )
      return "selected";
  }

  function fntPersonaTitularSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaTitular'] ) && $_POST['cmbPersonaTitular'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaTitular'] == $var_persona )
      return "selected";
  }

  function fnOpcionUSAFSelect( $var_opcion )
  {
    if ( empty( $_POST['cmbOpcion'] ) ) 
      return 0;    
    else if ( $_POST['cmbOpcion'] == $var_tipomovimiento )
      return "selected";
  }

?>
