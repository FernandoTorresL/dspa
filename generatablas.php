<?php

  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Genera Tablas - Gestión Cuentas SINDO ';
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
            WHERE id_modulo = 19
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');
    $log = fnGuardaBitacora( 5, 112, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    exit(); 
  }


  //ALTAS
  $query = "SELECT  ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, ctas_solicitudes.nombre, 
                    ctas_movimientos.descripcion AS movimiento_descripcion, 
                    grupos1.descripcion AS grupo_nuevo, ctas_solicitudes.usuario, ctas_solicitudes.matricula, 
                    ctas_solicitudes.archivo, ctas_solicitudes.id_solicitud
            FROM ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2
            WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
            AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
            AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
            AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
            AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
            AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
            AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
            AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
            AND   ctas_solicitudes.id_causarechazo = 0
            AND   ctas_solicitudes.id_movimiento  = 1
            AND   ctas_solicitudes.id_lote        = 0
            ORDER BY ctas_solicitudes.usuario ASC";

  $data = mysqli_query($dbc, $query);
# Primer Apellido Segundo Apellido  Nombre(s) Tipo de Movimiento  Grupo Nuevo Usuario Matrícula
  echo '<p class="mensaje">ALTAS</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Primer Apellido</th>';
  echo '<th>Segundo Apellido</th>';
  echo '<th>Nombre(s)</th>';
  echo '<th>Tipo de Movimiento</th>';
  echo '<th>Grupo Nuevo</th>';
  echo '<th>Usuario</th>';
  echo '<th>Matrícula</th>';

  echo '<th>PDF</th>';
  echo '<th>#</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay nuevas solicitudes para ALTAS.</p></br>';
  }
  $i = 1;
  while ( $row = mysqli_fetch_array($data) ) {
    
    echo '<tr class="dato condensed">';
    echo '<td>' . $i . '</td>';
    echo '<td>' . $row['primer_apellido'] . '</td>';
    echo '<td>' . $row['segundo_apellido'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td align="center">' . $row['movimiento_descripcion'] . '</td>';
    echo '<td align="center">' . $row['grupo_nuevo'] . '</td>';
    echo '<td class="mensaje" align="center"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . '</a></td>';
    /*echo '<td>' . $row['usuario'] . '</td>';*/
    echo '<td align="right">' . $row['matricula'] . '</td>';


    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    }
    echo '<td>' . $i . '</td>'; 
    echo '</tr>';

    $i = $i + 1;
  }

  $i = $i -1;

  echo '</table>';

  echo '<p class="mensaje">TOTAL: ' . $i . ' ALTAS</p>';
  echo '</br></br>';

  //BAJAS
  $query = "SELECT  ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, ctas_solicitudes.nombre, 
                    ctas_movimientos.descripcion AS movimiento_descripcion, 
                    grupos2.descripcion AS grupo_actual, 
                    ctas_solicitudes.usuario, ctas_solicitudes.matricula,
                    ctas_solicitudes.archivo, ctas_solicitudes.id_solicitud
            FROM    ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, 
                    ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2
            WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
            AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
            AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
            AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
            AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
            AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
            AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
            AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
            AND   ctas_solicitudes.id_causarechazo = 0
            AND   ctas_solicitudes.id_movimiento = 2
            AND   ctas_solicitudes.id_lote      = 0
            ORDER BY ctas_solicitudes.usuario ASC";

  $data = mysqli_query($dbc, $query);
  echo '<p class="mensaje">BAJAS</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';
# Primer Apellido Segundo Apellido  Nombre(s) Tipo de Movimiento  Grupo Actual  Usuario Matrícula
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Primer Apellido</th>';
  echo '<th>Segundo Apellido</th>';
  echo '<th>Nombre(s)</th>';
  echo '<th>Tipo de Movimiento</th>';
  echo '<th>Grupo Actual</th>';
  echo '<th>Usuario</th>';
  echo '<th>Matrícula</th>';
  echo '<th>PDF</th>';
  echo '<th>#</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay nuevas solicitudes para BAJAS.</p></br>';
  }
  $i = 1;
  while ( $row = mysqli_fetch_array($data) ) {
    
    echo '<tr class="dato condensed">';
    echo '<td>' . $i . '</td>';
    echo '<td>' . $row['primer_apellido'] . '</td>';
    echo '<td>' . $row['segundo_apellido'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td align="center">' . $row['movimiento_descripcion'] . '</td>';
    echo '<td align="center">' . $row['grupo_actual'] . '</td>';
    echo '<td class="mensaje" align="center"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . '</a></td>';
    echo '<td align="right">' . $row['matricula'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    }
    echo '<td>' . $i . '</td>'; 
    echo '</tr>';

    $i = $i + 1;
  }

  $i = $i -1;

  echo '</table>';

  echo '<p class="mensaje">TOTAL: ' . $i . ' BAJAS</p>';
  echo '</br></br>';


  //CAMBIOS
  $query = "SELECT  ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, ctas_solicitudes.nombre, 
                    ctas_movimientos.descripcion AS movimiento_descripcion, 
                    grupos1.descripcion AS grupo_actual, grupos2.descripcion AS grupo_nuevo, 
                    ctas_solicitudes.usuario, ctas_solicitudes.matricula,
                    ctas_solicitudes.archivo, ctas_solicitudes.id_solicitud
            FROM    ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, 
                    ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2
            WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
            AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
            AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
            AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
            AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
            AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
            AND   ctas_solicitudes.id_grupo_actual= grupos1.id_grupo
            AND   ctas_solicitudes.id_grupo_nuevo= grupos2.id_grupo
            AND   ctas_solicitudes.id_causarechazo = 0
            AND   ctas_solicitudes.id_movimiento = 3
            AND   ctas_solicitudes.id_lote       = 0
            ORDER BY ctas_solicitudes.usuario ASC";

  $data = mysqli_query($dbc, $query);
  echo '<p class="mensaje">CAMBIOS</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';
# Primer Apellido Segundo Apellido  Nombre(s) Tipo de Movimiento  Grupo Actual  Grupo Nuevo Usuario Matrícula
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Primer Apellido</th>';
  echo '<th>Segundo Apellido</th>';
  echo '<th>Nombre(s)</th>';
  echo '<th>Tipo de Movimiento</th>';
  echo '<th>Grupo Actual</th>';
  echo '<th>Grupo Nuevo</th>';
  echo '<th>Usuario</th>';
  echo '<th>Matrícula</th>';
  echo '<th>PDF</th>';
  echo '<th>#</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay nuevas solicitudes para CAMBIOS.</p></br>';
  }
  $i = 1;
  while ( $row = mysqli_fetch_array($data) ) {
    
    echo '<tr class="dato condensed">';
    echo '<td>' . $i . '</td>';
    echo '<td>' . $row['primer_apellido'] . '</td>';
    echo '<td>' . $row['segundo_apellido'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td align="center">' . $row['movimiento_descripcion'] . '</td>';
    echo '<td align="center">' . $row['grupo_actual'] . '</td>';
    echo '<td align="center">' . $row['grupo_nuevo'] . '</td>';
    echo '<td class="mensaje" align="center"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . '</a></td>';
    echo '<td align="right">' . $row['matricula'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    } 
    echo '<td>' . $i . '</td>';
    echo '</tr>';

    $i = $i + 1;
  }

  $i = $i -1;

  echo '</table>';

  echo '<p class="mensaje">TOTAL: ' . $i . ' CAMBIOS</p>';
  echo '</br></br>';



/*CONNECT*/


  $query = "SELECT  ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, ctas_solicitudes.nombre, 
                    ctas_movimientos.descripcion AS movimiento_descripcion, 
                    grupos1.descripcion AS grupo_nuevo, ctas_solicitudes.usuario, ctas_solicitudes.matricula,
                    ctas_solicitudes.archivo, ctas_solicitudes.id_solicitud
            FROM    ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, ctas_movimientos, 
                    ctas_grupos grupos1, ctas_grupos grupos2
            WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
            AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
            AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
            AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
            AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
            AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
            AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
            AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
            AND   ctas_solicitudes.id_causarechazo = 0
            AND   ctas_solicitudes.id_movimiento = 4
            AND   ctas_solicitudes.id_lote        = 0
            ORDER BY ctas_solicitudes.usuario ASC";

  $data = mysqli_query($dbc, $query);
  echo '<p class="mensaje">CONNECT</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';
# Primer Apellido Segundo Apellido  Nombre(s) Tipo de Movimiento  Grupo Actual  Usuario Matrícula
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Primer Apellido</th>';
  echo '<th>Segundo Apellido</th>';
  echo '<th>Nombre(s)</th>';
  echo '<th>Tipo de Movimiento</th>';
  echo '<th>Grupo Nuevo</th>';
  echo '<th>Usuario</th>';
  echo '<th>Matrícula</th>';
  echo '<th>PDF</th>';
  echo '<th>#</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay nuevas solicitudes para CONNECT.</p></br>';
  }
  $i = 1;
  while ( $row = mysqli_fetch_array($data) ) {
    
    echo '<tr class="dato condensed">';
    echo '<td>' . $i . '</td>';
    echo '<td>' . $row['primer_apellido'] . '</td>';
    echo '<td>' . $row['segundo_apellido'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td>' . $row['movimiento_descripcion'] . '</td>';
    echo '<td>' . $row['grupo_nuevo'] . '</td>';
    echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . '</a></td>';
    echo '<td>' . $row['matricula'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    } 
    echo '<td>' . $i . '</td>';
    echo '</tr>';

    $i = $i + 1;
  }

  $i = $i -1;

  echo '</table>';

  echo '<p class="mensaje">TOTAL: ' . $i . ' CONNECT</p>';
  echo '</br></br>';

  //OFICIOS PARA DESCARGO
  $query = "SELECT DISTINCT 
                  IF ( DATE_FORMAT( ctas_valijas.fecha_captura_ca, '%Y' ) <> DATE_FORMAT( NOW(), '%Y' ), CONCAT( '(AÑO:', DATE_FORMAT( ctas_valijas.fecha_captura_ca, '%Y' ), ')' ), '' ) AS Anio_oficio,
                  concat('[', SUBSTR( CONCAT( '00', ctas_valijas.delegacion ), -2), 
                  IF( ctas_valijas.delegacion <> ctas_solicitudes.delegacion, 
                  concat( '(', SUBSTR( CONCAT( '00', ctas_solicitudes.delegacion ), -2), ')-' ), '-' ), 
                  ctas_valijas.num_oficio_del, '-', ctas_valijas.num_oficio_ca, ']') AS valija_descargo
            FROM  ctas_solicitudes, ctas_valijas, ctas_lotes, dspa_delegaciones, dspa_subdelegaciones, 
                  ctas_movimientos, ctas_grupos grupos1, ctas_grupos grupos2
            WHERE ctas_solicitudes.id_lote       = ctas_lotes.id_lote
            AND   ctas_solicitudes.id_valija     = ctas_valijas.id_valija
            AND   ctas_solicitudes.delegacion    = dspa_subdelegaciones.delegacion
            AND   ctas_solicitudes.subdelegacion = dspa_subdelegaciones.subdelegacion
            AND   ctas_solicitudes.delegacion    = dspa_delegaciones.delegacion
            AND   ctas_solicitudes.id_movimiento = ctas_movimientos.id_movimiento
            AND   ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
            AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
            AND   ctas_solicitudes.id_lote = 0
            ORDER BY 1 DESC, ctas_valijas.num_oficio_ca ASC, ctas_valijas.delegacion, ctas_solicitudes.delegacion, ctas_valijas.num_oficio_del";

  $data = mysqli_query($dbc, $query);
  echo '<p class="mensaje">LISTADO DE VALIJAS PARA DESCARGO (ACEPTADAS Y RECHAZADAS)</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';
# Primer Apellido Segundo Apellido  Nombre(s) Tipo de Movimiento  Grupo Actual  Usuario Matrícula
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>[Delegación solicitante (Delegación origen)- # Oficio Delegación - # Gestión DSPA] (AÑO:YYYY) </th>';
  /*echo '<th>Segundo Apellido</th>';
  echo '<th>Nombre(s)</th>';
  echo '<th>Tipo de Movimiento</th>';
  echo '<th>Grupo Actual</th>';
  echo '<th>Usuario</th>';
  echo '<th>Matrícula</th>';
  echo '<th>PDF</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas para DESCARGO.</p></br>';
  }
  $i = 1;
  $total_descargo = "";
  while ( $row = mysqli_fetch_array($data) ) {
    
    echo '<tr class="dato condensed">';
    echo '<td>' . $i . '</td>';
    echo '<td>' . $row['valija_descargo'] . $row['Anio_oficio'] . '</td>';
    /*echo '<td>' . $row['segundo_apellido'] . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td>' . $row['movimiento_descripcion'] . '</td>';
    echo '<td>' . $row['grupo_actual'] . '</td>';
    echo '<td class="mensaje"><a target="_blank" alt="Ver/Editar" href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['usuario'] . '</a></td>';
    echo '<td>' . $row['matricula'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">PDF</a></td>';
    }
    else {
      echo '<td>(Vacío)</a></td>';
    } */
    echo '</tr>';

    $i = $i + 1;
    $total_descargo = $total_descargo . ' ' . $row['valija_descargo'] . $row['Anio_oficio'];
  }

  $i = $i -1;
  
  echo '<tr>';
  echo '<td>LISTA COMPLETA</td>';
  echo '<td>' . $total_descargo . '</td>';
  echo '</tr>';
  echo '</table>';
/*  echo '</br>';
  echo 'LISTA COMPLETA: ' . $total_descargo;*/

  echo '<p class="mensaje">TOTAL: ' . $i . ' VALIJAS/OFICIO para DESCARGO</p>';
  echo '</br></br>';


  echo '<p class="mensaje">Cerrar Lote - Último lote creado</p>';

  $query = "SELECT ctas_lotes.id_lote, ctas_lotes.lote_anio, 
            ctas_lotes.fecha_modificacion, ctas_lotes.fecha_creacion, ctas_lotes.comentario,
            (SELECT COUNT(*) FROM ctas_solicitudes WHERE ctas_solicitudes.id_lote = ctas_lotes.id_lote) AS num_solicitudes,
            CONCAT(dspa_usuarios.nombre, ' ', dspa_usuarios.primer_apellido) AS creado_por, ctas_lotes.num_oficio_ca, 
            ctas_lotes.fecha_oficio_ca, ctas_lotes.num_ticket_mesa, ctas_lotes.fecha_atendido
            FROM ctas_lotes, dspa_usuarios
            WHERE ctas_lotes.id_user = dspa_usuarios.id_user
            ORDER BY 3 DESC LIMIT 1";

  $data = mysqli_query($dbc, $query);

    echo '<p class="titulo1">Último lote</p>';
    echo '<table class="striped" border="1">';
    echo '<tr class="dato">';
    echo '<th># Lote</th>';
    echo '<th># Oficio CA</th>';
    echo '<th>Fecha oficio</th>';
    echo '<th># Ticket MSI</th>';
    echo '<th>Fecha de atención</th>';
    echo '<th>Cantidad de Solicitudes</th><th>Comentario</th>';
    echo '<th>Creado por</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay lotes</p></br>';
  }

  while ( $row = mysqli_fetch_array($data) ) {
    $id_lote = $row['id_lote'];
    $lote = $row['lote_anio'];
    //echo '<tr class="dato"><td class="lista"><a href="editarlote.php?id_lote=' . $row['id_lote'] . '">' . $row['id_lote'] . ' / ' . $row['anio'] . '</a></td>';
    echo '<tr class="dato">';
      echo '<td>' . $row['lote_anio'] . '</td>';
      echo '<td>' . $row['num_oficio_ca'] . '</td>';
      echo '<td>' . $row['fecha_oficio_ca'] . '</td>';
      echo '<td>' . $row['num_ticket_mesa'] . '</td>';
      echo '<td>' . $row['fecha_atendido'] . '</td>';
      echo '<td>' . $row['num_solicitudes']  . '</td>';
      echo '<td>' . $row['comentario'] . '</td>';
      echo '<td>' . $row['creado_por'] . '</td>';
    echo '</tr>';
  }

  echo '</table>';

  $query = "SELECT id_user 
            FROM  dspa_permisos
            WHERE id_modulo = 20
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    // El usuario tiene permiso para éste módulo
    echo '<p class="error">¡ PRECAUCION - <a color="red" href="cerrarlote.php?id_lote=' . $id_lote . '">CERRAR LOTE</a> ' . $lote .' !</p>';
  }
  else {

  }
  
  require_once('lib/footer.php');
?>




