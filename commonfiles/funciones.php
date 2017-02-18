<?php

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
    /*$sql_query = "INSERT INTO dspa_pistas_aud 
                    ( id_audita_act, id_audita_accion, user_id, dir_ip, informacion, fecha_pista_aud ) 
              VALUES ( " . $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "', NOW() ); ";*/
              
    $sql_query = "INSERT INTO dspa_pistas_aud 
                    ( id_audita_act, id_audita_accion, user_id, dir_ip, informacion, fecha_pista_aud ) 
              VALUES ( " . $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', NOW() ); ";
    $timeNOW = date('D d-M-Y H:i:s T', $timeNOW );

    /*$sql_query = "UPDATE ctas_solicitudes
              SET id_valija = '$cmbValijas', 
                  fecha_solicitud_del = '$fecha_solicitud_del',
                  fecha_modificacion = NOW(),
                  delegacion = '$cmbDelegaciones',
                  subdelegacion = '$cmbSubdelegaciones',
                  nombre = '$nombre',
                  primer_apellido = '$primer_apellido',
                  segundo_apellido = '$segundo_apellido',
                  matricula = '$matricula',
                  curp = '$curp',
                  usuario = '$usuario',
                  id_movimiento = '$cmbtipomovimiento',
                  id_grupo_actual = '$cmbgpoactual',
                  id_grupo_nuevo = '$cmbgponuevo',
                  comentario = '$comentario',
                  id_causarechazo = $cmbcausarechazo, 
                  archivo = '$timeNOWtime $new_file',
                  user_id = " . $_SESSION['user_id'] . " WHERE ";*/

/*      $sql_query = $sql_query . "id_solicitud = '" . $id_solicitud . "' LIMIT 1";*/
    /*echo $sql_query;*/
    /*return $sql_query;*/
    mysqli_query( $dbc, $sql_query );

    $sql_query = "SELECT LAST_INSERT_ID()";
    $result = mysqli_query( $dbc, $sql_query );
    $data = mysqli_query( $dbc, $sql_query );

    if ( mysqli_num_rows( $data ) == 1 ) {
      // The user row was found so display the user data
  /*    $row = mysqli_fetch_array($data);*/
      /*echo '<p class="nota"><strong>¡La bitacora ha sido creada exitosamente!</strong></p>';
      echo $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;*/
      return $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;
    }
    else {
      echo '<p class="nota"><strong>Error! Contacte al administrador y proporcione este dato: ' . $pid_audita_act . ', ' . $timeNOW . '</strong></p>';
      /*echo $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;*/
      return "Error:" . $pid_audita_act . ", " . $pid_audita_accion . ", " . $pUserId . ", '" . $pdir_ip . "', '" . $pInformacion . "|', " . $timeNOW;
    }

  }


  //FUNCION DE CONEXIÓN:
  function fnConnectBD( $tmp_string )
  {
    // Conectarse a la BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    /* check connection */
    if ( mysqli_connect_errno () ) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return "Falló la conexión a base de datos";
    }
      
    /* change character set to utf8 */
    if ( !$dbc->set_charset( "utf8" ) ) {
        printf("Error loading character set utf8: %s\n", $dbc->error);
        return "Error al cargar set de caracteres utf8. Contacte al administrador del sitio.";
    } else {
        //printf("Current character set: %s\n", $dbc->character_set_name () );
        return "";
    }

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

?>
