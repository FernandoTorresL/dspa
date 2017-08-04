<?php

  // Start the session
  require_once( 'commonfiles/startsession.php' );

  require_once( 'lib/ctas_appvars.php' );
  require_once( 'lib/connectBD.php' );
  
  // Insert the page header
  $page_title = 'Proyecto USAF - Ver Listado solicitudes';
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

  echo '<div class="section no-pad-bot" id="index-banner">';
  echo '<div class="container">';
  echo '<div class="row center">';

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Obtener todas las solicitudes capturadas al momento
  $query = "SELECT 
              US.id_sol_usaf,
              US.id_persona_usaf, CONCAT( P1.nombre, ' ', P1.primer_apellido ) AS personaUSAF,
              US.fecha_solicitud_del, DATE_FORMAT(US.fecha_solicitud_del, '%d%M%y') AS fSolDelFormato,
              US.delegacion, D.descripcion AS delegacion_descripcion,
              US.subdelegacion, SD.descripcion AS subdelegacion_descripcion,
              US.id_persona_solicitante, CONCAT( P2.nombre, ' ', P2.primer_apellido, ' ', P2.segundo_apellido ) AS personaSolicitante,
              PU2.descripcion AS puestoSolicitante,
              US.usuario,
              US.id_persona_titular, CONCAT( P3.nombre, ' ', P3.primer_apellido, ' ', P3.segundo_apellido ) AS personaTitular,
              PU3.descripcion AS puestoTitular,
              US.id_opcion, M.descripcion AS opcion_descripcion,
              US.region1, US.region2, US.region3, US.region4,
              US.id_causa_rechazo, UC.descripcion AS descripcion_causa_rechazo,
              US.comentario,
              US.id_user_creacion, CONCAT(DU1.nombre, ' ', DU1.primer_apellido) AS creada_por,
              US.fecha_creacion, DATE_FORMAT(US.fecha_creacion, '%d%M%y %H:%i') AS fCreacionFormato,
              US.id_user_modificacion, CONCAT(DU2.nombre, ' ', DU2.primer_apellido) AS modificada_por,
              US.fecha_modificacion, DATE_FORMAT(US.fecha_modificacion, '%d%M%y %H:%i') AS fModificacionFormato
            FROM 
              ( ( ( ( ( ( ( ( ( ( (
              usaf_solicitudes US JOIN usaf_personas P1 
                ON US.id_persona_usaf = P1.id_persona )
                  JOIN usaf_personas P2 
                    ON US.id_persona_solicitante = P2.id_persona )
                      JOIN usaf_personas P3 
                        ON US.id_persona_titular = P3.id_persona )
                          JOIN dspa_usuarios DU1 
                            ON US.id_user_creacion = DU1.id_user )
                              JOIN dspa_usuarios DU2 
                                ON US.id_user_modificacion = DU2.id_user )
                                  JOIN dspa_delegaciones D
                                    ON US.delegacion = D.delegacion ) 
                                      JOIN dspa_subdelegaciones SD
                                        ON ( US.subdelegacion = SD.subdelegacion AND US.delegacion = SD.delegacion ) )
                                          JOIN usaf_opciones M
                                            ON US.id_opcion = M.id_opcion )
                                              JOIN usaf_causasrechazo UC
                                                ON US.id_causa_rechazo = UC.id_causa_rechazo )
                                                  JOIN dspa_puestos PU2
                                                    ON P2.id_puesto = PU2.id_puesto )
                                                      JOIN dspa_puestos PU3
                                                        ON P3.id_puesto = PU3.id_puesto ) ORDER BY id_sol_usaf";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes USAF Capturadas</p>';
  echo '<table class="striped" border="1">';
  echo '<tr>';
  echo '<th>#</th>';
  echo '<th>Normativo que atendió</th>';
  echo '<th>Fecha Atención</th>';
  echo '<th>Delegación - Subdelegación del Solicitante</th>';
  echo '<th>Solicitante (Puesto)</th>';
  echo '<th>Usuario [Opción]</th>';
  echo '<th>Titular de la cuenta (Puesto)</th>';
  echo '<th>Regiones</th>';
  echo '<th>Causa Rechazo</th>';
  echo '<th>Comentario</th>';
  echo '<th>Fecha de Captura / Fecha de Modificación</th>';
  echo '<th>Creada/Modificada por</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay solicitudes.</p></br>';
  }

  $i = 1;
  while ( $row = mysqli_fetch_array($data) ) {

    echo '<tr class="dato condensed">';
    echo '<td>' . $i. '</td>';
    echo '<td>' . $row['personaUSAF'] . '</td>';
    echo '<td>' . $row['fSolDelFormato'] . '</td>';
    echo '<td class="mensaje">(' . $row['delegacion'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['subdelegacion'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td>' . $row['personaSolicitante'] . '(' . $row['puestoSolicitante'] . ')</td>';
    echo '<td class="mensaje">' . $row['usuario'] . ' [' . $row['opcion_descripcion'] . ']</td>';
    echo '<td>' . $row['personaTitular'] . '(' . $row['puestoTitular'] . ')</td>';
    if ( !empty($row['region1']) )
      $cizs = '1|';
    else 
      $cizs = '-|';
    if ( !empty($row['region2']) )
      $cizs = $cizs . '2|';
    else 
      $cizs = $cizs . '-|';
    if ( !empty($row['region3']) )
      $cizs = $cizs . '3|';
    else 
      $cizs = $cizs . '-|';
    if ( !empty($row['region4']) )
      $cizs = $cizs . '4';
    else 
      $cizs = $cizs . '-';
    echo '<td>' . $cizs . '</td>';
    switch ( $row['id_causa_rechazo'] ) {
      case 0:
        echo '<td class="mensaje" align=center>ATENDIDA</td>';
        break;
      default:
        echo '<td class="error">' . $row['id_causa_rechazo'] .'-' . $row['descripcion_causa_rechazo'] . '</td>';
        break;
    }
    echo '<td>' . $row['comentario'] . '</td>';
    $columna_fecha_usuario = $row['fCreacionFormato'];
    $columna_fecha_usuario2 = '';
    if ( $row['fecha_creacion'] == $row['fecha_modificacion'] )
      $columna_fecha_usuario2 = '';
    else {
      $columna_fecha_usuario2 = $row['fModificacionFormato'];
    }
    echo '<td>' . $columna_fecha_usuario . '<br>' . $columna_fecha_usuario2 . '</td>';
    echo '<td>' . $row['creada_por'] . '</td>';
    echo '</tr>';

    $i = $i + 1;
  }    

  echo '</table></br></br>';

  echo '</div>';
  echo '</div>';
  echo '</div>';

  mysqli_close($dbc);

  // Insert the page footer
  require_once('lib/footer.php');
?>
