<?php

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/ctas_appvars.php');
  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = 'Ver Inventario - Gestión Cuentas SINDO';
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
  $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-VerInventario' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  if ( !isset( $_GET['id_delegacion'] ) ) {
    $id_delegacion_bitacora = $_SESSION['id_delegacion'];
  } else {
    $id_delegacion_bitacora = $_GET['id_delegacion'];
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  $query = "SELECT id_user
            FROM  dspa_permisos
            WHERE id_modulo = 25
            AND   id_user   = " . $_SESSION['id_user'];
  /*echo $query;*/
  $data = mysqli_query($dbc, $query);

  if ( mysqli_num_rows( $data ) == 1 ) {
    $row = mysqli_fetch_array( $data );
    // El usuario tiene permiso para éste módulo
    $Usuario = $row['id_user'];
    /*$Puesto = $row['id_puesto'];*/
  }
  else {
    echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
    require_once('lib/footer.php');

    $log = fnGuardaBitacora( 5, 117, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_delegacion:' . $id_delegacion_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

    exit(); 
  }

  if ( $Usuario <> 23  && $Usuario <> 2 ) {
    /*--Revisión de delegación para el usuario actual*/
    $query = "SELECT delegacion 
              FROM  dspa_usuarios
              WHERE id_puesto IN (3, 4)
              AND   id_user = " . $_SESSION['id_user'];

    $query = $query . " AND   delegacion = " . $id_delegacion_bitacora;

    /*echo $query;*/
    $data = mysqli_query($dbc, $query);

    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para ver la delegación
      $log = fnGuardaBitacora( 3, 117, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_delegacion:' . $id_delegacion_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    }
    else {
      echo '<p class="advertencia">No tiene permisos para consultar otra delegación o su puesto no corresponde. Por favor contacte al Administrador del sitio. </p>';
      require_once('lib/footer.php');

      $log = fnGuardaBitacora( 5, 117, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_delegacion:' . $id_delegacion_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

      exit(); 
    }
  }
  else
    // El usuario tiene permiso para ver la delegación
      $log = fnGuardaBitacora( 3, 117, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_delegacion:' . $id_delegacion_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
  
  $query = 'SELECT DISTINCT
              USER_DETALLE.userid_racf  AS "Usuario",
              Tabla_CIZ_Activos.CIZ_Activos AS "CIZ_Activos",  
              USER_DETALLE.nombre       AS "Nombre",
              USER_DETALLE.install_data AS "Info",
              USERID.delegacion         AS "Del",
              IF( USER_DETALLE.id_area = 1, "Cuenta Genérica", AREA.descripcion ) AS "Tipo_Cuenta",
              USER_DETALLE.id_bd_mainframe
            FROM 
                racf_det_userid USER_DETALLE 
                  JOIN racf_userid_ciz USER_CIZ
                    ON ( USER_DETALLE.userid_racf = USER_CIZ.userid_racf AND 
                         USER_DETALLE.id_ciz = USER_CIZ.id_ciz )
                      JOIN racf_userid USERID
                        ON USER_CIZ.userid_racf = USERID.userid_racf
                          JOIN dspa_delegaciones DEL
                            ON USERID.delegacion = DEL.delegacion
                              JOIN dspa_area AREA
                                ON USER_DETALLE.id_area = AREA.id_area
                                      JOIN 
                                        ( SELECT 
                                            racf_userid.userid_racf, 
                                            CONCAT(
                                              IFNULL (
                                                ( SELECT DISTINCT A.id_ciz FROM racf_det_userid AS A
                                                  WHERE A.id_ciz = 1 AND A.userid_racf = racf_userid.userid_racf ), 
                                                  "-" ) , 
                                              "|" ,
                                              IFNULL (
                                                ( SELECT DISTINCT A.id_ciz FROM racf_det_userid AS A
                                                  WHERE A.id_ciz = 2 AND A.userid_racf = racf_userid.userid_racf ), 
                                                  "-" ) , 
                                              "|" ,
                                              IFNULL (
                                                ( SELECT DISTINCT A.id_ciz FROM racf_det_userid AS A
                                                  WHERE A.id_ciz = 3 AND A.userid_racf = racf_userid.userid_racf ), 
                                                  "-" ) 
                                            ) AS CIZ_Activos
                                          FROM racf_userid ) Tabla_CIZ_Activos 
                                            ON USERID.userid_racf = Tabla_CIZ_Activos.userid_racf 
                                          WHERE USERID.delegacion = ';

  if ( !isset( $_GET['id_delegacion'] ) ) {
    $query = $query . $_SESSION['id_delegacion'];
    $id_delegacion_bitacora = $_SESSION['id_delegacion'];
    
  } else {
    $query = $query . $_GET['id_delegacion'];
    $id_delegacion_bitacora = $_GET['id_delegacion'];
  }

  $query = $query . ' ORDER BY 6, USER_DETALLE.userid_racf';
?>

  <div class="contenedor_inventario">

    <p class="titulo1">Inventario de la delegación</p>

    <div class=inventario>
<?php        
     
  /*echo $query;*/
  $data = mysqli_query( $dbc, $query );

  if ( mysqli_num_rows( $data ) <> 0 ) {

    $log = fnGuardaBitacora( 3, 117, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_delegacion:' . $id_delegacion_bitacora . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
  
    $i = 1;

    if (mysqli_num_rows($data) == 0) {
      echo '</table></br><p class="error">No hay cuentas RACF asignadas a esta delegación.</p></br>';
    }

    // Mostrar el inventario de la delegación
    echo '</br>';

    echo '<table class="striped" border="1">';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Usuario</th>';
    echo '<th>CIZ Activos</th>';
    echo '<th>Nombre completo</th>';
    echo '<th>Grupos activos</th>';
    echo '<th>Info</th>';
    echo '<th>Tipo Cuenta</th>';
    echo '</tr>';

    while ( $row = mysqli_fetch_array($data) ) {
      echo '<tr>';
      echo '<td align=center>' . $i. '</td>';
      echo '<td class="mensaje">' . $row['Usuario'] . '</td>';
      echo '<td>' . $row['CIZ_Activos'] . '</td>';
      echo '<td>' . $row['Nombre'] . '</td>';

      $query = 'SELECT DISTINCT IF( GPO = "SSCONX", "SSCONS", GPO ) DESC_GRUPO FROM 
                  ( SELECT  G.id_grupo ID, G.descripcion GPO FROM racf_det_userid U 
                      JOIN ctas_grupos G ON U.id_gpo_owner = G.id_grupo WHERE U.userid_racf= "' . $row['Usuario'] . '" ) GRUPOS 
                UNION
                SELECT DISTINCT IF( GPO = "SSCONX", "SSCONS", GPO ) DESC_GRUPO FROM 
                  ( SELECT  G.id_grupo ID, G.descripcion GPO FROM racf_det_userid U 
                      JOIN ctas_grupos G ON U.id_gpo_default = G.id_grupo WHERE U.userid_racf= "' . $row['Usuario'] . '" ) GRUPOS
                UNION
                SELECT DISTINCT IF( GPO = "SSCONX", "SSCONS", GPO ) DESC_GRUPO FROM 
                  ( SELECT  G.id_grupo ID, G.descripcion GPO FROM racf_det_userid_gpo U 
                      JOIN ctas_grupos G ON U.id_gpo_userid = G.id_grupo WHERE U.userid_racf= "' . $row['Usuario'] . '" ) GRUPOS
                UNION
                SELECT DISTINCT IF( GPO = "SSCONX", "SSCONS", GPO ) DESC_GRUPO FROM 
                  ( SELECT  G.id_grupo ID, G.descripcion GPO FROM racf_det_userid_gpo U 
                      JOIN ctas_grupos G ON U.id_gpo_conn_owner = G.id_grupo WHERE U.userid_racf= "' . $row['Usuario'] . '" ) GRUPOS
                ORDER BY 1';
      
      /*echo $query;*/
      $result = mysqli_query( $dbc, $query );

      $cadena_de_grupos = '';

      while ( $row2 = mysqli_fetch_array( $result ) )
        $cadena_de_grupos = $cadena_de_grupos . $row2['DESC_GRUPO'] . '|';

      echo '<td>' . $cadena_de_grupos . '</td>';

      echo '<td>' . $row['Info'] . '</td>';

      echo '<td>' . $row['Tipo_Cuenta'] . '</td>';

      echo '</tr>';
      $i = $i + 1;
    }    

    echo '</table></br></br>';
?>


    </div>

    <!-- <div class=datos_delegacion>
      Datos Delegación
    </div>
    
    <div class=foto_delegacion>
      Foto Delegación
    </div>

    <div>
      Resumen
    </div>

    <div>
      Movimientos recientes
    </div> -->

  </div>
    

<?php

  }

  else {
    echo '</br><p class="error">No se localizó información para la delegación:' . $id_delegacion_bitacora . '. Verifica por favor con el Administrador del sitio.</p>';
  }

    // Insert the page footer
    require_once('lib/footer.php');
  ?>
