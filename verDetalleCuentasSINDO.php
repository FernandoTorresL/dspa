<?php

  // Start the session
  require_once( 'commonfiles/startsession.php' );

  require_once( 'lib/ctas_appvars.php' );
  require_once( 'lib/connectBD.php' );
  
  // Insert the page header
  $page_title = 'Gestión Cuentas SINDO - Ver Solicitud';
  require_once( 'lib/header.php' );
  
  // Show the navigation menu
  require_once( 'lib/navmenu.php' );
  require_once( 'commonfiles/funciones.php');

    // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['id_user'] ) ) {
    echo '<p class="error">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    require_once('lib/footer.php');
    exit();
  }

  /*echo '<section id="main-container">';
  echo '<div class="container">';*/
  echo '<div class="section no-pad-bot" id="index-banner">';
    echo '<div class="container">';
      echo '<div class="row center">';

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


  //Mostrar lotes
    
  // Obtener los últimos lotes capturados al momento
  $query = "SELECT ctas_lotes.id_lote, ctas_lotes.lote_anio, 
    ctas_lotes.fecha_modificacion, ctas_lotes.fecha_creacion, ctas_lotes.comentario,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_lote = 0) AS num_solicitudes,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creado_por, ctas_lotes.num_oficio_ca, 
    ctas_lotes.fecha_oficio_ca, ctas_lotes.num_ticket_mesa, ctas_lotes.fecha_atendido
    FROM ctas_lotes, dspa_usuarios
    WHERE ctas_lotes.id_user = dspa_usuarios.id_user
    AND ctas_lotes.id_lote = 0
    ORDER BY 3 DESC LIMIT 2";

  $data = mysqli_query($dbc, $query);

/*  echo '<p class="titulo1">Lote D000</p>';
  
  echo '<p class="titulo2">Agregar <a href="">nuevo lote</a></p>';
*/
  echo '<table class="striped" border="1">';
  echo '<tr class="dato">';
    echo '<th># Lote</th>';
    echo '<th># Oficio CA</th>';
    echo '<th>Fecha oficio</th>';
    echo '<th># Ticket MSI</th>';
    echo '<th>Fecha de atención</th>';
    /*echo '<th>Fecha modificaci&oacute;n</th>';*/
    /*echo '<th>Fecha creaci&oacute;n</th>';*/
    echo '<th>Cantidad de Solicitudes</th><th>Comentario</th>';
    /*echo '<th>Creado por</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay lotes capturados</p></br>';
  /*  require_once('footer.php');
    exit();*/
  }

  while ( $row = mysqli_fetch_array($data) ) {
    $id_lote = $row['id_lote'];
    //echo '<tr class="dato"><td class="lista"><a href="editarlote.php?id_lote=' . $row['id_lote'] . '">' . $row['id_lote'] . ' / ' . $row['anio'] . '</a></td>';
    echo '<tr class="dato">';
      echo '<td class="lista">' . $row['lote_anio'] . '</td>';
      echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['fecha_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['num_ticket_mesa'] . '</td>';
      echo '<td class="lista">' . $row['fecha_atendido'] . '</td>';
      /*echo '<td class="lista">' . $row['fecha_modificacion'] . '</td>';
      echo '<td class="lista">' . $row['fecha_creacion'] . '</td>';*/
      echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
      echo '<td width="10px" class="lista">' . $row['comentario'] . '</td>';
      /*echo '<td class="lista">' . $row['creado_por'] . '</td>';*/
    echo '</tr>';
  }

  echo '</table></br></br>';

//Mostrar solicitudes del lote 0
  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = "SELECT 
    ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, ctas_valijas.archivo AS archivo_valija, ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, 
    ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion,
    ctas_lotes.lote_anio AS num_lote_anio, 
    ctas_solicitudes.delegacion AS num_del, dspa_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.delegacion AS num_del_val, 
    ctas_solicitudes.subdelegacion AS num_subdel, dspa_subdelegaciones.descripcion AS subdelegacion_descripcion, 
    ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
    ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
    ctas_movimientos.descripcion AS movimiento_descripcion, 
    grupos1.descripcion AS grupo_actual, grupos2.descripcion AS grupo_nuevo, 
    ctas_solicitudes.comentario, ctas_causasrechazo.id_causarechazo, ctas_causasrechazo.descripcion AS causa_rechazo, ctas_solicitudes.archivo,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
    FROM ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2, dspa_usuarios, ctas_causasrechazo
    WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
    AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
    AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
    AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
    AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
    AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
    AND   ctas_solicitudes.id_grupo_actual= grupos1.id_grupo
    AND   ctas_solicitudes.id_grupo_nuevo= grupos2.id_grupo
    AND   ctas_solicitudes.id_user = dspa_usuarios.id_user
    AND   ctas_solicitudes.id_causarechazo = ctas_causasrechazo.id_causarechazo
    AND   ctas_solicitudes.id_lote = 0
    ORDER BY ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC, ctas_solicitudes.id_movimiento ASC";
    //ORDER BY ctas_solicitudes.id_movimiento ASC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC";
    //ORDER BY ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.fecha_modificacion DESC";

    //ORDER BY ctas_solicitudes.fecha_captura_ca DESC, ctas_solicitudes.id_solicitud ASC, ctas_solicitudes.fecha_modificacion DESC";
    //ORDER BY ctas_valijas.id_valija DESC, ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC ";
    //AND   ctas_solicitudes.id_causarechazo = 0
    //AND   ctas_valijas.ID_VALIJA BETWEEN 2259 AND 2284
    //2259 AND 2284
    /*AND   ctas_solicitudes.id_movimiento = 2*/
    
    /*AND   ctas_solicitudes.id_user = 19
    AND   ctas_solicitudes.subdelegacion = 0
    AND   ctas_solicitudes.id_movimiento = 1*/

    
    
    
    //ORDER BY ctas_solicitudes.fecha_modificacion ASC, ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.id_movimiento ASC";
    //AND   ctas_solicitudes.id_lote = 106
    //AND   ctas_solicitudes.id_lote = 79
    //AND   ctas_solicitudes.id_movimiento=2
    
    //ctas_movimientos.descripcion, ctas_solicitudes.usuario,
    //AND   ctas_solicitudes.rechazado <> 1
    //AND   ctas_solicitudes.id_lote = 4
    //AND   ctas_solicitudes.rechazado <> 1
  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes Capturadas sin lote (Lote D00)</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table class="striped" border="1">';
  /*echo '<thead>';*/
  echo '<tr>';
  /*echo '<th># Valija</th>';*/
  /*echo '<th># de Lote</th>';*/
  echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Recepción CA</th>';
  echo '<th>Creada/Modificada por</th>';
  echo '<th>Delegación - Subdelegación</th>';
  echo '<th>Nombre completo</th>';
  /*echo '<th>Matrícula</th>';*/
  /*echo '<th>CURP</th>';*/
  //echo '<th>CURP correcta</th>';
  //echo '<th>Cargo</th>'
;  echo '<th>Usuario(Mov)</th>';
  /*echo '<th>Movimiento</th>';*/
  echo '<th>Grupo Actual->Nuevo</th>';
  echo '<th>Comentario</th>';
  /*echo '<th></th>';*/
  echo '<th>Causa Rechazo</th>';
  echo '<th>PDF</th>';
  echo '</tr>';
  /*echo '</thead>';  */

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay solicitudes nuevas (Sin lote asignado).</p></br>';
    /*require_once('footer.php');
    exit();*/
  }

  while ( $row = mysqli_fetch_array($data) ) {

    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    /*echo '<tbody>';*/
    echo '<tr class="dato condensed">';
    //echo '<td class="lista"><a href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['id_solicitud'] . '</a></td>';
    //echo '<td class="lista">' . $row['id_solicitud'] . '</td>';
    /*echo '<td class="lista">' . $row['id_valija'] . '</td>';*/
    /*echo '<td>' . $row['num_lote_anio'] . '</td>';*/
    /*echo '<td>' . $row['num_oficio_ca'] . '</td>';*/
    echo '<td class="mensaje"><a target="_blank" href="editarvalija.php?id_valija=' . $row['id_valija'] . '">Editar ' . $row['num_oficio_ca'] . '
    </a></td>';
    /*echo '<td>' . $row['fecha_recepcion_ca'] . '</td>';*/
    echo '<td>' . $row['fecha_captura_ca'] . '</td>';
    echo '<td>' . $row['creada_por'] . '</td>';
    echo '<td class="mensaje">' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    //echo '<td>' . $row['primer_apellido'] . '</td>'; 
    //echo '<td>' . $row['segundo_apellido'] . '</td>'; 
    //echo '<td>' . $row['nombre'] . '</td>';
    /*echo '<td>' . $row['matricula'] . '</td>'; */
    /*echo '<td>' . $row['curp'] . '</td>'; */
    //echo '<td>' . $row['curp_correcta'] . '</td>'; 
    //echo '<td>' . $row['cargo'] . '</td>';
    echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
    /*echo '<td>' . $row['movimiento_descripcion'] . '</td>'; */
    echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
    echo '<td>' . $row['comentario'] . '</td>';
    /*echo '<td><a target="_blank" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">Ver</a>' . '</td>';*/
    
    if ( !empty( $row['id_causarechazo'] ) && $row['id_causarechazo'] <> 0 )
      echo '<td class="error">' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';
    else echo '<td>' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';

    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    } 
    echo '</tr>';
    /*echo '</tbody>';*/
    //$archivox = $row['archivo'];
  }    

  echo '</table></br></br>';

  //Mostrar lotes
    
  // Obtener los últimos lotes capturados al momento
  $query = "SELECT ctas_lotes.id_lote, ctas_lotes.lote_anio, 
    ctas_lotes.fecha_modificacion, ctas_lotes.fecha_creacion, ctas_lotes.comentario,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_lote = ctas_lotes.id_lote) AS num_solicitudes,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creado_por, ctas_lotes.num_oficio_ca, 
    ctas_lotes.fecha_oficio_ca, ctas_lotes.num_ticket_mesa, ctas_lotes.fecha_atendido
    FROM ctas_lotes, dspa_usuarios
    WHERE ctas_lotes.id_user = dspa_usuarios.id_user
    UNION
    SELECT ctas_lotes.id_lote, ctas_lotes.lote_anio, 
    ctas_lotes.fecha_modificacion, ctas_lotes.fecha_creacion, ctas_lotes.comentario,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_lote = 0) AS num_solicitudes,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creado_por, ctas_lotes.num_oficio_ca, 
    ctas_lotes.fecha_oficio_ca, ctas_lotes.num_ticket_mesa, ctas_lotes.fecha_atendido
    FROM ctas_lotes, dspa_usuarios
    WHERE ctas_lotes.id_user = dspa_usuarios.id_user
    AND ctas_lotes.id_lote = 0
    ORDER BY 3 DESC LIMIT 20";

  $data = mysqli_query($dbc, $query);

/*
  $mi_pdf = MM_UPLOADPATH_CTASSINDO . '\\' . '1452713181 60953_3.pdf';
  header('Content-type: application/pdf');
  header('Content-Disposition: attachment; filename="'. $mi_pdf . '"');
  readfile($mi_pdf);
  */

  echo '<p class="titulo1">Últimos 10 lotes</p>';
  
  //$t=time();
  //echo($t . "<br>");
  //echo(date("Y-m-d H:i:s",$t));

  //$t=time();
  //$t=$t-25200;//menos 7 horas
  //echo($t . "<br>Tiempo actual<br>");
  //echo(date("Y-m-d H:i:s",$t));

  //$t=time();
  //$t="1467671939";
  //echo($t . "<br>");
  //echo(date("Y-m-d H:i:s",$t));
  //echo($t . "<br>");
  //echo("<br> Tiempo archivo<br>");
  //$t=$t-86400;
  //$t=$t+18000;
  //echo(date("Y-m-d H:i:s",$t));
  //echo(ADDTIME(date("Y-m-d H:i:s",$t) , '02:00:00'));


  /*echo '<p class="titulo2">Agregar <a href="">nuevo lote</a></p>';*/

  echo '<table class="lote" border="1">';
  echo '<tr class="dato">';
    echo '<th># Lote</th>';
    echo '<th># Oficio CA</th>';
    echo '<th>Fecha oficio</th>';
    echo '<th># Ticket MSI</th>';
    echo '<th>Fecha de atenci&oacute;n</th>';
    /*echo '<th>Fecha modificaci&oacute;n</th>';*/
    /*echo '<th>Fecha creaci&oacute;n</th>';*/
    echo '<th>Cantidad de Solicitudes</th><th>Comentario</th>';
    /*echo '<th>Creado por</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay lotes capturados</p></br>';
    require_once('footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {
    $id_lote = $row['id_lote'];
    //echo '<tr class="dato"><td class="lista"><a href="editarlote.php?id_lote=' . $row['id_lote'] . '">' . $row['id_lote'] . ' / ' . $row['anio'] . '</a></td>';
    echo '<tr class="dato">';
      echo '<td class="lista">' . $row['lote_anio'] . '</td>';
      echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['fecha_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['num_ticket_mesa'] . '</td>';
      echo '<td class="lista">' . $row['fecha_atendido'] . '</td>';
      /*echo '<td class="lista">' . $row['fecha_modificacion'] . '</td>';
      echo '<td class="lista">' . $row['fecha_creacion'] . '</td>';*/
      echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
      echo '<td class="lista">' . $row['comentario'] . '</td>';
      /*echo '<td class="lista">' . $row['creado_por'] . '</td>';*/
    echo '</tr>';
  }

  echo '</table></br></br>';




  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = 'SELECT DISTINCT
              D2.delegacion AS num_del_val, V.id_valija AS id_valija, V.num_oficio_ca AS num_oficio_ca,
              V.archivo AS archivo_valija, S.archivo AS archivo_solicitud,
              S.delegacion AS num_del, D.descripcion AS delegacion_descripcion, V.num_oficio_del AS num_oficio_deleg, 
              S.subdelegacion AS num_subdel, SD.descripcion AS subdelegacion_descripcion, 
              L.lote_anio AS num_lote_anio, L.id_lote AS id_lote, L.fecha_atendido as fecha_atendido,
              S.fecha_captura_ca, DATE_FORMAT(S.fecha_captura_ca, "%d%M%y %H:%i") AS fecha_cap_formato,
              S.fecha_solicitud_del, DATE_FORMAT(S.fecha_solicitud_del, "%d%M%y %H:%i") AS fecha_sol_del_formato,
              S.fecha_modificacion, DATE_FORMAT(S.fecha_modificacion, "%d%M%y %H:%i") AS fecha_mod_formato,
              S.primer_apellido, S.segundo_apellido, S.nombre,
              CONCAT(DU.nombre, " ", DU.primer_apellido) AS creada_por,
              S.matricula AS matricula, S.curp, S.curp_correcta, S.cargo, S.usuario AS usuario,
              IF( RS.usuario_mainframe IS NULL, S.usuario, RS.usuario_mainframe ) AS usuario_MAINFRAME,
              M.descripcion AS movimiento_descripcion, 
              G2.descripcion AS grupo_actual, G1.descripcion AS grupo_nuevo,
              S.id_solicitud, S.comentario, 
              CR.id_causarechazo AS causa_rechazo, CR.descripcion AS descripcion_causa_rechazo,
              RM.id_rechazomainframe as causa_rechazo_MAINFRAME, RM.descripcion as descripcion_causa_rechazo_MAINFRAME,
              RS.marca_reintento AS marca_reintento,
              S.comentario AS comentarioDSPA, RS.comentario AS comentarioMAINFRAME
            FROM
              ( ( ( ( ( ( ( ( ( ( (
                ( ctas_solicitudes S LEFT JOIN ( ctas_resultadosolicitudes RS, ctas_rechazosmainframe RM )
                  ON ( ( S.id_solicitud = RS.id_solicitud AND RM.id_rechazomainframe = RS.id_rechazomainframe )  ) )
                  JOIN dspa_usuarios DU
                      ON S.id_user = DU.id_user ) 
                    JOIN `ctas_valijas` V
                      ON S.id_valija = V.id_valija )
                        JOIN `ctas_lotes` L
                          ON S.id_lote = L.id_lote )
                            JOIN ctas_movimientos M
                              ON S.id_movimiento = M.id_movimiento )
                                JOIN ctas_grupos G1
                                  ON S.id_grupo_nuevo = G1.id_grupo )
                                    JOIN ctas_grupos G2
                                      ON S.id_grupo_actual = G2.id_grupo )
                                        JOIN dspa_delegaciones D
                                          ON S.delegacion = D.delegacion ) 
                                            JOIN dspa_subdelegaciones SD
                                              ON ( S.subdelegacion = SD.subdelegacion AND D.delegacion = SD.delegacion ) )
                                                JOIN dspa_delegaciones D2
                                                  ON V.delegacion = D2.delegacion )
                                                    JOIN ctas_causasrechazo CR
                                                      ON S.id_causarechazo = CR.id_causarechazo )
                                                        LEFT JOIN ctas_hist_solicitudes HS 
                                                          ON S.id_solicitud = HS.id_solicitud )
                    WHERE S.id_lote = ( SELECT id_lote from ctas_lotes ORDER BY fecha_creacion DESC LIMIT 1 )';
  /*$query = $query . " ORDER BY M.descripcion, S.usuario ;";    */
  $data = mysqli_query($dbc, $query);
  echo '<p class="mensaje">Solicitudes Capturadas - Último Lote</p>';
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Lote</th>';
  echo '<th># Área de Gestión - PDF</th>';
  echo '<th>Fecha de Captura / Fecha de Modificación</th>';
  echo '<th>Última modificación por</th>';
  echo '<th>Delegación - Subdelegación</th>';
  echo '<th>Nombre completo</th>';
  /*echo '<th>Matrícula</th>';*/
  echo '<th>Usuario(Mov)</th>';
  echo '<th>Grupo Actual->Nuevo</th>';
  echo '<th>Causas Rechazo</th>';
  /*echo '<th>Causa Rechazo Mainframe</th>';*/
  echo '<th>Estatus</th>';
  echo '<th>Comentario DSPA / Comentario Mainframe</th>';
  echo '<th>PDF</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) 
    echo '</table></br><p class="error">No se localizaron solicitudes.</p></br>';

  $i = 1;

  while ( $row = mysqli_fetch_array($data) ) {

    //Preparar texto de columna Comentario = ComentarioDSPA + // + ComentarioMAINFRAME
    if  ( (is_null($row['comentarioDSPA']) OR $row['comentarioDSPA'] == '') AND (is_null($row['comentarioMAINFRAME']) OR $row['comentarioMAINFRAME'] == '') )
          $observaciones = NULL;
    elseif ( !(is_null($row['comentarioDSPA']) OR $row['comentarioDSPA'] == '') AND (is_null($row['comentarioMAINFRAME']) OR $row['comentarioMAINFRAME'] == '') )
          $observaciones = $row['comentarioDSPA'];
    else  
          $observaciones = $row['comentarioDSPA'] . ' / ' . $row['comentarioMAINFRAME']; 

    echo '<tr class="dato condensed">';
    echo '<td align=center>' . $i. '</td>';
    echo '<td align=center>' . $row['num_lote_anio'] . '</td>';
    if ( !empty( $row['archivo_valija'] ) ) 
      $archivoPDF = '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo_valija'] . '"  target="_new">PDF</a>';
    else
      $archivoPDF = '(Sin PDF)';
    echo '<td class="mensaje"><a target="_blank" href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['num_oficio_ca'] . '</a>-' . $archivoPDF . '</td>';
    $columna_fecha_usuario = $row['fecha_cap_formato'];
    $columna_fecha_usuario2 = '';
    if ( $row['fecha_captura_ca'] == $row['fecha_modificacion'] )
      $columna_fecha_usuario2 = '';
    else {
      $columna_fecha_usuario2 = $row['fecha_mod_formato'];
    }
    echo '<td>' . $columna_fecha_usuario . '<br>' . $columna_fecha_usuario2 . '</td>';
    echo '<td>' . $row['creada_por'] . '</td>';
    echo '<td class="mensaje">' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    echo '<td class="mensaje" align="center"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
    echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
    
    //Columna Causa Rechazo DSPA
    switch ( $row['causa_rechazo'] ) {
      case 0:
        $causa_rechazo_DSPA = '';
        $color_mensaje_DSPA = '';
        /*echo '<td></td>';*/
        break;
      default:
        $causa_rechazo_DSPA = '(' . $row['causa_rechazo'] . ') ' . $row['descripcion_causa_rechazo'];
        $color_mensaje_DSPA = 'error';
        /*echo '<td class="error">(' . $row['causa_rechazo'] . ') ' . $row['descripcion_causa_rechazo'] . '</td>';*/
        break;
    }

    //Columna Causa Rechazo MainframeXX
    switch ( $row['causa_rechazo_MAINFRAME'] ) {
      case 0:
        $causa_rechazo_MAINFRAME = '';
        $color_mensaje = '';
        /*echo '<td></td>';*/
        break;

      //Si no hay valor en 'Causa de Rechazo Mainframe'...
      case NULL:
        //... y el lote NO HA SIDO atendido
        if ( is_null( $row['fecha_atendido'] ) ) {
          $causa_rechazo_MAINFRAME = 'EN ESPERA RESPUESTA MAINFRAME';
          $color_mensaje = '';
          /*echo '<td>EN ESPERA RESPUESTA MAINFRAME</td>';*/
        }
        //...si el lote ya fue atendido
        elseif ( !is_null( $row['fecha_atendido'] ) )
          $causa_rechazo_MAINFRAME = 'FALTA REGISTRAR RESPUESTA MAINFRAME';
        $color_mensaje = 'advertencia';
          /*echo '<td class="advertencia">FALTA REGISTRAR RESPUESTA MAINFRAME</td>';*/
        break;

      default:
        //Si hay valor, muestra la 'Causa de Rechazo Mainframe'
        $causa_rechazo_MAINFRAME = '(' . $row['causa_rechazo_MAINFRAME'] .') ' . $row['descripcion_causa_rechazo_MAINFRAME'];
        $color_mensaje = 'error';
        /*echo '<td class="error">(' . $row['causa_rechazo_MAINFRAME'] .') ' . $row['descripcion_causa_rechazo_MAINFRAME'] . '</td>';*/
        break;
    }

    if  ( (is_null($causa_rechazo_DSPA) OR $causa_rechazo_DSPA == '') AND (is_null($causa_rechazo_MAINFRAME) OR $causa_rechazo_MAINFRAME == '') )
          $texto_rechazos = NULL;
    elseif ( !(is_null($causa_rechazo_DSPA) OR $causa_rechazo_DSPA == '') AND (is_null($causa_rechazo_MAINFRAME) OR $causa_rechazo_MAINFRAME == '') )
          $texto_rechazos = '<span class="' . $color_mensaje_DSPA . '">' . $causa_rechazo_DSPA . '</span>';
    else  
      $texto_rechazos = '<span class="' . $color_mensaje_DSPA . '">' . $causa_rechazo_DSPA . '</span>' . ' / ' .
        '<span class="' . $color_mensaje . '">' . $causa_rechazo_MAINFRAME . '</span>'; 
    echo '<td>' . $texto_rechazos . '</td>';

    //Columna Estatus
    switch ( $row['causa_rechazo'] ) {
      case 0:
        if ( is_null( $row['fecha_atendido'] ) AND is_null( $row['causa_rechazo_MAINFRAME'] ) )
          echo '<td>EN ESPERA RESPUESTA MAINFRAME</td>';
        elseif ( !is_null( $row['fecha_atendido'] ) AND is_null( $row['causa_rechazo_MAINFRAME'] ) )
        {
          echo '<td class="advertencia">FALTA REGISTRAR RESPUESTA MAINFRAME</td>';
        }
        else
        {
          switch ( $row['causa_rechazo_MAINFRAME'] ) {
            case 0:
              echo '<td class="mensaje" align=center>ATENDIDA (' . $row['usuario_MAINFRAME'] . ')</td>';
              break;
            default:
            //...si fue rechazada por Mainframe, indicar causa de rechazo y valor de Estatus: NO PROCEDE o PENDIENTE.
            if ( $row['marca_reintento'] <> 0 )
              // ...marcar como "PENDIENTE"
              echo '<td class="advertencia" align=center>PENDIENTE</td>';
            else
              echo '<td class="error" align=center>NO PROCEDE(M)</td>';
            break;
          }
        }
        break;
      case !0:
        /*echo '<td></td>';*/
        echo '<td class="error" align=center>NO PROCEDE(D)</td>';
        break;
    }

    echo '<td>' . $observaciones . '</td>';
    if (!empty($row['archivo_solicitud'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo_solicitud'] . '"  target="_new">Ver PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    } 
    echo '</tr>';
    $i = $i + 1;
  }    
  echo '</table></br></br>';

  //Mostrar valijas
  // Obtener todas las valijas capturadas al momento
  $query = "SELECT ctas_valijas.id_valija, ctas_valijas.delegacion AS num_del, dspa_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, ctas_valijas.num_oficio_del, ctas_valijas.fecha_captura_ca,
    ctas_valijas.fecha_valija_del, ctas_valijas.comentario, ctas_valijas.archivo,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_valija = ctas_valijas.id_valija) AS num_solicitudes,
    (SELECT L.lote_anio FROM ctas_lotes L, ctas_solicitudes S WHERE S.id_valija = ctas_valijas.id_valija AND L.id_lote = S.id_lote ORDER BY L.id_lote DESC limit 1 ) AS lotes,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
  FROM ctas_valijas, dspa_delegaciones, dspa_usuarios
  WHERE ctas_valijas.delegacion = dspa_delegaciones.delegacion 
  AND   ctas_valijas.id_user = dspa_usuarios.id_user
  AND   ctas_valijas.archivo = ''
  ORDER BY ctas_valijas.id_valija DESC LIMIT 500";
  //ORDER BY ctas_valijas.fecha_captura_ca DESC LIMIT 300";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Últimas valijas capturadas que no tienen adjunto</p>';
  echo '<p class="titulo2">Agregar <a href="">nueva valija</a></p>';
  
  echo '<table class="striped" border="1">';
  echo '<tr class="dato">';
  /*echo '<tr class="dato"><th># Valija</th>';*/
  echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Área de Gestión</th>';

  echo '<th>Creada/Modificada por</th>';
  echo '<th>Fecha Captura/Modificación</th>';
  
  echo '<th># Oficio Delegación</th>';
  echo '<th>Fecha Oficio Delegación</th>';

  echo '<th>Delegación que envía</th>';
  /*echo '<th>Comentario</th>';*/
  echo '<th>Archivo</th>';
  echo '<th>Lote</th>';
  echo '<th>Cantidad de solicitudes</th>';
  
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
  }

  while ( $row = mysqli_fetch_array($data) ) {
    //$id_valija = $row['id_valija'];
    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    /*echo '<td class="lista">' . $row['id_valija'] . '</td>';*/
    echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['num_oficio_ca'] . '</a></td>';
    echo '<td class="valijas">' . $row['fecha_recepcion_ca'] . '</td>';
    echo '<td class="valijas">' . $row['creada_por'] . '</td>';
    echo '<td class="valijas">' . $row['fecha_captura_ca'] . '</td>';

    echo '<td class="valijas">' . $row['num_oficio_del'] . '</td>';
    echo '<td class="valijas">' . $row['fecha_valija_del'] . '</td>';

    echo '<td class="valijas">' . '(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . '</td>';
    
    /*echo '<td class="valijas">' . $row['comentario'] . '</td>';    */
    //echo '<td class="valijas">' . $row['archivo'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td class="mensaje"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td class="error">(Vacío)</a></td>';
    } 
    echo '<td class="valijas">' . $row['lotes'] . '</td>';
    echo '<td class="valijas">' . $row['num_solicitudes']  . '</td>';
    echo '</tr>';
  }

  echo '</table></br></br>';
  
  
  //Mostrar valijas con adjunto
  // Obtener todas las valijas capturadas al momento
  $query = "SELECT ctas_valijas.id_valija, ctas_valijas.delegacion AS num_del, dspa_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, ctas_valijas.num_oficio_del, ctas_valijas.fecha_captura_ca,
    ctas_valijas.fecha_valija_del, ctas_valijas.comentario, ctas_valijas.archivo,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_valija = ctas_valijas.id_valija) AS num_solicitudes,
    (SELECT L.lote_anio FROM ctas_lotes L, ctas_solicitudes S WHERE S.id_valija = ctas_valijas.id_valija AND L.id_lote = S.id_lote ORDER BY L.id_lote DESC limit 1 ) AS lotes,
    CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creada_por
  FROM ctas_valijas, dspa_delegaciones, dspa_usuarios
  WHERE ctas_valijas.delegacion = dspa_delegaciones.delegacion 
  AND   ctas_valijas.id_user = dspa_usuarios.id_user
  AND   ctas_valijas.archivo <> ''
  ORDER BY ctas_valijas.id_valija DESC LIMIT 100";
  //ORDER BY ctas_valijas.fecha_captura_ca DESC LIMIT 300";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Últimas valijas capturadas con adjuntos</p>';
  echo '<p class="titulo2">Agregar <a href="">nueva valija</a></p>';
  
  echo '<table class="striped" border="1">';
  echo '<tr class="dato">';
  /*echo '<tr class="dato"><th># Valija</th>';*/
  echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Área de Gestión</th>';

  echo '<th>Creada/Modificada por</th>';
  echo '<th>Fecha Captura/Modificación</th>';
  
  echo '<th># Oficio Delegación</th>';
  echo '<th>Fecha Oficio Delegación</th>';

  echo '<th>Delegación que envía</th>';
  echo '<th>Comentario</th>';
  echo '<th>Archivo</th>';
  echo '<th>Lote</th>';
  echo '<th>Cantidad de solicitudes</th>';
  
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
  }

  while ( $row = mysqli_fetch_array($data) ) {
    //$id_valija = $row['id_valija'];
    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    /*echo '<td class="lista">' . $row['id_valija'] . '</td>';*/
    echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['num_oficio_ca'] . '</a></td>';
    echo '<td class="valijas">' . $row['fecha_recepcion_ca'] . '</td>';
    echo '<td class="valijas">' . $row['creada_por'] . '</td>';
    echo '<td class="valijas">' . $row['fecha_captura_ca'] . '</td>';

    echo '<td class="valijas">' . $row['num_oficio_del'] . '</td>';
    echo '<td class="valijas">' . $row['fecha_valija_del'] . '</td>';

    echo '<td class="valijas">' . '(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . '</td>';
    
    echo '<td class="valijas">' . $row['comentario'] . '</td>';    
    //echo '<td class="valijas">' . $row['archivo'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td class="mensaje"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td class="error">(Vacío)</a></td>';
    } 
    echo '<td class="valijas">' . $row['lotes'] . '</td>';
    echo '<td class="valijas">' . $row['num_solicitudes']  . '</td>';
    echo '</tr>';
  }

  echo '</table></br></br>';


      echo '</div>';
    echo '</div>';
  echo '</div>';

  mysqli_close($dbc);
    
  // Insert the page footer
  require_once('lib/footer.php');
?>

