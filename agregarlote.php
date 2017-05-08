<?php
    require_once('commonfiles/startsession.php');

    require_once('lib/ctas_appvars.php');
    require_once('lib/connectvars.php');

    require_once('commonfiles/funciones.php');

    // Insert the page header
    $page_title = 'Agregar Lote - Gestión Cuentas SINDO ';
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
    $ResultadoConexion = fnConnectBD( $_SESSION['id_user'],  $_SESSION['ip_address'], 'EQUIPO.' . $_SESSION['host'], 'Conn-AgregarLote' );
    if ( !$ResultadoConexion ) {
      // Hubo un error en la conexión a la base de datos;
      printf( " Connect failed: %s", mysqli_connect_error() );
      require_once('lib/footer.php');
      exit();
    }

    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    $query = "SELECT id_user 
              FROM dspa_permisos
              WHERE id_modulo = 9
              AND   id_user   = " . $_SESSION['id_user'];
    /*echo $query;*/
    $data = mysqli_query($dbc, $query);

    if ( mysqli_num_rows( $data ) == 1 ) {
      // El usuario tiene permiso para éste módulo
    }
    else {
      echo '<p class="advertencia">No tiene permisos activos para este módulo. Por favor contacte al Administrador del sitio. </p>';
      require_once('lib/footer.php');
      $log = fnGuardaBitacora( 5, 101, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
      exit(); 
    }

?>

  <section id="main-container">
    <div class="row">

      <div class="col s5">
        <div class="container">
        </div>
      </div>

      <div class="col s2">
        <div class="row">
          <div class="signup-box">
            <form class="signup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="input-field">
                <i class="material-icons prefix">view_quilt</i>
                <input type="text" class="validate" name="new_lote" id="new_lote" length="9" value="<?php if ( !empty( $new_lote ) ) echo $new_lote; ?>" />
                <label data-error="Demasiados caracteres" for="new_lote">Nuevo Lote</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">comment</i>
                <textarea class="materialize-textarea" class="validate" id="comentario" length="100" name="comentario"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
                <label data-error="Insuficiente" for="comentario">Comentario</label>
              </div>
              <div class="section" align="center">
                <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Crear lote<i class="material-icons right">send</i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col s5">
        <div class="container">
          <div class="row">
              <?php
                /*$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if ( mysqli_connect_errno () ) {
                  printf("Connect failed: %s\n", mysqli_connect_error());
                  return "Falló la conexión a base de datos";
                  require_once('footer.php');
                  exit(); 
                }*/

                /*$error_msg = fnConnect( $dbc );*/

                if ( isset( $_POST['submit'] ) ) {
                  echo '<div class="signup-box">';
                  echo '<div class="container">';
                  // Conectarse a la BD
                  $new_lote = mysqli_real_escape_string($dbc, trim($_POST['new_lote']));
                  $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
                  //$error = false;

                  if ( !empty( $comentario ) && !empty( $new_lote ) ) {
                    $query = "INSERT INTO ctas_lotes 
                      ( lote_anio, fecha_creacion, fecha_modificacion, comentario, id_user )
                      VALUES 
                      ( '$new_lote', NOW(), NOW(), '$comentario', " . $_SESSION['id_user'] . ")";

                    mysqli_query($dbc, $query);

                    $log = fnGuardaBitacora( 1, 101, $_SESSION['id_user'],  $_SESSION['ip_address'], 'Lote:' . $new_lote . '|CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );

                    // Confirm success with the user
                    echo '<p class="mensaje"><strong>El nuevo lote ' . $new_lote . ' ha sido creado exitosamente.</strong></p>';
                    /*$new_lote = "";
                    $comentario = "";*/
                  } else {
                    $error_msg = 'Debes ingresar todos los datos para registrar el lote. ' . $error_msg;
                  }
                }
                echo '<p class="error" align="justify">' . $error_msg . '</p>';
                echo '</div>';
                echo '</div>';
              ?>
            
          </div>
        </div>
      </div>

    </div>
    
  </section>
  <?php
    // Insert the page footer
    require_once('lib/footer.php');
  ?>
