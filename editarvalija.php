<?php

    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectvars.php');

    require_once('commonfiles/funciones.php');

    // Insert the page header
    $page_title = 'Agregar Valija - Gestión Cuentas SINDO ';
    require_once('lib/header.php');

    // Show the navigation menu
    require_once('lib/navmenu.php');

    $error_msg = "";
    $output_form = 'yes';

    if ( !isset( $_GET['valija_id'] ) ) {
      $valija_id = $_SESSION['valija_id'];
    } else {
      $valija_id = $_GET['valija_id'];
    }
    
    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    $query = "SELECT id_user 
              FROM dspa_permisos
              WHERE id_modulo = 13
              AND   id_user   = " . $_SESSION['id_user'];
    /*echo $query;*/
    $data = mysqli_query($dbc, $query);


    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para éste módulo
      $log = fnGuardaBitacora( 3, 105, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_valija:' . $valija_id . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
    }
    else {


      /*echo $valija_id;
      echo 'id_valija:' . $valija_id . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'];*/
      echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
      $log = fnGuardaBitacora( 5, 105, $_SESSION['id_user'],  $_SESSION['ip_address'], 'id_valija:' . $valija_id . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
      echo '<p class="advertencia">En construcción</p>';
      require_once('lib/footer.php');
      exit();
    }

    echo '<p class="advertencia">En construcción</p>';
    require_once('lib/footer.php');
    exit();


    