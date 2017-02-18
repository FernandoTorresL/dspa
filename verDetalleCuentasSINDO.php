<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Gestión Cuentas SINDO - Ver Solicitud';
  require_once( 'header.php' );
  
  // Show the navigation menu
  require_once( 'navmenu.php' );
  require_once( 'funciones.php');

  // Make sure the user is logged in before going any further.
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacute;gina.</p>';
    // Insert the page footer
    require_once('footer.php');
    exit();
  }

  require_once('appvars.php');
  require_once('connectvars.php');

  /*echo '<section id="main-container">';
  echo '<div class="container">';*/
  echo '<div class="section no-pad-bot" id="index-banner">';
    echo '<div class="container">';
      echo '<div class="row center">';

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Mostrar solicitudes del lote 0
  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = "SELECT 
    ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, 
    ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion,
    ctas_lotes.lote_anio AS num_lote_anio, 
    ctas_solicitudes.delegacion AS num_del, ctas_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.delegacion AS num_del_val, 
    ctas_solicitudes.subdelegacion AS num_subdel, ctas_subdelegaciones.descripcion AS subdelegacion_descripcion, 
    ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
    ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
    ctas_movimientos.descripcion AS movimiento_descripcion, 
    grupos1.descripcion AS grupo_nuevo, grupos2.descripcion AS grupo_actual, 
    ctas_solicitudes.comentario, ctas_causasrechazo.id_causarechazo, ctas_causasrechazo.descripcion AS causa_rechazo, ctas_solicitudes.archivo,
    CONCAT(ctas_usuarios.first_name) AS creada_por
    FROM ctas_solicitudes, ctas_valijas, ctas_lotes, ctas_delegaciones, ctas_subdelegaciones, ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2, ctas_usuarios, ctas_causasrechazo
    WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
    AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
    AND   ctas_solicitudes.delegacion    = ctas_subdelegaciones.delegacion
    AND   ctas_solicitudes.subdelegacion = ctas_subdelegaciones.subdelegacion
    AND   ctas_solicitudes.delegacion    = ctas_delegaciones.delegacion
    AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
    AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   ctas_solicitudes.user_id = ctas_usuarios.user_id
    AND   ctas_solicitudes.id_causarechazo = ctas_causasrechazo.id_causarechazo
    AND   ctas_solicitudes.id_lote = 0
    ORDER BY ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC ";
    //ORDER BY ctas_solicitudes.id_movimiento ASC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC";
    //AND   ctas_solicitudes.id_causarechazo = 0
    //ORDER BY ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC, ctas_solicitudes.id_movimiento ASC";
    //ORDER BY ctas_solicitudes.fecha_modificacion ASC, ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.id_movimiento ASC";
    
    //AND   ctas_solicitudes.id_lote = 106
    //AND   ctas_solicitudes.id_lote = 79
    //AND   ctas_solicitudes.id_movimiento=2
    
    //ctas_movimientos.descripcion, ctas_solicitudes.usuario,
    //AND   ctas_solicitudes.rechazado <> 1
    //AND   ctas_solicitudes.id_lote = 4
    //AND   ctas_solicitudes.rechazado <> 1
  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes Capturadas sin lote</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table class="striped" border="1">';
  /*echo '<thead>';*/
  echo '<tr>';
  //echo '<th># Valija</th>';
  echo '<th># de Lote</th>';
  /*echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Recepción CA</th>';*/
  echo '<th>Creada por</th>';
  /*echo '<th>Delegación - Subdelegación</th>';*/
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
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td>' . $row['num_lote_anio'] . '</td>';
    /*echo '<td>' . $row['num_oficio_ca'] . '</td>';*/
    /*echo '<td>' . $row['fecha_recepcion_ca'] . '</td>';*/
    echo '<td>' . $row['creada_por'] . '</td>';
    /*echo '<td>' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';*/
    echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    //echo '<td>' . $row['primer_apellido'] . '</td>'; 
    //echo '<td>' . $row['segundo_apellido'] . '</td>'; 
    //echo '<td>' . $row['nombre'] . '</td>';
    /*echo '<td>' . $row['matricula'] . '</td>'; */
    /*echo '<td>' . $row['curp'] . '</td>'; */
    //echo '<td>' . $row['curp_correcta'] . '</td>'; 
    //echo '<td>' . $row['cargo'] . '</td>';
    echo '<td><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
    /*echo '<td>' . $row['movimiento_descripcion'] . '</td>'; */
    echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
    echo '<td>' . $row['comentario'] . '</td>';
    /*echo '<td><a target="_blank" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">Ver</a>' . '</td>';*/
    echo '<td>' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';
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

//Mostrar solicitudes del penúltimo lote
  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = "SELECT 
    ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, 
    ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion,
    ctas_lotes.lote_anio AS num_lote_anio, 
    ctas_solicitudes.delegacion AS num_del, ctas_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.delegacion AS num_del_val, 
    ctas_solicitudes.subdelegacion AS num_subdel, ctas_subdelegaciones.descripcion AS subdelegacion_descripcion, 
    ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
    ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
    ctas_movimientos.descripcion AS movimiento_descripcion, 
    grupos1.descripcion AS grupo_nuevo, grupos2.descripcion AS grupo_actual, 
    ctas_solicitudes.comentario, ctas_causasrechazo.id_causarechazo, ctas_causasrechazo.descripcion AS causa_rechazo, ctas_solicitudes.archivo,
    CONCAT(ctas_usuarios.first_name) AS creada_por
    FROM ctas_solicitudes, ctas_valijas, ctas_lotes, ctas_delegaciones, ctas_subdelegaciones, ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2, ctas_usuarios, ctas_causasrechazo
    WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
    AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
    AND   ctas_solicitudes.delegacion    = ctas_subdelegaciones.delegacion
    AND   ctas_solicitudes.subdelegacion = ctas_subdelegaciones.subdelegacion
    AND   ctas_solicitudes.delegacion    = ctas_delegaciones.delegacion
    AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
    AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   ctas_solicitudes.user_id = ctas_usuarios.user_id
    AND   ctas_solicitudes.id_causarechazo = ctas_causasrechazo.id_causarechazo
    AND   ctas_solicitudes.id_lote = (SELECT id_lote from ctas_lotes ORDER BY fecha_creacion DESC LIMIT 1)
    ORDER BY ctas_solicitudes.fecha_modificacion ASC, ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.id_movimiento ASC";
    //ORDER BY ctas_solicitudes.id_movimiento ASC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC";
    
    //AND   ctas_solicitudes.id_lote = 2
    //ORDER BY ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC, ctas_solicitudes.id_movimiento ASC";
    
    //AND   ctas_solicitudes.id_causarechazo = 0
    //ORDER BY ctas_solicitudes.id_solicitud DESC, ctas_solicitudes.usuario ASC, ctas_solicitudes.fecha_modificacion DESC ";
    
    //AND   ctas_solicitudes.id_lote = 106
    //AND   ctas_solicitudes.id_lote = 79
    //AND   ctas_solicitudes.id_movimiento=2
    
    
    //ctas_movimientos.descripcion, ctas_solicitudes.usuario,
    //AND   ctas_solicitudes.rechazado <> 1
    //AND   ctas_solicitudes.id_lote = 4
    //AND   ctas_solicitudes.rechazado <> 1
  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes Capturadas - Último lote</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table class="striped" border="1">';
  /*echo '<thead>';*/
  echo '<tr>';
  //echo '<th># Valija</th>';
  echo '<th># de Lote</th>';
  /*echo '<th># Área de Gestión</th>';
  echo '<th>Fecha Recepción CA</th>';*/
  echo '<th>Creada por</th>';
  /*echo '<th>Delegación - Subdelegación</th>';*/
  echo '<th>Nombre completo</th>';
  /*echo '<th>Matrícula</th>';*/
  /*echo '<th>CURP</th>';*/
  //echo '<th>CURP correcta</th>';
  //echo '<th>Cargo</th>';
  echo '<th>Usuario(Mov)</th>';
  /*echo '<th>Movimiento</th>';*/
  echo '<th>Grupo Actual->Nuevo</th>';
  echo '<th>Comentario</th>';
  /*echo '<th></th>';*/
  echo '<th>Causa Rechazo</th>';
  echo '<th>PDF</th>';
  echo '</tr>';
  /*echo '</thead>';  */

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay solicitudes para el último lote.</p></br>';
    /*require_once('footer.php');
    exit();*/
  }

  while ( $row = mysqli_fetch_array($data) ) {

    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    /*echo '<tbody>';*/
    echo '<tr class="dato condensed">';
    //echo '<td class="lista"><a href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['id_solicitud'] . '</a></td>';
    //echo '<td class="lista">' . $row['id_solicitud'] . '</td>';
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td>' . $row['num_lote_anio'] . '</td>';
    /*echo '<td>' . $row['num_oficio_ca'] . '</td>';*/
    /*echo '<td>' . $row['fecha_recepcion_ca'] . '</td>';*/
    echo '<td>' . $row['creada_por'] . '</td>';
    /*echo '<td>' . $row['num_del_val'] . ' (' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';*/
    echo '<td class="dato condensed">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    //echo '<td>' . $row['primer_apellido'] . '</td>'; 
    //echo '<td>' . $row['segundo_apellido'] . '</td>'; 
    //echo '<td>' . $row['nombre'] . '</td>';
    /*echo '<td>' . $row['matricula'] . '</td>'; */
    /*echo '<td>' . $row['curp'] . '</td>'; */
    //echo '<td>' . $row['curp_correcta'] . '</td>'; 
    //echo '<td>' . $row['cargo'] . '</td>';
    echo '<td><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . ' (' . $row['movimiento_descripcion'] . ')</a></td>';
    /*echo '<td>' . $row['movimiento_descripcion'] . '</td>'; */
    echo '<td>' . $row['grupo_actual'] . '>' . $row['grupo_nuevo'] . '</td>'; 
    echo '<td>' . $row['comentario'] . '</td>';
    /*echo '<td><a target="_blank" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">Ver</a>' . '</td>';*/
    echo '<td>' . $row['id_causarechazo'] .'-' . $row['causa_rechazo'] . '</td>';
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
    CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creado_por, ctas_lotes.num_oficio_ca, 
    ctas_lotes.fecha_oficio_ca, ctas_lotes.num_ticket_mesa, ctas_lotes.fecha_atendido
    FROM ctas_lotes, ctas_usuarios
    WHERE ctas_lotes.user_id = ctas_usuarios.user_id
    ORDER BY ctas_lotes.fecha_modificacion DESC LIMIT 10";

  $data = mysqli_query($dbc, $query);

/*
  $mi_pdf = MM_UPLOADPATH_CTASSINDO . '\\' . '1452713181 60953_3.pdf';
  header('Content-type: application/pdf');
  header('Content-Disposition: attachment; filename="'. $mi_pdf . '"');
  readfile($mi_pdf);
  */

  echo '<p class="titulo1">Últimos 20 lotes</p>';
  
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


  echo '<p class="titulo2">Agregar <a href="">nuevo lote</a></p>';

  echo '<table class="striped" border="1">';
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

  //Mostrar valijas
  // Obtener todas las valijas capturadas al momento
  $query = "SELECT ctas_valijas.id_valija, ctas_valijas.delegacion AS num_del, ctas_delegaciones.descripcion AS delegacion_descripcion, 
    ctas_valijas.num_oficio_ca, ctas_valijas.fecha_recepcion_ca, ctas_valijas.num_oficio_del, 
    ctas_valijas.fecha_valija_del, ctas_valijas.comentario, ctas_valijas.archivo,
    (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_valija = ctas_valijas.id_valija) AS num_solicitudes,
    CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creada_por
  FROM ctas_valijas, ctas_delegaciones, ctas_usuarios
  WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion 
  AND   ctas_valijas.user_id = ctas_usuarios.user_id
  ORDER BY ctas_valijas.id_valija DESC LIMIT 30";
  //ORDER BY ctas_valijas.fecha_captura_ca DESC LIMIT 300";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">&Uacuteltimas valijas capturadas</p>';
  echo '<p class="titulo2">Agregar <a href="">nueva valija</a></p>';
  
  echo '<table class="striped" border="1">';
  //echo '<tr class="dato"><th># Valija</th>';
  echo '<tr class="dato">';
  echo '<th># &Aacute;rea de Gesti&oacute;n</th>';
  echo '<th>Fecha &Aacute;rea de Gesti&oacute;n</th>';
  
  echo '<th># Oficio Delegaci&oacute;n</th>';
  echo '<th>Fecha Oficio Delegaci&oacute;n</th>';

  echo '<th>Delegaci&oacute;n que env&iacute;a</th>';
  echo '<th>Comentario</th>';
  /*echo '<th>Archivo</th>';*/
  echo '<th>Cantidad de solicitudes</th>';
  /*echo '<th>Creada por</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
    require_once('footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {
    //$id_valija = $row['id_valija'];
    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
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
  require_once('footer.php');
?>

